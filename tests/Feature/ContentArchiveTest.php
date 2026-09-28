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
        expect($zip->getFromName('media/storage/uploads/sample.webp'))->toBe('image bytes');
        $zip->close();

        expect($archive->preview($path)['unchanged'])->toBe(1);
        DB::table('archive_test_items')->delete();
        Storage::disk('public')->delete('uploads/sample.webp');

        expect($archive->import($path)['added'])->toBe(1);
        expect($archive->import($path)['skipped'])->toBe(1);
        expect(DB::table('archive_test_items')->value('name'))->toBe('Original');
        expect(Storage::disk('public')->exists('imports/'.hash('sha256', 'image bytes').'/sample.webp'))->toBeTrue();
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
