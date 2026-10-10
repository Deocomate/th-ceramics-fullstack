<?php

use App\Domains\Archive\Adapters\LegacyV1ArchiveAdapter;
use App\Domains\Catalog\Domain\ProductTypeRegistry;
use App\Domains\Catalog\Domain\PhuKienNgoiCategory;
use App\Domains\Catalog\Infrastructure\Models\Product;
use App\Domains\Catalog\Infrastructure\PublicIdAllocator;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require dirname(__DIR__).'/vendor/autoload.php';
$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$apply = in_array('--apply', $argv, true);
$backup = null;
foreach ($argv as $argument) {
    if (str_starts_with($argument, '--backup=')) {
        $backup = substr($argument, strlen('--backup='));
    }
}

try {
    foreach (['products', 'product_variants', 'product_media', 'product_display_options', 'product_public_ids', 'variant_public_ids', 'content_archive_record_maps'] as $table) {
        if (! Schema::hasTable($table)) {
            throw new RuntimeException('Run additive migrations first: missing '.$table);
        }
    }

    $source = [];
    $keys = [];
    foreach (ProductTypeRegistry::all() as $type => $configuration) {
        $keys[$type] = $configuration['pk'];
        if ($configuration['variant_table']) {
            $keys[$configuration['variant_table']] = $configuration['variant_table'].'_id';
        }
    }
    $keys['mau_sac_ngoi_am_duong_ct'] = 'mau_sac_ngoi_am_duong_ct_id';
    foreach ($keys as $table => $key) {
        if (! Schema::hasTable($table)) {
            throw new RuntimeException('Missing legacy source table '.$table);
        }
        $source[$table] = DB::table($table)->orderBy($key)->get()->map(fn ($row) => (array) $row)->all();
    }
    $sourceHash = hash('sha256', json_encode($source, JSON_THROW_ON_ERROR));

    $verify = function () use ($source): array {
        $failures = [];
        $check = function (string $reference, string $field, mixed $expected, mixed $actual) use (&$failures): void {
            if ($expected === null ? $actual !== null : (is_array($expected) ? $expected != $actual : (string) $expected !== (string) $actual)) {
                $failures[] = $reference.': '.$field;
            }
        };
        $normalizeArray = function (mixed $value): ?array {
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                $value = is_array($decoded) ? $decoded : [$value];
            }

            return is_array($value) ? array_values(array_filter(array_map('trim', $value), fn ($item) => $item !== '')) : null;
        };
        foreach (ProductTypeRegistry::all() as $type => $configuration) {
            foreach ($source[$type] as $row) {
                $id = (int) $row[$configuration['pk']];
                $reference = $type.'/'.$id;
                $product = Product::query()->where('type_key', $type)->where('legacy_id', $id)->with(['publicId', 'variants.publicId', 'media'])->first();
                if (! $product) {
                    $failures[] = $reference.': missing product';

                    continue;
                }
                $check($reference, 'public_id', $id, $product->getRelation('publicId')?->public_id);
                $check($reference, 'legacy_type', $type, $product->legacy_type);
                foreach (['name', 'category_type', 'size', 'size_image', 'video', 'dinh_muc', 'weight', 'created_at', 'updated_at'] as $field) {
                    $expected = $row[$field] ?? null;
                    if ($type === 'phu_kien_ngoi_ct' && $field === 'category_type') {
                        $expected = PhuKienNgoiCategory::normalizeLegacy($expected);
                    }
                    $check($reference, $field, $expected, $product->getRawOriginal($field));
                }
                $check($reference, 'color', $row['color'] ?? 'Tự chọn', $product->color);
                foreach (['des', 'size_des'] as $field) {
                    $check($reference, $field, $normalizeArray($row[$field] ?? null), $product->{$field});
                }
                $check($reference, 'priority', (int) ($row['priority'] ?? 0), $product->priority);
                $check($reference, 'is_delete', (int) ($row['is_delete'] ?? 0), (int) $product->is_delete);
                $images = is_string($row['images'] ?? null) ? json_decode($row['images'], true) : ($row['images'] ?? []);
                $expectedMedia = [];
                foreach ($images ?? [] as $index => $image) {
                    $path = is_string($image) ? $image : ($image['path'] ?? $image['url'] ?? '');
                    if ($path !== '') {
                        $expectedMedia[] = ['path' => $path, 'kind' => is_array($image) && ($image['type'] ?? '') === 'video' ? 'video' : 'image', 'sort_order' => $index];
                    }
                }
                $check($reference, 'media', $expectedMedia, $product->media->map(fn ($media) => $media->only(['path', 'kind', 'sort_order']))->all());
                if (! $configuration['has_variants']) {
                    $variant = $product->variants->firstWhere('is_default', true);
                    if (! $variant || ! $variant->getRelation('publicId')) {
                        $failures[] = $reference.': missing default variant/public ID';
                    } else {
                        $check($reference, 'sku', $row['code'] ?? null, $variant->sku);
                        $check($reference, 'price', isset($row['price']) ? (int) $row['price'] : null, $variant->price);
                        $check($reference, 'variant is_delete', (int) ($row['is_delete'] ?? 0), (int) $variant->is_delete);
                    }
                }
            }
            if ($configuration['variant_table']) {
                $table = $configuration['variant_table'];
                foreach ($source[$table] as $row) {
                    $id = (int) $row[$table.'_id'];
                    $variant = DB::table('variant_public_ids')->join('product_variants', 'product_variants.id', '=', 'variant_public_ids.product_variant_id')
                        ->join('products', 'products.id', '=', 'product_variants.product_id')
                        ->where('variant_public_ids.type_key', $type)->where('variant_public_ids.public_id', $id)
                        ->select('product_variants.*', 'products.legacy_id as parent_legacy_id')->first();
                    $reference = $table.'/'.$id;
                    if (! $variant) {
                        $failures[] = $reference.': missing variant';

                        continue;
                    }
                    $check($reference, 'parent', $row[$configuration['pk']], $variant->parent_legacy_id);
                    foreach (['name', 'image', 'created_at', 'updated_at'] as $field) {
                        $check($reference, $field, $row[$field] ?? null, $variant->{$field});
                    }
                    $check($reference, 'sku', $row['code'] ?? null, $variant->sku);
                    $check($reference, 'price', isset($row['price']) ? (int) $row['price'] : null, $variant->price);
                    $check($reference, 'is_default', 0, $variant->is_default);
                    $check($reference, 'is_delete', (int) ($row['is_delete'] ?? 0), (int) $variant->is_delete);
                }
            }
        }
        foreach ($source['mau_sac_ngoi_am_duong_ct'] as $row) {
            $id = $row['mau_sac_ngoi_am_duong_ct_id'];
            $option = DB::table('product_display_options')->where('type_key', 'ngoi_am_duong_ct')->where('legacy_id', $id)->first();
            if (! $option) {
                $failures[] = 'display option/'.$id.': missing';

                continue;
            }
            foreach (['name', 'image', 'created_at', 'updated_at'] as $field) {
                $check('display option/'.$id, $field, $row[$field] ?? null, $option->{$field});
            }
            $check('display option/'.$id, 'sort_order', (int) ($row['sort_order'] ?? 0), $option->sort_order);
        }

        return $failures;
    };

    if ($apply) {
        if (! $backup || ! is_file($backup) || filesize($backup) === 0) {
            throw new RuntimeException('--apply requires --backup=/absolute/path/to/verified-full-database-dump');
        }
        if (DB::table('products')->exists()) {
            if ($verify() !== []) {
                throw new RuntimeException('Existing canonical data differs from legacy source; refusing to overwrite it.');
            }
        } else {
            $temporary = tempnam(sys_get_temp_dir(), 'legacy-catalog-');
            $zip = new ZipArchive;
            if ($zip->open($temporary, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new RuntimeException('Cannot create migration archive');
            }
            try {
                foreach ($source as $table => $rows) {
                    $zip->addFromString('data/'.$table.'.ndjson', implode('', array_map(fn ($row) => json_encode($row, JSON_THROW_ON_ERROR)."\n", $rows)));
                }
                $zip->close();
                $zip->open($temporary);
                DB::transaction(function () use ($source, $zip, $verify): void {
                    $allocator = app(PublicIdAllocator::class);
                    foreach (ProductTypeRegistry::all() as $type => $configuration) {
                        if ($configuration['variant_table']) {
                            $table = $configuration['variant_table'];
                            $maximum = max(array_merge([0], array_column($source[$table], $table.'_id')));
                            $allocator->reserveVariantFloor($type, $maximum + 1);
                        }
                    }
                    $adapter = app(LegacyV1ArchiveAdapter::class);
                    $result = ['added' => 0, 'updated' => 0, 'skipped' => 0];
                    $manifest = ['source_id' => '969ec41a-dad3-4dac-8354-472584a803a8'];
                    $productTables = array_keys(ProductTypeRegistry::all());
                    $tables = array_merge($productTables, array_values(array_diff(array_keys($source), $productTables)));
                    foreach ($tables as $table) {
                        $adapter->importTable($zip, $table, $manifest, [], $result, fn ($row) => $row);
                    }
                    $allocator->reconcileSequences();
                    $failures = $verify();
                    if ($result['skipped'] !== 0 || $failures !== []) {
                        throw new RuntimeException('Migration reconciliation failed: '.json_encode(array_slice($failures, 0, 20)));
                    }
                });
            } finally {
                $zip->close();
                unlink($temporary);
            }
        }
    }

    $failures = $verify();
    foreach ($keys as $table => $key) {
        $source[$table] = DB::table($table)->orderBy($key)->get()->map(fn ($row) => (array) $row)->all();
    }
    if (hash('sha256', json_encode($source, JSON_THROW_ON_ERROR)) !== $sourceHash) {
        throw new RuntimeException('Legacy source changed during conversion/reconciliation');
    }
    echo json_encode(['verified' => $failures === [], 'source_sha256' => $sourceHash, 'products' => DB::table('products')->count(), 'variants' => DB::table('product_variants')->count(), 'media' => DB::table('product_media')->count(), 'display_options' => DB::table('product_display_options')->count(), 'mismatches' => array_slice($failures, 0, 30)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).PHP_EOL;
    exit($failures === [] ? 0 : 1);
} catch (Throwable $error) {
    fwrite(STDERR, $error->getMessage().PHP_EOL);
    exit(1);
}
