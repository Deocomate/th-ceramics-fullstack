<?php

namespace App\View\Components\Client\Shared;

use App\Support\ClientProductType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public string $resolvedProductType;

    public ?int $resolvedProductId;

    public ?int $resolvedVariantId;

    public bool $shouldShowAddToCart;

    public string $addToCartButtonClasses;

    public bool $showAddToCartLabel;

    public bool $addToCartAlignStart;

    public function __construct(
        public string $href = '#',
        public ?string $image = null,
        public string $alt = '',
        public string $title = '',
        public string $code = '',
        public string $price = '',
        public string $aspect = 'aspect-square shadow-lg',
        public bool $blend = true,
        public bool $showOverlay = false,
        public string $titleClass = '',
        public ?string $productType = null,
        public mixed $productId = null,
        public mixed $variantId = null,
        public ?string $detailRouteName = null,
        public ?string $pkField = null,
        public ?object $product = null,
        public bool $showAddToCart = true,
        public string $addToCartVariant = 'outline',
    ) {
        $this->resolvedProductType = $this->productType
            ?: ClientProductType::fromDetailRoute($this->detailRouteName)
            ?: '';

        $this->resolvedProductId = ClientProductType::resolveProductId(
            $this->product,
            $this->resolvedProductType ?: null,
            $this->pkField,
            $this->productId,
        );

        $this->resolvedVariantId = $this->variantId !== null && $this->variantId !== ''
            ? (int) $this->variantId
            : ClientProductType::resolveFirstVariantId($this->product, $this->resolvedProductType ?: null);

        $this->shouldShowAddToCart = $this->showAddToCart
            && filled($this->resolvedProductType)
            && filled($this->resolvedProductId);

        $this->showAddToCartLabel = in_array($this->addToCartVariant, ['filled', 'labeled'], true);
        $this->addToCartAlignStart = $this->showAddToCartLabel;

        $this->addToCartButtonClasses = $this->showAddToCartLabel
            ? 'inline-flex items-center gap-2 px-3 py-1.5 md:px-4 md:py-2 rounded-full border border-secondary text-secondary text-[11px] md:text-sm font-semibold hover:bg-secondary hover:text-white transition-all'
            : 'inline-flex items-center justify-center w-8 h-8 md:w-9 md:h-9 rounded-full border border-secondary text-secondary hover:bg-secondary hover:text-white transition-all';
    }

    public function render(): View|Closure|string
    {
        return view('components.client.shared.product-card');
    }
}
