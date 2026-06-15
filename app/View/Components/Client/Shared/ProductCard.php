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

        $this->addToCartButtonClasses = $this->addToCartVariant === 'filled'
            ? 'bg-transparent text-secondary border border-secondary text-[9px] md:text-[13px] font-bold py-1 md:py-2 px-4 md:px-6 rounded-full hover:bg-secondary hover:text-white transition-all whitespace-nowrap mt-1 mr-auto block'
            : 'border border-secondary text-secondary text-[11px] md:text-[13px] font-bold py-1.5 px-4 rounded-full hover:bg-secondary hover:text-white transition-all mt-3 self-start';
    }

    public function render(): View|Closure|string
    {
        return view('components.client.shared.product-card');
    }
}
