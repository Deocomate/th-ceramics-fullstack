<?php

namespace App\Services;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\ProductTypeRegistry;
use App\Support\AssetPath;
use App\Support\ProductGallery;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

class ProductCopyService
{
    /**
     * @var array<string, array{type_key: string, pk: string, label: string, has_code: bool, has_price: bool}>
     */
    protected array $typeConfigs = [];

    public function __construct()
    {
        foreach (ProductTypeRegistry::all() as $type => $product) {
            $direct = ($product['variant_table'] ?? null) === null;
            $this->typeConfigs[str_replace('_', '-', $type)] = [
                'type_key' => $type,
                'pk' => $product['pk'],
                'label' => $product['label'],
                'has_code' => $direct,
                'has_price' => $direct,
            ];
        }
    }

    public function getSupportedTypes(): array
    {
        $result = [];
        foreach ($this->typeConfigs as $key => $config) {
            $result[] = [
                'key' => $key,
                'label' => $config['label'],
            ];
        }

        return $result;
    }

    public function getTypeConfig(string $type): ?array
    {
        return $this->typeConfigs[$type] ?? null;
    }

    /**
     * @return array<int, array{id: int, name: string, code: string, price: float|int, formatted_price: string, color: string, size: string, thumbnail_url: string, des_count: int, category_type: ?string}>
     */
    public function searchProducts(string $type, ?string $keyword = null, int $limit = 30): array
    {
        $config = $this->getTypeConfig($type);
        if (! $config) {
            throw new InvalidArgumentException("Loại sản phẩm không hợp lệ: {$type}");
        }

        $typeKey = $config['type_key'];
        $query = Product::query()
            ->with(['variants' => fn ($q) => $q->where('is_delete', false), 'media', 'publicId'])
            ->where('type_key', $typeKey)
            ->where('is_delete', false);

        if ($keyword !== null && trim($keyword) !== '') {
            $kw = '%' . trim($keyword) . '%';
            $query->where(function (Builder $q) use ($kw) {
                $q->where('name', 'like', $kw)
                    ->orWhere('color', 'like', $kw)
                    ->orWhere('size', 'like', $kw)
                    ->orWhereHas('variants', function (Builder $sub) use ($kw) {
                        $sub->where('is_delete', false)->where(function (Builder $s) use ($kw) {
                            $s->where('sku', 'like', $kw)->orWhere('name', 'like', $kw);
                        });
                    });
            });
        }

        $items = $query->orderByDesc('priority')->orderByDesc('id')->limit($limit)->get();

        return $items->map(function (Product $item) {
            $firstImg = ProductGallery::firstImagePath($item->images);
            $thumbnailUrl = $firstImg ? AssetPath::url($firstImg) : asset('assets/images/logo.png');
            $price = (int) ($item->price ?? 0);
            $formattedPrice = $price > 0 ? number_format($price, 0, ',', '.') . ' đ' : 'Liên hệ';

            return [
                'id' => (int) $item->public_id,
                'name' => (string) $item->name,
                'code' => (string) ($item->code ?? '—'),
                'price' => $price,
                'formatted_price' => $formattedPrice,
                'color' => (string) ($item->color ?? '—'),
                'size' => (string) ($item->size ?? '—'),
                'thumbnail_url' => $thumbnailUrl,
                'des_count' => is_array($item->des) ? count($item->des) : 0,
                'category_type' => $item->category_type,
            ];
        })->values()->all();
    }

    public function getProductDetailForCopy(string $type, int $id): ?array
    {
        $config = $this->getTypeConfig($type);
        if (! $config) {
            return null;
        }

        $typeKey = $config['type_key'];
        /** @var Product|null $product */
        $product = Product::query()
            ->with(['variants' => fn ($q) => $q->where('is_delete', false), 'media', 'publicId'])
            ->where('type_key', $typeKey)
            ->where('is_delete', false)
            ->whereHas('publicId', fn ($p) => $p->where('type_key', $typeKey)->where('public_id', $id))
            ->first();

        if (! $product) {
            return null;
        }

        $firstImage = ProductGallery::firstImagePath($product->images);

        $result = [
            'id' => (int) $product->public_id,
            'name' => $product->name,
            'color' => $product->color,
            'size' => $product->size,
            'size_image' => $product->size_image,
            'size_image_url' => $product->size_image ? AssetPath::url($product->size_image) : null,
            'video' => $product->video,
            'dinh_muc' => $product->dinh_muc,
            'weight' => $product->weight,
            'des' => is_array($product->des) ? $product->des : [],
            'size_des' => is_array($product->size_des) ? $product->size_des : [],
            'images' => $product->images,
            'first_image_url' => $firstImage ? AssetPath::url($firstImage) : null,
            'category_type' => $product->category_type,
            'code' => $product->code,
            'suggested_code' => $product->code ? ($product->code . '-COPY') : null,
            'price' => $product->price,
        ];

        $variants = $product->variants->where('is_default', false)->values();
        if ($variants->isNotEmpty()) {
            $result['variants'] = $variants->map(function ($v) {
                return [
                    'id' => (int) $v->public_id,
                    'name' => $v->name,
                    'code' => $v->sku,
                    'price' => $v->price,
                    'image' => $v->image,
                    'image_url' => $v->image ? AssetPath::url($v->image) : null,
                ];
            })->all();
        }

        return $result;
    }
}
