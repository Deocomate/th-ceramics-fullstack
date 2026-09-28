<?php

use App\Services\ContentArchiveService;
use App\Services\ProductBackfillService;
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

it('preserves unchanged JSON text during import', function () {
    $json = '{ "gallery" : [ "first.jpg", "second.jpg" ] }';
    DB::table('archive_test_items')->insert([
        'id' => 31, 'name' => 'JSON formatting', 'image' => $json,
        'created_at' => '2026-01-01 00:00:00', 'updated_at' => '2026-01-01 00:00:00',
    ]);
    $archive = app(ContentArchiveService::class);
    $path = $archive->export();
    try {
        DB::table('archive_test_items')->delete();
        expect($archive->import($path)['added'])->toBe(1);
        expect(DB::table('archive_test_items')->value('image'))->toBe($json);
        expect($archive->preview($path)['unchanged'])->toBe(1);
        expect($archive->import($path)['skipped'])->toBe(1);
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

it('includes media embedded in HTML and rewrites a conflicting reference', function () {
    Storage::disk('public')->put('uploads/manual.pdf', 'source PDF');
    DB::table('archive_test_items')->insert([
        'id' => 9, 'name' => 'Catalog link',
        'image' => '<a href="/storage/uploads/manual.pdf?v=2">PDF</a>',
        'created_at' => '2026-01-01 00:00:00', 'updated_at' => '2026-01-01 00:00:00',
    ]);
    $archive = app(ContentArchiveService::class);
    $path = $archive->export();
    try {
        $zip = new ZipArchive;
        $zip->open($path);
        expect($zip->getFromName('media/storage/uploads/manual.pdf'))->toBe('source PDF');
        $zip->close();
        DB::table('archive_test_items')->delete();
        Storage::disk('public')->put('uploads/manual.pdf', 'destination PDF');
        expect($archive->import($path)['added'])->toBe(1);
        expect(DB::table('archive_test_items')->value('image'))
            ->toContain('/storage/imports/'.hash('sha256', 'source PDF').'/manual.pdf?v=2');
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

it('round trips a hybrid archive through an empty product catalog', function () {
    config()->set('content_archive.tables', (require config_path('content_archive.php'))['tables']);
    $legacyId = DB::table('ngoi_am_duong_ct')->insertGetId([
        'code' => 'HYBRID-001', 'name' => 'Ngói hybrid', 'images' => '[]', 'price' => 41000,
        'is_delete' => 0, 'created_at' => now(), 'updated_at' => now(),
    ]);
    app(ProductBackfillService::class)->backfill();
    $archive = app(ContentArchiveService::class);
    $path = $archive->export();
    try {
        DB::table('product_legacy_ids')->delete();
        DB::table('product_media')->delete();
        DB::table('product_variants')->delete();
        DB::table('products')->delete();
        DB::table('ngoi_am_duong_ct')->delete();
        expect($archive->preview($path)['add'])->toBeGreaterThanOrEqual(4);
        $archive->import($path);
        expect(DB::table('ngoi_am_duong_ct')->where('ngoi_am_duong_ct_id', $legacyId)->value('name'))->toBe('Ngói hybrid');
        expect(DB::table('product_variants')->where('sku', 'HYBRID-001')->value('price'))->toBe(41000);
        expect($archive->import($path)['added'])->toBe(0);
        $again = $archive->export();
        try {
            $zip = new ZipArchive;
            $zip->open($again);
            $manifest = json_decode($zip->getFromName('manifest.json'), true);
            expect($manifest['source_schema'])->toBe('hybrid');
            expect($manifest['tables']['products'])->toBe(1);
            $zip->close();
        } finally {
            @unlink($again);
        }
    } finally {
        @unlink($path);
    }
});

it('tracks an external source so repeated imports stay idempotent', function () {
    DB::table('archive_test_items')->insert([
        'id' => 12, 'name' => 'Remote item', 'image' => null,
        'created_at' => '2026-01-01 00:00:00', 'updated_at' => '2026-01-01 00:00:00',
    ]);
    $archive = app(ContentArchiveService::class);
    $path = $archive->export();
    try {
        $zip = new ZipArchive;
        $zip->open($path);
        $manifest = json_decode($zip->getFromName('manifest.json'), true);
        $manifest['source_id'] = '0db61d20-2acf-4f4f-b0fe-52b4b588fe68';
        $zip->addFromString('manifest.json', json_encode($manifest));
        $zip->close();
        DB::table('archive_test_items')->delete();

        expect($archive->preview($path)['add'])->toBe(1);
        expect($archive->import($path)['added'])->toBe(1);
        expect($archive->preview($path)['unchanged'])->toBe(1);
        expect($archive->import($path)['skipped'])->toBe(1);
        expect(DB::table('content_archive_record_maps')->where('source_id', $manifest['source_id'])->count())->toBe(1);
    } finally {
        @unlink($path);
    }
});
it('does not attach an external child to a different local parent with the same ID', function () {
    Schema::create('archive_test_parents', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });
    Schema::create('archive_test_children', function (Blueprint $table) {
        $table->id();
        $table->foreignId('archive_test_parent_id')->constrained('archive_test_parents');
        $table->string('name');
        $table->timestamps();
    });
    config()->set('content_archive.tables', ['archive_test_parents', 'archive_test_children']);
    DB::table('archive_test_parents')->insert(['id' => 1, 'name' => 'Source', 'created_at' => now(), 'updated_at' => now()]);
    DB::table('archive_test_children')->insert(['id' => 1, 'archive_test_parent_id' => 1, 'name' => 'Child', 'created_at' => now(), 'updated_at' => now()]);
    $archive = app(ContentArchiveService::class);
    $path = $archive->export();
    try {
        $zip = new ZipArchive;
        $zip->open($path);
        $manifest = json_decode($zip->getFromName('manifest.json'), true);
        $manifest['source_id'] = 'db088089-57dc-4521-bd48-e15d93f9193a';
        $zip->addFromString('manifest.json', json_encode($manifest));
        $zip->close();
        DB::table('archive_test_children')->delete();
        DB::table('archive_test_parents')->where('id', 1)->update(['name' => 'Destination']);

        $report = $archive->preview($path);
        expect($report['conflict'])->toBe(2);
        expect($report['conflicts'][1]['reason'])->toBe('unmapped_parent');
        expect($archive->import($path)['skipped'])->toBe(2);
        expect(DB::table('archive_test_children')->count())->toBe(0);
    } finally {
        @unlink($path);
        Schema::dropIfExists('archive_test_children');
        Schema::dropIfExists('archive_test_parents');
    }
});

it('reports a matching code at a different destination ID before import', function () {
    Schema::table('archive_test_items', fn (Blueprint $table) => $table->string('code')->nullable());
    DB::table('archive_test_items')->insert([
        'id' => 20, 'name' => 'Source', 'code' => 'SAME-CODE',
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $archive = app(ContentArchiveService::class);
    $path = $archive->export();
    try {
        $zip = new ZipArchive;
        $zip->open($path);
        $manifest = json_decode($zip->getFromName('manifest.json'), true);
        $manifest['source_id'] = '66dfe10d-8f35-4d10-88ed-fc66c7acaf4e';
        $zip->addFromString('manifest.json', json_encode($manifest));
        $zip->close();
        DB::table('archive_test_items')->delete();
        DB::table('archive_test_items')->insert([
            'id' => 21, 'name' => 'Destination', 'code' => 'SAME-CODE',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $report = $archive->preview($path);
        expect($report['conflict'])->toBe(1);
        expect($report['conflicts'][0]['reason'])->toBe('duplicate_code');
        expect($archive->import($path)['skipped'])->toBe(1);
        expect(DB::table('archive_test_items')->count())->toBe(1);
    } finally {
        @unlink($path);
    }
});

it('rejects a modified archive and an archive over its configured size limit', function () {
    DB::table('archive_test_items')->insert([
        'id' => 30, 'name' => 'Original', 'created_at' => now(), 'updated_at' => now(),
    ]);
    $archive = app(ContentArchiveService::class);
    $path = $archive->export();
    try {
        config()->set('content_archive.max_uncompressed_bytes', 1);
        expect(fn () => $archive->preview($path))->toThrow(RuntimeException::class);
        config()->set('content_archive.max_uncompressed_bytes', 20_000_000_000);

        $zip = new ZipArchive;
        $zip->open($path);
        $data = $zip->getFromName('data/archive_test_items.ndjson');
        $zip->addFromString('data/archive_test_items.ndjson', str_replace('Original', 'Modified', $data));
        $zip->close();
        expect(fn () => $archive->preview($path))->toThrow(RuntimeException::class, 'Checksum không khớp');
    } finally {
        @unlink($path);
    }
});
