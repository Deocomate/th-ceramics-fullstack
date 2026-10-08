<?php

namespace App\Domains\Archive\Infrastructure\Adapters;

use App\Domains\Archive\Application\Ports\CatalogArchivePort;
use App\Domains\Catalog\Infrastructure\Models\Product;
use App\Domains\Catalog\Infrastructure\Models\ProductDisplayOption;
use App\Domains\Catalog\Infrastructure\Models\ProductVariant;
use App\Domains\Catalog\Infrastructure\PublicIdAllocator;
use Illuminate\Support\Facades\Schema;

class CatalogArchiveAdapter implements CatalogArchivePort
{
    public function __construct(private readonly PublicIdAllocator $publicIds) {}

    public function upsertLegacyProduct(
        string $type,
        int $legacyId,
        array $productData,
        bool $hasDefaultVariant,
        array $defaultVariantData,
        array $images,
    ): array {
        $product = Product::query()
            ->where('type_key', $type)
            ->where('legacy_id', $legacyId)
            ->first();
        $status = $product ? 'updated' : 'added';

        if ($product) {
            $product->forceFill($productData)->saveQuietly();
        } else {
            $product = new Product;
            $product->forceFill($productData)->saveQuietly();
            $this->publicIds->product($product, $legacyId);
        }

        if ($hasDefaultVariant) {
            $variant = $product->variants()->where('is_default', true)->first();
            if ($variant) {
                $variant->forceFill($defaultVariantData)->save();
            } else {
                $variant = new ProductVariant;
                $variant->forceFill($defaultVariantData + ['product_id' => $product->id])->save();
                $this->publicIds->variant($variant->setRelation('product', $product));
            }
        }

        if ($images !== []) {
            $this->syncProductMedia($product, $images);
        }

        return ['product_id' => (int) $product->id, 'status' => $status];
    }

    public function upsertLegacyVariant(
        string $parentType,
        int $parentLegacyId,
        int $legacyId,
        array $variantData,
    ): ?array {
        $parentProduct = Product::query()
            ->where('type_key', $parentType)
            ->where('legacy_id', $parentLegacyId)
            ->first();

        if (! $parentProduct) {
            return null;
        }

        $variant = null;
        if (! empty($variantData['id'])) {
            $variant = ProductVariant::query()->find($variantData['id']);
        }
        unset($variantData['id']);

        if (! $variant && ! empty($variantData['sku'])) {
            $variant = ProductVariant::query()
                ->where('product_id', $parentProduct->id)
                ->where('sku', $variantData['sku'])
                ->first();
        }

        $status = $variant ? 'updated' : 'added';
        $variantData['product_id'] = $parentProduct->id;
        if ($variant) {
            $variant->forceFill($variantData)->save();
        } else {
            $variant = new ProductVariant;
            $variant->forceFill($variantData)->save();
            $this->publicIds->variant($variant->setRelation('product', $parentProduct), $legacyId);
        }

        return ['variant_id' => (int) $variant->id, 'status' => $status];
    }

    public function upsertLegacyDisplayOption(int $legacyId, array $optionData): array
    {
        $option = ProductDisplayOption::query()
            ->where('type_key', 'ngoi_am_duong_ct')
            ->where('legacy_id', $legacyId)
            ->first();
        $status = $option ? 'updated' : 'added';

        if ($option) {
            $option->forceFill($optionData)->save();
        } else {
            $option = new ProductDisplayOption;
            $option->forceFill($optionData)->save();
        }

        return ['option_id' => (int) $option->id, 'status' => $status];
    }

    public function reconcileSequences(): void
    {
        if (Schema::hasTable('product_public_ids')) {
            $this->publicIds->reconcileSequences();
        }
    }

    /** @param list<mixed> $images */
    private function syncProductMedia(Product $product, array $images): void
    {
        $hasCover = false;
        $product->media()->delete();
        foreach ($images as $index => $image) {
            $path = is_string($image) ? $image : ($image['path'] ?? $image['url'] ?? '');
            if ($path === '') {
                continue;
            }

            $kind = is_array($image) && ($image['type'] ?? '') === 'video' ? 'video' : 'image';
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
}
