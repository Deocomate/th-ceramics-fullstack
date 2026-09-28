<?php

namespace App\Services;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\ProductTypeRegistry;
use App\Domains\Content\Models\TinTuc;
use App\Support\AssetPath;
use App\Support\ProductGallery;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class ViewHistoryService
{
    private const RECENT_NEWS_KEY = 'th_recent_news';

    private const RECENT_PRODUCTS_KEY = 'th_recent_products';

    private const MAX_ITEMS = 12;

    public function trackArticle(int $articleId): void
    {
        $history = array_values(array_filter(
            session(self::RECENT_NEWS_KEY, []),
            fn ($id) => (int) $id !== $articleId
        ));

        array_unshift($history, $articleId);

        session()->put(self::RECENT_NEWS_KEY, array_slice($history, 0, self::MAX_ITEMS));
    }

    public function recentArticles(int $limit = 3): Collection
    {
        $ids = array_slice(session(self::RECENT_NEWS_KEY, []), 0, self::MAX_ITEMS);

        if (empty($ids)) {
            return collect();
        }

        $position = array_flip(array_map('intval', $ids));

        return TinTuc::query()
            ->with('danhMuc')
            ->whereIn('tin_tuc_id', $ids)
            ->whereIn('trang_thai', ['published', 'active'])
            ->whereHas('danhMuc', fn ($query) => $query->where('is_delete', false))
            ->get()
            ->sortBy(fn (TinTuc $article) => $position[$article->tin_tuc_id] ?? PHP_INT_MAX)
            ->take($limit)
            ->values();
    }

    public function trackProduct(string $type, int $id, array $snapshot = []): void
    {
        $history = array_values(array_filter(
            session(self::RECENT_PRODUCTS_KEY, []),
            fn ($item) => ($item['type'] ?? null) !== $type || (int) ($item['id'] ?? 0) !== $id
        ));

        array_unshift($history, array_merge($snapshot, [
            'type' => $type,
            'id' => $id,
        ]));

        session()->put(self::RECENT_PRODUCTS_KEY, array_slice($history, 0, self::MAX_ITEMS));
    }

    public function recentProducts(int $limit = 4): Collection
    {
        $history = array_slice(session(self::RECENT_PRODUCTS_KEY, []), 0, $limit);

        if (empty($history)) {
            return collect();
        }

        return collect($history)
            ->map(fn (array $item) => $this->resolveProductItem($item))
            ->filter()
            ->values();
    }

    public function defaultArticles(int $limit = 3): Collection
    {
        return TinTuc::query()
            ->with('danhMuc')
            ->whereIn('trang_thai', ['published', 'active'])
            ->whereHas('danhMuc', fn ($query) => $query->where('is_delete', false))
            ->latest('ngay_dang')
            ->take($limit)
            ->get();
    }

    public function defaultProducts(int $limit = 4): Collection
    {
        $sourceTypes = ['ngoi_am_duong_ct', 'gach_hoa_thong_gio_ct'];
        $perType = (int) ceil($limit / count($sourceTypes));
        $items = collect();

        foreach ($sourceTypes as $type) {
            $products = Product::query()
                ->with(['variants' => fn ($q) => $q->where('is_delete', false), 'media', 'publicId'])
                ->where('type_key', $type)
                ->where('is_delete', false)
                ->orderByDesc('priority')
                ->orderByDesc('id')
                ->limit($perType)
                ->get();

            foreach ($products as $product) {
                $routeName = ProductTypeRegistry::detailRoute($type, $product->category_type);
                $firstImage = ProductGallery::firstImagePath($product->images);
                $items->push($this->makeProductDto(
                    type: $type,
                    id: (int) $product->public_id,
                    name: (string) $product->name,
                    price: (float) ($product->price ?? 0),
                    image: $firstImage,
                    routeName: $routeName,
                ));
            }
        }

        return $items->take($limit)->values();
    }

    private function resolveProductItem(array $item): ?object
    {
        $type = (string) ($item['type'] ?? '');
        $id = (int) ($item['id'] ?? 0);

        if ($type === '' || $id <= 0) {
            return null;
        }

        $config = ProductTypeRegistry::get($type);
        if (! $config) {
            return null;
        }

        $product = Product::query()
            ->with(['variants' => fn ($q) => $q->where('is_delete', false), 'media', 'publicId'])
            ->where('type_key', $type)
            ->where('is_delete', false)
            ->whereHas('publicId', fn ($p) => $p->where('type_key', $type)->where('public_id', $id))
            ->first();

        if (! $product) {
            return null;
        }

        $categoryType = $product->category_type;
        $routeName = ProductTypeRegistry::detailRoute($type, $categoryType);
        $firstImage = ProductGallery::firstImagePath($product->images);

        return $this->makeProductDto(
            type: $type,
            id: (int) $product->public_id,
            name: (string) $product->name,
            price: (float) ($product->price ?? 0),
            image: $firstImage,
            routeName: $routeName,
        );
    }

    private function makeProductDto(
        string $type,
        int $id,
        string $name,
        float $price,
        ?string $image,
        string $routeName,
    ): object {
        $url = Route::has($routeName) ? route($routeName, $id) : '#';

        return (object) [
            'type' => $type,
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'price_formatted' => $price > 0 ? number_format($price, 0, ',', '.') . ' đ' : 'Liên hệ',
            'image' => $image,
            'image_url' => $image ? AssetPath::url($image) : asset('assets/images/logo.png'),
            'url' => $url,
            'route_name' => $routeName,
        ];
    }
}
