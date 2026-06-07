<?php

namespace App\View\Components\Client\Shared;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\View\Component;

class ProductDetailContainer extends Component
{
    public Collection $featureItems;

    public string $titleText;

    public string $contactHotline;

    public string $zaloLink;

    public bool $showButton;

    public function __construct(
        public string $title = 'Chi tiết sản phẩm',
        public ?string $price = null,
        public mixed $rawPrice = null,
        public ?string $sku = null,
        mixed $features = null,
        public mixed $colors = null,
        public mixed $variants = null,
        public mixed $images = [],
        public ?string $productType = null,
        public mixed $productId = null,
    ) {
        $this->featureItems = collect($features)->filter(fn ($feature) => filled($feature))->values();
        $this->titleText = html_entity_decode(preg_replace('/<br\s*\/?>/i', "\n", strip_tags($this->title)), ENT_QUOTES, 'UTF-8');

        $globalContact = ViewFactory::shared('globalContact');
        $this->contactHotline = data_get($globalContact, 'hotline', '0966 55 8808');
        $this->zaloLink = data_get($globalContact, 'zalo_link', 'https://zalo.me/0966558808');
        $this->showButton = $this->canAddToCart() || $this->hasVariantPricing();
    }

    public function render(): View
    {
        return view('components.client.shared.product-detail-container');
    }

    private function canAddToCart(): bool
    {
        return (float) $this->rawPrice > 0;
    }

    private function hasVariantPricing(): bool
    {
        return collect($this->colors)
            ->merge(collect($this->variants))
            ->contains(fn ($variant) => filled(data_get($variant, 'variantId')) || filled(data_get($variant, 'price')));
    }
}
