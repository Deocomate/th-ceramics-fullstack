<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Application\Ports\CatalogQueryPort;
use App\Domains\Catalog\Domain\ProductTypeRegistry;
use App\Domains\Catalog\Infrastructure\Models\Product;
use App\Domains\Catalog\Infrastructure\Models\ProductDisplayOption;
use App\Domains\Catalog\Infrastructure\Models\ProductVariant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class CatalogQueryService implements CatalogQueryPort
{
    /** @return Collection<int, Product> */
    public function all(string $type, string $status = 'active', ?string $categoryType = null): Collection
    {
        $query = Product::query()
            ->with(['variants.publicId', 'media', 'publicId'])
            ->where('type_key', $type);

        if ($status === 'active') {
            $query->where('is_delete', false);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', true);
        }

        if ($categoryType !== null && $categoryType !== 'all') {
            $query->where('category_type', $categoryType);
        }

        if (ProductTypeRegistry::get($type)['list_by_category'] ?? false) {
            $query->orderBy('category_type');
        }

        return $query->orderByDesc('priority')->orderByDesc('id')->get();
    }

    public function find(string $type, int $publicId): Product
    {
        return Product::query()
            ->with(['variants.publicId', 'media', 'publicId', 'displayOptions'])
            ->where('type_key', $type)
            ->whereHas('publicId', fn ($p) => $p->where('type_key', $type)->where('public_id', $publicId))
            ->firstOrFail();
    }

    public function findActive(string $type, int $publicId): Product
    {
        $product = $this->find($type, $publicId);
        if ($product->is_delete) {
            throw (new ModelNotFoundException)->setModel(Product::class, [$publicId]);
        }

        return $product;
    }

    public function paginate(
        string $type,
        array $filters,
        int $perPage = 8,
        ?string $categoryType = null,
        string $pageName = 'page'
    ): LengthAwarePaginator {
        $query = Product::query()
            ->with(['variants.publicId', 'media', 'publicId'])
            ->withMin(['variants as min_active_price' => fn ($q) => $q->where('is_delete', false)], 'price')
            ->where('type_key', $type)
            ->where('is_delete', false);

        if ($categoryType !== null && $categoryType !== 'all') {
            $query->where('category_type', $categoryType);
        }

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $like = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $search).'%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('size', 'like', $like)
                    ->orWhereHas('variants', fn ($v) => $v->where('sku', 'like', $like));
            });
        }

        switch ($filters['sort'] ?? '') {
            case 'price_asc':
                $query->orderByRaw('min_active_price IS NULL')->orderBy('min_active_price');
                break;
            case 'price_desc':
                $query->orderByDesc('min_active_price');
                break;
            case 'name_asc':
                $query->orderBy('name');
                break;
            default:
                $query->orderByDesc('priority');
        }

        return $query->orderByDesc('id')->paginate($perPage, ['*'], $pageName)->withQueryString();
    }

    /** @return Collection<int, Product> */
    public function filtered(string $type, array $filters, ?string $categoryType = null): Collection
    {
        $query = Product::query()
            ->with(['variants.publicId', 'media', 'publicId'])
            ->withMin(['variants as min_active_price' => fn ($q) => $q->where('is_delete', false)], 'price')
            ->where('type_key', $type)
            ->where('is_delete', false);

        if ($categoryType !== null && $categoryType !== 'all') {
            $query->where('category_type', $categoryType);
        }

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $like = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $search).'%';
            $query->where(fn ($q) => $q->where('name', 'like', $like)
                ->orWhere('size', 'like', $like)
                ->orWhereHas('variants', fn ($v) => $v->where('sku', 'like', $like)));
        }

        switch ($filters['sort'] ?? '') {
            case 'price_asc':
                $query->orderByRaw('min_active_price IS NULL')->orderBy('min_active_price');
                break;
            case 'price_desc':
                $query->orderByDesc('min_active_price');
                break;
            case 'name_asc':
                $query->orderBy('name');
                break;
            default:
                $query->orderBy('category_type')->orderByDesc('priority');
        }

        return $query->orderByDesc('id')->get();
    }

    /** @return Collection<int, Product> */
    public function related(string $type, int $publicId, ?string $categoryType, int $limit = 4): Collection
    {
        return Product::query()
            ->with(['variants.publicId', 'media', 'publicId'])
            ->where('type_key', $type)
            ->where('is_delete', false)
            ->when($categoryType !== null, fn ($q) => $q->where('category_type', $categoryType))
            ->whereDoesntHave('publicId', fn ($q) => $q->where('type_key', $type)->where('public_id', $publicId))
            ->orderByDesc('priority')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    /** @return Collection<int, Product> */
    public function forHome(string $type, int $limit = 8): Collection
    {
        return Product::query()
            ->with(['variants.publicId', 'media', 'publicId'])
            ->where('type_key', $type)
            ->where('is_delete', false)
            ->orderByDesc('priority')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    /** @return \Illuminate\Support\Collection<int, array<string, mixed>> */
    public function search(string $keyword, int $limit = 8): \Illuminate\Support\Collection
    {
        $like = '%'.str_replace(['%', '_'], ['\\%', '\\_'], trim($keyword)).'%';

        $variants = ProductVariant::query()
            ->with(['product.media', 'product.publicId'])
            ->where('is_delete', false)
            ->whereHas('product', fn ($q) => $q->where('is_delete', false))
            ->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('sku', 'like', $like)
                    ->orWhereHas('product', fn ($p) => $p->where('name', 'like', $like));
            })
            ->orderByDesc('id')
            ->limit($limit)
            ->get();

        return $variants;
    }

    /** @return array{name: string, variant_name: ?string, sku: ?string, price: int, image: ?string} */
    public function cartDetails(string $type, int $productId, ?int $variantId): array
    {
        $product = $this->findActive($type, $productId);
        $config = ProductTypeRegistry::get($type);
        $default = $product->variants->firstWhere('is_default', true) ?? $product->variants->first();
        $variant = null;
        $option = null;

        if ($variantId !== null && ($config['options_as_variants'] ?? false)) {
            $option = ProductDisplayOption::findByPublicId($type, $variantId);
            if (! $option) {
                throw (new ModelNotFoundException)->setModel(ProductDisplayOption::class, [$variantId]);
            }
        } elseif ($variantId !== null && ($config['has_variants'] ?? false)) {
            $variant = $product->variants->first(fn ($item) => ! $item->is_delete
                && (int) $item->public_id === $variantId);
            if (! $variant) {
                throw (new ModelNotFoundException)->setModel(ProductVariant::class, [$variantId]);
            }
        }

        $hasSelectableVariants = $product->variants->contains(fn ($item) => ! $item->is_default && ! $item->is_delete);
        if (($config['requires_variant'] ?? false) && $hasSelectableVariants && $variant === null) {
            throw new \InvalidArgumentException('Vui lòng chọn phân loại sản phẩm.');
        }

        $sellable = $variant ?: $default;
        $image = $option?->image ?: $sellable?->image ?: $product->media->firstWhere('kind', 'image')?->path;

        return [
            'name' => $product->name,
            'variant_name' => $option?->name ?? $variant?->name,
            'sku' => $sellable?->sku ?? $default?->sku,
            'price' => (int) ($sellable?->price ?? 0),
            'image' => $image,
        ];
    }

    public function cartOptions(string $type, int $productId): array
    {
        $product = $this->findActive($type, $productId);
        $config = ProductTypeRegistry::get($type);
        $cover = $product->media->firstWhere('kind', 'image')?->path;
        $default = $product->variants->firstWhere('is_default', true) ?? $product->variants->first();

        if ($config['options_as_variants'] ?? false) {
            $variants = ProductDisplayOption::query()->where('type_key', $type)->orderBy('sort_order')->get()
                ->map(fn ($item) => [
                    'id' => (int) ($item->legacy_id ?? $item->id),
                    'name' => $item->name,
                    'sku' => (string) ($default?->sku ?? ''),
                    'price' => (int) ($default?->price ?? 0),
                    'price_formatted' => number_format((int) ($default?->price ?? 0), 0, ',', '.').' đ',
                    'image' => $item->image ?: $cover,
                ])->all();
        } else {
            $variants = $product->variants->where('is_default', false)->where('is_delete', false)
                ->map(function ($item) use ($cover) {
                    $id = $item->public_id;

                    return [
                        'id' => (int) $id,
                        'name' => $item->name,
                        'sku' => (string) ($item->sku ?? ''),
                        'price' => (int) ($item->price ?? 0),
                        'price_formatted' => number_format((int) ($item->price ?? 0), 0, ',', '.').' đ',
                        'image' => $item->image ?: $cover,
                    ];
                })->sortBy('price')->values()->all();
        }

        $first = $variants[0] ?? null;
        $price = (int) ($first['price'] ?? $default?->price ?? 0);
        $requires = ($config['requires_variant'] ?? false) && $variants !== [];

        return [
            'product_type' => $type,
            'product_id' => $productId,
            'name' => $product->name,
            'image' => $first['image'] ?? $cover,
            'requires_variant' => $requires,
            'variant_label' => $variants === [] ? null : $config['variant_label'],
            'variants' => $variants,
            'default_variant_id' => $first['id'] ?? null,
            'unit_price' => $price,
            'unit_price_formatted' => $price <= 0 ? 'Liên hệ' : number_format($price, 0, ',', '.').' đ',
            'contact_only' => $price <= 0,
        ];
    }
}
