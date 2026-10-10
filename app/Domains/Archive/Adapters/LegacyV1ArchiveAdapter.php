<?php

namespace App\Domains\Archive\Adapters;

use App\Domains\Archive\Application\Ports\CatalogArchivePort;
use App\Domains\Catalog\Domain\PhuKienNgoiCategory;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class LegacyV1ArchiveAdapter
{
    public function __construct(private readonly CatalogArchivePort $catalog) {}

    private const LEGACY_PRODUCT_TABLES = [
        'ngoi_am_duong_ct' => ['pk' => 'ngoi_am_duong_ct_id', 'has_code' => true, 'has_price' => true],
        'ngoi_hai_van_mieu_ct' => ['pk' => 'ngoi_hai_van_mieu_ct_id', 'has_code' => false, 'has_price' => false],
        'ngoi_hai_co_ct' => ['pk' => 'ngoi_hai_co_ct_id', 'has_code' => false, 'has_price' => false],
        'gach_hoa_thong_gio_ct' => ['pk' => 'gach_hoa_thong_gio_ct_id', 'has_code' => true, 'has_price' => true],
        'gach_trang_tri_ct' => ['pk' => 'gach_trang_tri_ct_id', 'has_code' => true, 'has_price' => true],
        'gach_co_bat_trang_ct' => ['pk' => 'gach_co_bat_trang_ct_id', 'has_code' => true, 'has_price' => true],
        'linh_vat_phong_thuy_ct' => ['pk' => 'linh_vat_phong_thuy_ct_id', 'has_code' => true, 'has_price' => true],
        'lan_can_gom_su_ct' => ['pk' => 'lan_can_gom_su_ct_id', 'has_code' => false, 'has_price' => false],
        'den_vuon_gom_su_ct' => ['pk' => 'den_vuon_gom_su_ct_id', 'has_code' => false, 'has_price' => false],
        'phu_kien_ngoi_ct' => ['pk' => 'phu_kien_ngoi_ct_id', 'has_code' => false, 'has_price' => false],
    ];

    private const LEGACY_VARIANT_TABLES = [
        'phan_loai_den_vuon_gom_su_ct' => [
            'pk' => 'phan_loai_den_vuon_gom_su_ct_id',
            'fk' => 'den_vuon_gom_su_ct_id',
            'parent_table' => 'den_vuon_gom_su_ct',
        ],
        'phan_loai_lan_can_gom_su_ct' => [
            'pk' => 'phan_loai_lan_can_gom_su_ct_id',
            'fk' => 'lan_can_gom_su_ct_id',
            'parent_table' => 'lan_can_gom_su_ct',
        ],
        'phan_loai_phu_kien_ngoi_ct' => [
            'pk' => 'phan_loai_phu_kien_ngoi_ct_id',
            'fk' => 'phu_kien_ngoi_ct_id',
            'parent_table' => 'phu_kien_ngoi_ct',
        ],
        'mau_sac_ngoi_hai_van_mieu_ct' => [
            'pk' => 'mau_sac_ngoi_hai_van_mieu_ct_id',
            'fk' => 'ngoi_hai_van_mieu_ct_id',
            'parent_table' => 'ngoi_hai_van_mieu_ct',
        ],
        'mau_sac_ngoi_hai_co_ct' => [
            'pk' => 'mau_sac_ngoi_hai_co_ct_id',
            'fk' => 'ngoi_hai_co_ct_id',
            'parent_table' => 'ngoi_hai_co_ct',
        ],
    ];

    public static function isLegacyCatalogTable(string $table): bool
    {
        return isset(self::LEGACY_PRODUCT_TABLES[$table])
            || isset(self::LEGACY_VARIANT_TABLES[$table])
            || $table === 'mau_sac_ngoi_am_duong_ct'
            || in_array($table, ['product_legacy_ids', 'variant_legacy_ids'], true);
    }

    public function importTable(
        ZipArchive $zip,
        string $table,
        array $manifest,
        array $rewrites,
        array &$result,
        callable $rewriteMediaCallback
    ): void {
        if (in_array($table, ['product_legacy_ids', 'variant_legacy_ids'], true)) {
            // These tables are internal mapping tables in legacy/hybrid schema; skip direct insert
            return;
        }

        $stream = $zip->getStream("data/{$table}.ndjson");
        if (! $stream) {
            return;
        }

        try {
            while (($line = fgets($stream)) !== false) {
                $row = json_decode($line, true, flags: JSON_THROW_ON_ERROR);
                if (! is_array($row)) {
                    continue;
                }
                $row = $rewriteMediaCallback($row, $rewrites);

                if (isset(self::LEGACY_PRODUCT_TABLES[$table])) {
                    $this->importProductRow($table, $row, $manifest, $result);
                } elseif (isset(self::LEGACY_VARIANT_TABLES[$table])) {
                    $this->importVariantRow($table, $row, $manifest, $result);
                } elseif ($table === 'mau_sac_ngoi_am_duong_ct') {
                    $this->importDisplayOptionRow($row, $manifest, $result);
                }
            }
        } finally {
            fclose($stream);
        }
    }

    private function importProductRow(string $table, array $row, array $manifest, array &$result): void
    {
        $config = self::LEGACY_PRODUCT_TABLES[$table];
        $legacyId = (int) $row[$config['pk']];
        $sourceId = $manifest['source_id'];

        $productData = [
            'type_key' => $table,
            'category_type' => $table === 'phu_kien_ngoi_ct'
                ? PhuKienNgoiCategory::normalizeLegacy($row['category_type'] ?? null)
                : ($row['category_type'] ?? null),
            'legacy_type' => $table,
            'legacy_id' => $legacyId,
            'name' => $row['name'],
            'color' => $row['color'] ?? 'Tự chọn',
            'des' => $this->sanitizeJsonArray($row['des'] ?? null),
            'size' => $row['size'] ?? null,
            'size_image' => $row['size_image'] ?? null,
            'size_des' => $this->sanitizeJsonArray($row['size_des'] ?? null),
            'video' => $row['video'] ?? null,
            'dinh_muc' => $row['dinh_muc'] ?? null,
            'weight' => $row['weight'] ?? null,
            'priority' => (int) ($row['priority'] ?? 0),
            'is_delete' => (bool) ($row['is_delete'] ?? false),
            'created_at' => $row['created_at'] ?? now(),
            'updated_at' => $row['updated_at'] ?? now(),
        ];

        $images = is_string($row['images'] ?? null) ? json_decode($row['images'], true) : ($row['images'] ?? []);
        $catalogResult = $this->catalog->upsertLegacyProduct(
            $table,
            $legacyId,
            $productData,
            $config['has_code'] || $config['has_price'],
            [
                'sku' => $row['code'] ?? null,
                'price' => isset($row['price']) ? (int) $row['price'] : null,
                'is_default' => true,
                'is_delete' => (bool) ($row['is_delete'] ?? false),
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ],
            is_array($images) ? $images : [],
        );
        $result[$catalogResult['status']]++;

        DB::table('content_archive_record_maps')->updateOrInsert([
            'source_id' => $sourceId,
            'table_name' => $table,
            'source_record_id' => $legacyId,
        ], [
            'target_record_id' => $catalogResult['product_id'],
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }

    private function importVariantRow(string $table, array $row, array $manifest, array &$result): void
    {
        $config = self::LEGACY_VARIANT_TABLES[$table];
        $legacyId = (int) $row[$config['pk']];
        $parentLegacyId = (int) $row[$config['fk']];
        $sourceId = $manifest['source_id'];

        $variantData = [
            'name' => $row['name'] ?? null,
            'sku' => $row['code'] ?? null,
            'price' => isset($row['price']) ? (int) $row['price'] : null,
            'image' => $row['image'] ?? null,
            'is_default' => false,
            'is_delete' => (bool) ($row['is_delete'] ?? false),
            'created_at' => $row['created_at'] ?? now(),
            'updated_at' => $row['updated_at'] ?? now(),
        ];

        // Check mapping or match by sku/product_id
        $mapping = DB::table('content_archive_record_maps')
            ->where('source_id', $sourceId)->where('table_name', $table)
            ->where('source_record_id', $legacyId)->first();

        if ($mapping) {
            $variantData['id'] = (int) $mapping->target_record_id;
        }

        $catalogResult = $this->catalog->upsertLegacyVariant(
            $config['parent_table'],
            $parentLegacyId,
            $legacyId,
            $variantData,
        );
        if ($catalogResult === null) {
            $result['skipped']++;

            return;
        }
        $result[$catalogResult['status']]++;

        DB::table('content_archive_record_maps')->updateOrInsert([
            'source_id' => $sourceId,
            'table_name' => $table,
            'source_record_id' => $legacyId,
        ], [
            'target_record_id' => $catalogResult['variant_id'],
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }

    private function importDisplayOptionRow(array $row, array $manifest, array &$result): void
    {
        $legacyId = (int) $row['mau_sac_ngoi_am_duong_ct_id'];
        $sourceId = $manifest['source_id'];

        $data = [
            'type_key' => 'ngoi_am_duong_ct',
            'legacy_id' => $legacyId,
            'name' => $row['name'],
            'image' => $row['image'],
            'sort_order' => (int) ($row['sort_order'] ?? 0),
            'created_at' => $row['created_at'] ?? now(),
            'updated_at' => $row['updated_at'] ?? now(),
        ];

        $catalogResult = $this->catalog->upsertLegacyDisplayOption($legacyId, $data);
        $result[$catalogResult['status']]++;

        DB::table('content_archive_record_maps')->updateOrInsert([
            'source_id' => $sourceId,
            'table_name' => 'mau_sac_ngoi_am_duong_ct',
            'source_record_id' => $legacyId,
        ], [
            'target_record_id' => $catalogResult['option_id'],
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }

    private function sanitizeJsonArray(mixed $value): ?array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? $decoded : [$value];
        }
        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value), fn ($v) => $v !== ''));
        }

        return null;
    }
}
