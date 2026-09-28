<?php

namespace App\Domains\Commerce\Services;

use App\Domains\Catalog\Services\CatalogQueryService;
use Exception;

class CartService
{
    private string $sessionKey = 'th_cart';

    public function __construct(
        private readonly CouponService $couponService
    ) {}

    /** @return array{name: string, variant_name: ?string, sku: ?string, price: int, image: ?string} */
    public function getProductDetails(string $productType, int $productId, ?int $variantId): array
    {
        return app(CatalogQueryService::class)->cartDetails($productType, $productId, $variantId);
    }

    public function add(string $productType, int $productId, ?int $variantId, int $qty): void
    {
        $details = $this->getProductDetails($productType, $productId, $variantId);
        if ($details['price'] <= 0) {
            throw new Exception('Vui lòng liên hệ đặt hàng.');
        }

        $rowId = $this->makeRowId($productType, $productId, $variantId);
        $cart = $this->getCart();

        if (isset($cart[$rowId])) {
            $cart[$rowId]['qty'] += $qty;
            $cart[$rowId]['total'] = $cart[$rowId]['qty'] * $cart[$rowId]['price'];
        } else {
            $cart[$rowId] = [
                'rowId' => $rowId,
                'productType' => $productType,
                'productId' => $productId,
                'variantId' => $variantId,
                'name' => $details['name'],
                'variantName' => $details['variant_name'],
                'sku' => $details['sku'],
                'price' => $details['price'],
                'image' => $details['image'],
                'qty' => $qty,
                'total' => $qty * $details['price'],
            ];
        }

        $this->saveCart($cart);
    }

    public function updateQty(string $rowId, int $qty): void
    {
        $cart = $this->getCart();
        if (isset($cart[$rowId])) {
            if ($qty <= 0) {
                unset($cart[$rowId]);
            } else {
                $cart[$rowId]['qty'] = $qty;
                $cart[$rowId]['total'] = $qty * $cart[$rowId]['price'];
            }
            $this->saveCart($cart);
        }
    }

    public function remove(string $rowId): void
    {
        $cart = $this->getCart();
        unset($cart[$rowId]);
        $this->saveCart($cart);
    }

    public function getCart(): array
    {
        return session()->get($this->sessionKey, []);
    }

    public function getCount(): int
    {
        $cart = $this->getCart();

        return array_sum(array_column($cart, 'qty'));
    }

    public function getSubtotal(): int
    {
        $cart = $this->getCart();

        return array_sum(array_column($cart, 'total'));
    }

    public function getTotal(): int
    {
        return max(0, $this->getSubtotal() - $this->getDiscount());
    }

    public function applyCoupon(string $code): array
    {
        $cart = $this->getCart();
        if (empty($cart)) {
            return ['valid' => false, 'message' => 'Giỏ hàng trống.'];
        }

        $result = $this->couponService->validateAndCalculate($code, $cart);
        if ($result['valid']) {
            session()->put('th_coupon_code', $result['coupon']->code);
        }

        return $result;
    }

    public function removeCoupon(): void
    {
        session()->forget('th_coupon_code');
    }

    public function getCouponCode(): ?string
    {
        return session()->get('th_coupon_code');
    }

    public function getDiscount(): int
    {
        $code = $this->getCouponCode();
        if (! $code) {
            return 0;
        }

        $result = $this->couponService->validateAndCalculate($code, $this->getCart());
        if (! $result['valid']) {
            $this->removeCoupon();

            return 0;
        }

        return $result['discount'];
    }

    public function getDiscountAmount(): int
    {
        return $this->getDiscount();
    }

    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }

    private function makeRowId(string $type, int $productId, ?int $variantId): string
    {
        return md5("{$type}_{$productId}_{$variantId}");
    }

    private function saveCart(array $cart): void
    {
        session()->put($this->sessionKey, $cart);
    }
}
