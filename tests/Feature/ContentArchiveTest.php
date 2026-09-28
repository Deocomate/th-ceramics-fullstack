<?php

use App\Services\ContentArchiveService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Schema::create('archive_test_items', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('image')->nullable();
        $table->timestamps();
    });
    config()->set('content_archive.tables', ['archive_test_items']);
    Storage::fake('public');
});

afterEach(function () {
    Schema::dropIfExists('archive_test_items');
});

it('exports a verified zip and imports content idempotently', function () {
    Storage::disk('public')->put('uploads/sample.webp', 'image bytes');
    DB::table('archive_test_items')->insert([
        'id' => 7,
        'name' => 'Original',
        'image' => 'uploads/sample.webp',
        'created_at' => '2026-01-01 00:00:00',
        'updated_at' => '2026-01-01 00:00:00',
    ]);

    $archive = app(ContentArchiveService::class);
    $path = $archive->export();
    try {
        $zip = new ZipArchive;
        expect($zip->open($path))->toBeTrue();
        expect($zip->getFromName('database.sql'))->toContain('archive_test_items');
        expect($zip->getFromName('database.sql'))->toContain('DROP TABLE IF EXISTS `archive_test_items`');
        expect($zip->getFromName('media/storage/uploads/sample.webp'))->toBe('image bytes');
        $zip->close();

        expect($archive->preview($path)['unchanged'])->toBe(1);
        DB::table('archive_test_items')->delete();
        Storage::disk('public')->delete('uploads/sample.webp');

        expect($archive->import($path)['added'])->toBe(1);
        expect($archive->import($path)['skipped'])->toBe(1);
        expect(DB::table('archive_test_items')->value('name'))->toBe('Original');
        expect(Storage::disk('public')->exists('uploads/sample.webp'))->toBeTrue();
    } finally {
        @unlink($path);
    }
});

it('preserves destination media when a path has different bytes', function () {
    Storage::disk('public')->put('uploads/sample.webp', 'original bytes');
    DB::table('archive_test_items')->insert([
        'id' => 8, 'name' => 'Media conflict', 'image' => 'uploads/sample.webp',
        'created_at' => '2026-01-01 00:00:00', 'updated_at' => '2026-01-01 00:00:00',
    ]);
    $archive = app(ContentArchiveService::class);
    $path = $archive->export();
    try {
        DB::table('archive_test_items')->delete();
        Storage::disk('public')->put('uploads/sample.webp', 'destination bytes');
        expect($archive->import($path)['added'])->toBe(1);
        $replacement = 'imports/'.hash('sha256', 'original bytes').'/sample.webp';
        expect(DB::table('archive_test_items')->value('image'))->toBe($replacement);
        expect(Storage::disk('public')->get('uploads/sample.webp'))->toBe('destination bytes');
        expect(Storage::disk('public')->get($replacement))->toBe('original bytes');
    } finally {
        @unlink($path);
    }
});

it('keeps business and identity tables outside the content allowlist', function () {
    $excluded = config('content_archive.excluded_tables');
    $tables = config('content_archive.tables');
    expect(array_intersect($excluded, $tables))->toBe([]);
    expect($excluded)->toContain('users', 'orders', 'order_items', 'coupons', 'consultation_requests');
});

it('accepts a legacy archive and backfills the unified product tables', function () {
    config()->set('content_archive.tables', ['ngoi_am_duong_ct', 'products', 'product_variants', 'product_media', 'product_legacy_ids']);
    $archive = app(ContentArchiveService::class);
    $path = $archive->directory().DIRECTORY_SEPARATOR.'legacy-test-'.bin2hex(random_bytes(8)).'.zip';
    $row = [
        'ngoi_am_duong_ct_id' => 91,
        'code' => 'ZIP-LEGACY-091',
        'name' => 'Ngói từ ZIP cũ',
        'images' => '[]',
        'price' => 15000,
        'is_delete' => 0,
        'created_at' => '2026-01-01 00:00:00',
        'updated_at' => '2026-01-01 00:00:00',
    ];
    $data = json_encode($row, JSON_UNESCAPED_UNICODE)."\n";
    $sql = '-- test';
    $manifest = [
        'format_version' => 1,
        'source_schema' => 'legacy',
        'source_id' => $archive->sourceId(),
        'exported_at_utc' => '2026-01-01T00:00:00Z',
        'tables' => ['ngoi_am_duong_ct' => 1],
        'files' => [
            'data/ngoi_am_duong_ct.ndjson' => ['sha256' => hash('sha256', $data), 'bytes' => strlen($data)],
            'database.sql' => ['sha256' => hash('sha256', $sql), 'bytes' => strlen($sql)],
        ],
    ];
    $zip = new ZipArchive;
    expect($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE))->toBeTrue();
    $zip->addFromString('data/ngoi_am_duong_ct.ndjson', $data);
    $zip->addFromString('database.sql', $sql);
    $zip->addFromString('manifest.json', json_encode($manifest));
    $zip->close();
    try {
        expect($archive->import($path)['added'])->toBe(1);
        expect(DB::table('products')->where('name', 'Ngói từ ZIP cũ')->count())->toBe(1);
        expect(DB::table('product_variants')->where('sku', 'ZIP-LEGACY-091')->value('price'))->toBe(15000);
    } finally {
        @unlink($path);
    }
});

it('exports the complete allowlisted schema without business tables', function () {
    config()->set('content_archive.tables', (require config_path('content_archive.php'))['tables']);
    $path = app(ContentArchiveService::class)->export();
    try {
        $zip = new ZipArchive;
        expect($zip->open($path))->toBeTrue();
        $manifest = json_decode($zip->getFromName('manifest.json'), true);
        expect($manifest['tables'])->toHaveKey('products');
        expect($manifest['tables'])->not->toHaveKey('users');
        expect($manifest['tables'])->not->toHaveKey('orders');
        $zip->close();
    } finally {
        @unlink($path);
    }
});
