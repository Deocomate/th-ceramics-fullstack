<?php

namespace App\Domains\Catalog\Http\Presenters;

use App\Domains\Catalog\Domain\PhuKienNgoiCategory;
use App\Domains\Catalog\Domain\ProductTypeRegistry;
use App\Domains\Catalog\Infrastructure\Models\ProductVariant;
use App\Support\AssetPath;
use Illuminate\Support\Collection;

final class CatalogHttpPresenter
{
    /**
     * @param  Collection<int, ProductVariant>|array<int, mixed>  $variants
     * @return Collection<int, array<string, mixed>>
     */
    public static function presentSearchResults(Collection|array $variants): Collection
    {
        $collection = $variants instanceof Collection ? $variants : collect($variants);

        return $collection->map(function ($item) {
            if ($item instanceof ProductVariant) {
                $product = $item->product;
                $config = ProductTypeRegistry::get($product->type_key);
                $id = (int) $product->public_id;
                $image = $item->image ?: ($product->media->firstWhere('kind', 'image')?->path);
                $price = (int) ($item->price ?? 0);
                $category = $config['label'] ?? '';
                if ($product->type_key === 'phu_kien_ngoi_ct') {
                    $category = PhuKienNgoiCategory::label($product->category_type ?? '');
                }

                $name = $item->is_default || ! $item->name
                    ? $product->name
                    : trim($product->name.' - '.$item->name);

                return [
                    'id' => $id,
                    'name' => $name,
                    'code' => $item->sku ?? '',
                    'category' => $category,
                    'image' => AssetPath::url($image, 'assets/images/logo.png'),
                    'url' => route(ProductTypeRegistry::detailRoute($product->type_key, $product->category_type), $id),
                    'price' => $price,
                    'price_formatted' => number_format($price, 0, ',', '.').'đ',
                ];
            }

            if (is_array($item)) {
                return $item;
            }

            return (array) $item;
        });
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public static function presentCartOptions(array $options): array
    {
        foreach ($options['variants'] as &$variant) {
            $variant['image_url'] = AssetPath::url($variant['image'] ?? null);
            unset($variant['image']);
        }
        unset($variant);

        $options['image_url'] = AssetPath::url($options['image'] ?? null);
        unset($options['image']);

        return $options;
    }
}
