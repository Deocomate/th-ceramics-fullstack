<?php

namespace App\View\Components\Client\Shared;

use App\Support\AssetPath;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Recommendations extends Component
{
    public Collection $items;

    public function __construct(
        mixed $relatedProducts = null,
        public bool $showDecor = false,
        public string $routeName = 'client.products.ngoi-am-duong.detail',
        public string $pkField = 'id',
        public ?string $productType = null,
        public bool $compareTable = false,
    ) {
        $this->items = collect($relatedProducts)->values()->map(fn ($product) => $this->normalizeProduct($product));
    }

    public function render(): View
    {
        return view('components.client.shared.recommendations');
    }

    private function normalizeProduct(mixed $product): array
    {
        $productId = data_get($product, $this->pkField)
            ?? data_get($product, 'id')
            ?? (is_object($product) && method_exists($product, 'getKey') ? $product->getKey() : null);

        $accessoryType = data_get($product, 'category_type') ?: data_get($product, 'accessory_type');
        $targetRouteName = $this->routeName;

        if ($targetRouteName === 'client.products.phu-kien-ngoi.detail' && $accessoryType) {
            $targetRouteName = $accessoryType === 'chu_van'
                ? 'client.products.phu-kien-ngoi.bo-noc-chu-van.detail'
                : 'client.products.phu-kien-ngoi.ngoi-bo-noc.detail';
        }

        $rawImage = data_get($product, 'image') ?: collect(data_get($product, 'images', []))->first();
        $price = (float) (data_get($product, 'price') ?? data_get($product, 'min_price', 0));
        $rawColor = data_get($product, 'color') ?? data_get($product, 'color_name');
        $rawSize = data_get($product, 'size') ?? data_get($product, 'dimension');
        $resolvedType = $this->productType ?: (is_object($product) ? (string) str($product::class)->classBasename()->snake() : null);

        return [
            'id' => $productId,
            'url' => data_get($product, 'url') ?: ($targetRouteName && $productId && Route::has($targetRouteName)
                ? route($targetRouteName, $productId)
                : '#'),
            'image' => AssetPath::url($rawImage, 'assets/images/gach-co-work-2.jpg'),
            'name' => data_get($product, 'name', 'Sản phẩm'),
            'price' => $price,
            'price_display' => $price > 0 ? number_format($price, 0, ',', '.') . ' đ/m²' : 'Liên hệ',
            'color' => filled($rawColor) ? $rawColor : 'Tự chọn',
            'size' => filled($rawSize) ? $rawSize : '--',
            'type' => $resolvedType,
            'can_add_to_cart' => $price > 0 && $resolvedType && $resolvedType !== 'den_vuon_gom_su_ct',
        ];
    }
}
