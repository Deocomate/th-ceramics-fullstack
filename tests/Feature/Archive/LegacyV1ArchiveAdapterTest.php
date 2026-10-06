<?php

use App\Domains\Archive\Adapters\LegacyV1ArchiveAdapter;
use App\Domains\Archive\ContentArchiveService;
use App\Domains\Catalog\Domain\RoofTileAccessoryCategory;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductDisplayOption;
use App\Domains\Catalog\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

beforeEach(function () {
    Storage::fake('public');
});

it('maps the original roof accessory category to the current category without changing its public ID', function () {
    $path = tempnam(sys_get_temp_dir(), 'legacy_accessory_');
    $zip = new ZipArchive;
    $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $zip->addFromString('data/phu_kien_ngoi_ct.ndjson', json_encode([
        'phu_kien_ngoi_ct_id' => 45,
        'name' => 'Ngói Bờ Nóc cũ',
        'category_type' => 'bo_noc',
        'created_at' => '2026-01-01 00:00:00',
        'updated_at' => '2026-01-01 00:00:00',
    ])."\n");
    $zip->close();
    $zip->open($path);

    try {
        $result = ['added' => 0, 'updated' => 0, 'skipped' => 0];
        app(LegacyV1ArchiveAdapter::class)->importTable(
            $zip, 'phu_kien_ngoi_ct', ['source_id' => (string) Str::uuid()], [], $result, fn ($row) => $row,
        );

        $product = Product::where('type_key', 'phu_kien_ngoi_ct')->where('legacy_id', 45)->firstOrFail();
        expect($product->category_type)->toBe(RoofTileAccessoryCategory::TYPE_BO_NOC)
            ->and($product->public_id)->toBe(45)
            ->and($result['added'])->toBe(1);
    } finally {
        $zip->close();
        unlink($path);
    }
});

it('imports legacy format archive directly into canonical catalog tables', function () {
    Storage::disk('public')->put('uploads/legacy-nad.webp', 'legacy nad image');
    Storage::disk('public')->put('uploads/legacy-den.webp', 'legacy den image');

    $zipPath = tempnam(sys_get_temp_dir(), 'legacy_archive_test_').'.zip';
    $zip = new ZipArchive;
    expect($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE))->toBeTrue();

    $nadData = json_encode([
        'ngoi_am_duong_ct_id' => 99,
        'code' => 'NAD-LEGACY-099',
        'name' => 'Ngói Âm Dương Legacy 99',
        'price' => 50000,
        'images' => ['storage/uploads/legacy-nad.webp'],
        'des' => ['Mô tả legacy'],
        'size' => '200x200',
        'is_delete' => 0,
        'created_at' => '2026-01-01 00:00:00',
        'updated_at' => '2026-01-01 00:00:00',
    ])."\n";

    $nadColorData = json_encode([
        'mau_sac_ngoi_am_duong_ct_id' => 10,
        'name' => 'Màu Đỏ Gốm',
        'image' => 'storage/uploads/color-do.webp',
        'created_at' => '2026-01-01 00:00:00',
        'updated_at' => '2026-01-01 00:00:00',
    ])."\n";

    $denData = json_encode([
        'den_vuon_gom_su_ct_id' => 88,
        'name' => 'Đèn Sứ Legacy 88',
        'category_type' => 'den_su',
        'color' => 'Trắng',
        'images' => ['storage/uploads/legacy-den.webp'],
        'des' => ['Mô tả đèn'],
        'is_delete' => 0,
        'created_at' => '2026-01-01 00:00:00',
        'updated_at' => '2026-01-01 00:00:00',
    ])."\n";

    $denVariantData = json_encode([
        'phan_loai_den_vuon_gom_su_ct_id' => 201,
        'den_vuon_gom_su_ct_id' => 88,
        'name' => 'Phân loại Đèn 1',
        'code' => 'DEN-VAR-201',
        'price' => 250000,
        'image' => 'storage/uploads/legacy-den.webp',
        'is_delete' => 0,
        'created_at' => '2026-01-01 00:00:00',
        'updated_at' => '2026-01-01 00:00:00',
    ])."\n";

    $sqlData = '-- mock legacy sql dump';
    $mediaNad = 'legacy nad image';
    $mediaDen = 'legacy den image';
    $mediaColor = 'color red image';

    $files = [
        'database.sql' => ['sha256' => hash('sha256', $sqlData), 'bytes' => strlen($sqlData)],
        'data/ngoi_am_duong_ct.ndjson' => ['sha256' => hash('sha256', $nadData), 'bytes' => strlen($nadData)],
        'data/mau_sac_ngoi_am_duong_ct.ndjson' => ['sha256' => hash('sha256', $nadColorData), 'bytes' => strlen($nadColorData)],
        'data/den_vuon_gom_su_ct.ndjson' => ['sha256' => hash('sha256', $denData), 'bytes' => strlen($denData)],
        'data/phan_loai_den_vuon_gom_su_ct.ndjson' => ['sha256' => hash('sha256', $denVariantData), 'bytes' => strlen($denVariantData)],
        'media/storage/uploads/legacy-nad.webp' => ['sha256' => hash('sha256', $mediaNad), 'bytes' => strlen($mediaNad)],
        'media/storage/uploads/legacy-den.webp' => ['sha256' => hash('sha256', $mediaDen), 'bytes' => strlen($mediaDen)],
        'media/storage/uploads/color-do.webp' => ['sha256' => hash('sha256', $mediaColor), 'bytes' => strlen($mediaColor)],
    ];

    $manifest = [
        'format_version' => 1,
        'source_schema' => 'legacy',
        'source_id' => (string) Str::uuid(),
        'exported_at_utc' => now('UTC')->toIso8601String(),
        'export_timezone' => 'Asia/Ho_Chi_Minh',
        'tables' => [
            'ngoi_am_duong_ct' => 1,
            'mau_sac_ngoi_am_duong_ct' => 1,
            'den_vuon_gom_su_ct' => 1,
            'phan_loai_den_vuon_gom_su_ct' => 1,
        ],
        'files' => $files,
    ];

    $zip->addFromString('manifest.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $zip->addFromString('database.sql', $sqlData);
    $zip->addFromString('data/ngoi_am_duong_ct.ndjson', $nadData);
    $zip->addFromString('data/mau_sac_ngoi_am_duong_ct.ndjson', $nadColorData);
    $zip->addFromString('data/den_vuon_gom_su_ct.ndjson', $denData);
    $zip->addFromString('data/phan_loai_den_vuon_gom_su_ct.ndjson', $denVariantData);
    $zip->addFromString('media/storage/uploads/legacy-nad.webp', $mediaNad);
    $zip->addFromString('media/storage/uploads/legacy-den.webp', $mediaDen);
    $zip->addFromString('media/storage/uploads/color-do.webp', $mediaColor);
    $zip->close();

    try {
        $archive = app(ContentArchiveService::class);
        $preview = $archive->preview($zipPath);

        expect($preview['manifest']['source_schema'])->toBe('legacy');
        expect($preview['add'])->toBeGreaterThanOrEqual(2);

        $result = $archive->import($zipPath);
        expect($result['added'])->toBeGreaterThanOrEqual(2);

        // Verify products imported into canonical tables
        $nadProduct = Product::where('type_key', 'ngoi_am_duong_ct')->first();
        expect($nadProduct)->not->toBeNull();
        expect($nadProduct->name)->toBe('Ngói Âm Dương Legacy 99');
        expect($nadProduct->public_id)->toBe(99);
        expect($nadProduct->price)->toBe(50000);
        expect($nadProduct->getRawOriginal('created_at'))->toBe('2026-01-01 00:00:00');
        expect($nadProduct->getRawOriginal('updated_at'))->toBe('2026-01-01 00:00:00');
        expect((int) $nadProduct->priority)->toBe(0);

        // Verify display options
        $displayOption = ProductDisplayOption::where('type_key', 'ngoi_am_duong_ct')->first();
        expect($displayOption)->not->toBeNull();
        expect($displayOption->name)->toBe('Màu Đỏ Gốm');
        expect($displayOption->getRawOriginal('created_at'))->toBe('2026-01-01 00:00:00');

        // Verify den vuon product & variant
        $denProduct = Product::where('type_key', 'den_vuon_gom_su_ct')->first();
        expect($denProduct)->not->toBeNull();
        expect($denProduct->public_id)->toBe(88);
        expect($denProduct->category_type)->toBe('den_su');

        $variant = ProductVariant::where('sku', 'DEN-VAR-201')->first();
        expect($variant)->not->toBeNull();
        expect($variant->price)->toBe(250000);
        expect($variant->product_id)->toBe($denProduct->id);
        expect($variant->getRawOriginal('created_at'))->toBe('2026-01-01 00:00:00');
        expect($variant->getRawOriginal('updated_at'))->toBe('2026-01-01 00:00:00');

        // Verify idempotency on second import
        $secondResult = $archive->import($zipPath);
        expect($secondResult['added'])->toBe(0);
        expect($secondResult['updated'])->toBeGreaterThanOrEqual(2);
        expect($nadProduct->fresh()->getRawOriginal('updated_at'))->toBe('2026-01-01 00:00:00');
    } finally {
        @unlink($zipPath);
    }
});
