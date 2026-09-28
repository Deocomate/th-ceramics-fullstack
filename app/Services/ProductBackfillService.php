<?php

namespace App\Services;

use App\Models\Product;
use App\Products\ProductTypeRegistry;
use App\Support\ProductGallery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class ProductBackfillService
{
    /** @return array<string, int> */
    public function backfill(): array
    {
        $this->assertNoDuplicateSku();
        $counts = [];
        foreach (ProductTypeRegistry::all() as $type => $config) {
            if (! Schema::hasTable($type)) {
                continue;
            }
            $count = 0;
            DB::table($type)->orderBy($config['pk'])->chunkById(100, function ($rows) use ($type, $config, &$count): void {
                foreach ($rows as $row) {
                    $this->sync($type, (int) $row->{$config['pk']});
                    $count++;
                }
            }, $config['pk']);
            $counts[$type] = $count;
        }
        $this->syncSharedColors();

        return $counts;
    }

    public function sync(string $type, int $legacyId): ?Product
    {
        $config = ProductTypeRegistry::get($type);
        if ($config === null || ! Schema::hasTable('products')) {
            return null;
        }
        $row = DB::table($type)->where($config['pk'], $legacyId)->first();
        if (! $row) {
            $productId = DB::table('product_legacy_ids')->where('source_table', $type)->where('source_id', $legacyId)->value('product_id');
            if ($productId) {
                DB::table('products')->where('id', $productId)->update(['is_delete' => true]);
            }

            return null;
        }

        return DB::transaction(function () use ($row, $type, $legacyId, $config): Product {
            $productId = DB::table('product_legacy_ids')->where('source_table', $type)->where('source_id', $legacyId)->value('product_id');
            $data = [
                'type_key' => $type,
                'category_type' => $row->category_type ?? null,
                'legacy_type' => $row->legacy_type ?? null,
                'legacy_id' => $row->legacy_id ?? null,
                'name' => $row->name,
                'color' => $row->color ?? null,
                'des' => $row->des ?? null,
                'size' => $row->size ?? null,
                'size_image' => $row->size_image ?? null,
                'size_des' => $row->size_des ?? null,
                'video' => $row->video ?? null,
                'dinh_muc' => $row->dinh_muc ?? null,
                'weight' => $row->weight ?? null,
                'priority' => $row->priority ?? 0,
                'is_delete' => $row->is_delete ?? false,
                'created_at' => $row->created_at ?? now(),
                'updated_at' => $row->updated_at ?? now(),
            ];
            if ($productId) {
                DB::table('products')->where('id', $productId)->update($data);
            } else {
                $productId = DB::table('products')->insertGetId($data);
                DB::table('product_legacy_ids')->insert([
                    'source_table' => $type, 'source_id' => $legacyId, 'product_id' => $productId,
                ]);
            }

            $this->syncVariants($productId, $row, $config);
            $this->syncMedia($productId, $row->images ?? null);

            return Product::query()->with(['variants', 'media'])->findOrFail($productId);
        });
    }

    /** @return array<string, mixed> */
    public function verify(): array
    {
        $report = ['legacy' => [], 'unified' => [], 'missing' => [], 'mismatched' => [], 'examples' => [], 'duplicate_skus' => $this->duplicateSkus()];
        foreach (ProductTypeRegistry::all() as $type => $config) {
            $report['legacy'][$type] = DB::table($type)->count();
            $report['unified'][$type] = DB::table('products')->where('type_key', $type)->count();
            $report['missing'][$type] = DB::table($type)
                ->leftJoin('product_legacy_ids', function ($join) use ($type, $config) {
                    $join->on($type.'.'.$config['pk'], '=', 'product_legacy_ids.source_id')
                        ->where('product_legacy_ids.source_table', '=', $type);
                })
                ->whereNull('product_legacy_ids.product_id')->count();
            $report['mismatched'][$type] = 0;
            $report['examples'][$type] = [];
            DB::table($type)->orderBy($config['pk'])->chunkById(100, function ($rows) use ($type, $config, &$report): void {
                foreach ($rows as $row) {
                    $legacyId = (int) $row->{$config['pk']};
                    $productId = DB::table('product_legacy_ids')->where('source_table', $type)
                        ->where('source_id', $legacyId)->value('product_id');
                    if (! $productId) {
                        continue;
                    }
                    $product = DB::table('products')->where('id', $productId)->first();
                    $issues = $this->compareProduct($row, $product, $config);
                    if ($issues !== []) {
                        $report['mismatched'][$type]++;
                        if (count($report['examples'][$type]) < 20) {
                            $report['examples'][$type][] = ['legacy_id' => $legacyId, 'issues' => $issues];
                        }
                    }
                }
            }, $config['pk']);
        }

        return $report;
    }

    /** @return list<string> */
    private function compareProduct(object $row, ?object $product, array $config): array
    {
        if (! $product) {
            return ['missing_product'];
        }
        $issues = [];
        foreach (['name', 'color', 'category_type', 'legacy_type', 'legacy_id', 'des', 'size', 'size_image', 'size_des', 'video', 'dinh_muc', 'weight', 'priority', 'is_delete'] as $field) {
            if ((string) ($row->{$field} ?? '') !== (string) ($product->{$field} ?? '')) {
                $issues[] = $field;
            }
        }
        $expectedMedia = collect(ProductGallery::normalize($row->images ?? null))
            ->map(fn ($item) => [$item['type'] === ProductGallery::TYPE_VIDEO ? 'video' : 'image', $item['path'] ?? $item['url'] ?? null])
            ->filter(fn ($item) => is_string($item[1]) && $item[1] !== '')->values()->all();
        $actualMedia = DB::table('product_media')->where('product_id', $product->id)
            ->orderBy('sort_order')->get(['kind', 'path'])->map(fn ($item) => [$item->kind, $item->path])->all();
        if ($expectedMedia !== $actualMedia) {
            $issues[] = 'media';
        }
        $table = $config['variant_table'];
        $expectedVariants = $table === null ? collect() : DB::table($table)
            ->where($config['variant_fk'], $row->{$config['pk']})->get();
        $expectedCount = $expectedVariants->count() + (($table === null || ! $config['requires_variant']) ? 1 : 0);
        $actual = DB::table('product_variants')->where('product_id', $product->id)->get();
        if ($expectedCount !== $actual->count()) {
            $issues[] = 'variant_count';
        }
        foreach ($expectedVariants as $variant) {
            $id = DB::table('variant_legacy_ids')->where('source_table', $table)
                ->where('source_id', $variant->{$config['variant_pk']})->value('product_variant_id');
            $mapped = $actual->firstWhere('id', $id);
            if (! $mapped || $mapped->name !== $variant->name
                || (string) $mapped->sku !== (string) ($variant->code ?? null)
                || (string) $mapped->price !== (string) ($variant->price ?? null)
                || (bool) $mapped->is_delete !== (bool) ($variant->is_delete ?? false)) {
                $issues[] = 'variants';
                break;
            }
        }
        if ($table === null || ! $config['requires_variant']) {
            $default = $actual->firstWhere('is_default', 1);
            if (! $default || (string) $default->sku !== (string) ($row->code ?? null)
                || (string) $default->price !== (string) ($row->price ?? null)) {
                $issues[] = 'default_variant';
            }
        }

        return $issues;
    }

    private function syncVariants(int $productId, object $row, array $config): void
    {
        $table = $config['variant_table'];
        $seen = [];
        if ($table !== null) {
            $variants = DB::table($table)->where($config['variant_fk'], $row->{$config['pk']})->orderBy($config['variant_pk'])->get();
            foreach ($variants as $variant) {
                $sourceId = (int) $variant->{$config['variant_pk']};
                $id = DB::table('variant_legacy_ids')->where('source_table', $table)->where('source_id', $sourceId)->value('product_variant_id');
                $data = [
                    'product_id' => $productId,
                    'name' => $variant->name,
                    'sku' => $variant->code ?? null,
                    'price' => $variant->price ?? null,
                    'image' => $variant->image ?? null,
                    'is_default' => false,
                    'is_delete' => $variant->is_delete ?? false,
                    'created_at' => $variant->created_at ?? now(),
                    'updated_at' => $variant->updated_at ?? now(),
                ];
                if ($id) {
                    DB::table('product_variants')->where('id', $id)->update($data);
                } else {
                    $id = DB::table('product_variants')->insertGetId($data);
                    DB::table('variant_legacy_ids')->insert(['source_table' => $table, 'source_id' => $sourceId, 'product_variant_id' => $id]);
                }
                $seen[] = $id;
            }
        }

        // Direct-price products have one purchasable default. Van Mieu also
        // keeps its historical no-colour fallback price.
        if ($table === null || $config['requires_variant'] === false) {
            $default = DB::table('product_variants')->where('product_id', $productId)->where('is_default', true)->first();
            $data = [
                'product_id' => $productId,
                'name' => null,
                'sku' => $row->code ?? null,
                'price' => $row->price ?? null,
                'image' => null,
                'is_default' => true,
                'is_delete' => $row->is_delete ?? false,
                'created_at' => $row->created_at ?? now(),
                'updated_at' => $row->updated_at ?? now(),
            ];
            if ($default) {
                DB::table('product_variants')->where('id', $default->id)->update($data);
                $seen[] = $default->id;
            } else {
                $seen[] = DB::table('product_variants')->insertGetId($data);
            }
        }

        DB::table('product_variants')->where('product_id', $productId)->whereNotIn('id', $seen)->delete();
    }

    private function syncMedia(int $productId, mixed $gallery): void
    {
        DB::table('product_media')->where('product_id', $productId)->delete();
        $coverSet = false;
        foreach (ProductGallery::normalize($gallery) as $position => $item) {
            $kind = $item['type'] === ProductGallery::TYPE_VIDEO ? 'video' : 'image';
            $path = $item['path'] ?? $item['url'] ?? null;
            if (! is_string($path) || $path === '') {
                continue;
            }
            $cover = $kind === 'image' && ! $coverSet;
            $coverSet = $coverSet || $cover;
            DB::table('product_media')->insert([
                'product_id' => $productId,
                'kind' => $kind,
                'path' => $path,
                'sort_order' => $position,
                'is_cover' => $cover,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function syncSharedColors(): void
    {
        if (! Schema::hasTable('mau_sac_ngoi_am_duong_ct')) {
            return;
        }
        DB::table('product_display_options')->where('type_key', 'ngoi_am_duong_ct')->delete();
        foreach (DB::table('mau_sac_ngoi_am_duong_ct')->orderBy('mau_sac_ngoi_am_duong_ct_id')->get() as $index => $row) {
            DB::table('product_display_options')->insert([
                'type_key' => 'ngoi_am_duong_ct',
                'legacy_id' => $row->mau_sac_ngoi_am_duong_ct_id,
                'product_id' => null,
                'name' => $row->name,
                'image' => $row->image,
                'sort_order' => $index,
                'created_at' => $row->created_at ?? now(),
                'updated_at' => $row->updated_at ?? now(),
            ]);
        }
    }

    private function assertNoDuplicateSku(): void
    {
        $duplicates = $this->duplicateSkus();
        if ($duplicates !== []) {
            throw new RuntimeException('Mã hàng trùng: '.json_encode(array_slice($duplicates, 0, 20), JSON_UNESCAPED_UNICODE));
        }
    }

    /** @return list<array{sku: string, first: string, second: string}> */
    private function duplicateSkus(): array
    {
        $seen = [];
        $duplicates = [];
        foreach (ProductTypeRegistry::all() as $type => $config) {
            $sources = [$type];
            if ($config['variant_table']) {
                $sources[] = $config['variant_table'];
            }
            foreach ($sources as $table) {
                if (! Schema::hasColumn($table, 'code')) {
                    continue;
                }
                foreach (DB::table($table)->whereNotNull('code')->pluck('code') as $sku) {
                    $normalized = mb_strtolower(trim((string) $sku));
                    if ($normalized === '') {
                        continue;
                    }
                    if (isset($seen[$normalized])) {
                        $duplicates[] = ['sku' => (string) $sku, 'first' => $seen[$normalized], 'second' => $table];
                        continue;
                    }
                    $seen[$normalized] = $table;
                }
            }
        }

        return $duplicates;
    }
}
