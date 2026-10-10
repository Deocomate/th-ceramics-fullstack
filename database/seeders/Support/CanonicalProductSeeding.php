<?php

namespace Database\Seeders\Support;

use App\Domains\Catalog\Infrastructure\Models\Product;
use App\Domains\Catalog\Infrastructure\Models\ProductDisplayOption;
use App\Domains\Catalog\Infrastructure\Models\ProductVariant;
use App\Domains\Catalog\Infrastructure\PublicIdAllocator;

trait CanonicalProductSeeding
{
    use LoadsSeederData;

    protected function seedCanonicalProductType(string $table, string $pk, bool $hasCodePrice = true): void
    {
        $rows = $this->seederData($table);
        foreach ($rows as $row) {
            $legacyId = (int) $row[$pk];

            $product = Product::updateOrCreate([
                'type_key' => $table,
                'legacy_id' => $legacyId,
            ], [
                'category_type' => $row['category_type'] ?? null,
                'legacy_type' => $table,
                'name' => $row['name'],
                'color' => $row['color'] ?? 'Tự chọn',
                'des' => $this->normalizeArrayField($row['des'] ?? null),
                'size' => $row['size'] ?? null,
                'size_image' => $row['size_image'] ?? null,
                'size_des' => $this->normalizeArrayField($row['size_des'] ?? null),
                'video' => $row['video'] ?? null,
                'dinh_muc' => $row['dinh_muc'] ?? null,
                'weight' => $row['weight'] ?? null,
                'priority' => (int) ($row['priority'] ?? 0),
                'is_delete' => (bool) ($row['is_delete'] ?? false),
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ]);

            app(PublicIdAllocator::class)->product($product, $legacyId);

            if ($hasCodePrice) {
                $code = $row['code'] ?? null;
                $price = isset($row['price']) ? (int) $row['price'] : null;
                $variant = $product->variants()->where('is_default', true)->first();
                $variantData = [
                    'sku' => $code,
                    'price' => $price,
                    'is_default' => true,
                    'is_delete' => (bool) ($row['is_delete'] ?? false),
                ];
                if ($variant) {
                    $variant->update($variantData);
                } else {
                    $variant = $product->variants()->create($variantData);
                    app(PublicIdAllocator::class)->variant($variant->setRelation('product', $product));
                }
            }

            $images = $row['images'] ?? [];
            if (is_string($images)) {
                $images = json_decode($images, true) ?? [];
            }
            if (is_array($images) && ! empty($images)) {
                $this->syncSeededMedia($product, $images);
            }
        }
    }

    protected function seedCanonicalVariants(string $table, string $pk, string $fk, string $parentTable): void
    {
        $rows = $this->seederData($table);
        foreach ($rows as $row) {
            $legacyId = (int) $row[$pk];
            $parentLegacyId = (int) $row[$fk];

            $parentProduct = Product::where('type_key', $parentTable)
                ->where('legacy_id', $parentLegacyId)
                ->first();

            if (! $parentProduct) {
                continue;
            }

            $variantData = [
                'product_id' => $parentProduct->id,
                'name' => $row['name'] ?? null,
                'sku' => $row['code'] ?? null,
                'price' => isset($row['price']) ? (int) $row['price'] : null,
                'image' => $row['image'] ?? null,
                'is_default' => false,
                'is_delete' => (bool) ($row['is_delete'] ?? false),
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ];

            $variant = ProductVariant::where('product_id', $parentProduct->id)
                ->where('sku', $variantData['sku'])
                ->first();

            if ($variant) {
                $variant->update($variantData);
            } else {
                $variant = $parentProduct->variants()->create($variantData);
                app(PublicIdAllocator::class)->variant($variant->setRelation('product', $parentProduct), $legacyId);
            }
        }
    }

    protected function seedCanonicalDisplayOptions(string $table, string $pk, string $typeKey): void
    {
        $rows = $this->seederData($table);
        foreach ($rows as $row) {
            $legacyId = (int) $row[$pk];
            ProductDisplayOption::updateOrCreate([
                'type_key' => $typeKey,
                'legacy_id' => $legacyId,
            ], [
                'name' => $row['name'],
                'image' => $row['image'],
                'sort_order' => (int) ($row['sort_order'] ?? 0),
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ]);
        }
    }

    private function syncSeededMedia(Product $product, array $images): void
    {
        $product->media()->delete();
        $hasCover = false;
        foreach ($images as $index => $img) {
            $path = is_string($img) ? $img : ($img['path'] ?? $img['url'] ?? '');
            if ($path === '') {
                continue;
            }
            $kind = (is_array($img) && ($img['type'] ?? '') === 'video') ? 'video' : 'image';
            $isCover = ! $hasCover && $kind === 'image';
            if ($isCover) {
                $hasCover = true;
            }

            $product->media()->create([
                'kind' => $kind,
                'path' => $path,
                'sort_order' => $index,
                'is_cover' => $isCover,
            ]);
        }
    }

    private function normalizeArrayField(mixed $value): ?array
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
