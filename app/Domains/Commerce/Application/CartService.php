<?php

namespace App\Domains\Commerce\Application;

use App\Domains\Commerce\Application\Ports\CartSessionPort;
use App\Domains\Commerce\Application\Ports\CatalogCartProductPort;
use Exception;

class CartService
{
    public function __construct(
        private readonly CartSessionPort $session,
        private readonly CatalogCartProductPort $catalog,
        private readonly CouponService $couponService,
    ) {}

    /** @return array{name: string, variant_name: ?string, sku: ?string, price: int, image: ?string} */
    public function getProductDetails(string $productType, int $productId, ?int $variantId): array
    {
        return $this->catalog->details($productType, $productId, $variantId);
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

        $this->session->saveCart($cart);
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
            $this->session->saveCart($cart);
        }
    }

    public function update(string $rowId, int $qty): void
    {
        $this->updateQty($rowId, $qty);
    }

    public function remove(string $rowId): void
    {
        $cart = $this->getCart();
        unset($cart[$rowId]);
        $this->session->saveCart($cart);
    }

    public function getCart(): array
    {
        return $this->session->cart();
    }

    /** @return array<int, array<string, mixed>> */
    public function getCheckoutItems(): array
    {
        return array_map(static fn (array $item) => [
            'row_id' => $item['row_id'] ?? $item['rowId'] ?? '',
            'product_type' => $item['product_type'] ?? $item['productType'],
            'product_id' => $item['product_id'] ?? $item['productId'],
            'variant_id' => $item['variant_id'] ?? $item['variantId'] ?? null,
            'name' => $item['name'],
            'variant_name' => $item['variant_name'] ?? $item['variantName'] ?? null,
            'sku' => $item['sku'] ?? null,
            'price' => (int) $item['price'],
            'quantity' => (int) ($item['quantity'] ?? $item['qty'] ?? 1),
            'total' => (int) ($item['total'] ?? ($item['price'] * ($item['quantity'] ?? $item['qty'] ?? 1))),
            'image' => $item['image'] ?? null,
        ], $this->getCart());
    }

    public function getCount(): int
    {
        return array_sum(array_map(static fn (array $item) => (int) ($item['quantity'] ?? $item['qty'] ?? 0), $this->getCart()));
    }

    public function getSubtotal(): int
    {
        return array_sum(array_map(static fn (array $item) => (int) ($item['total'] ?? (($item['price'] ?? 0) * ($item['quantity'] ?? $item['qty'] ?? 1))), $this->getCart()));
    }

    public function getTotal(): int
    {
        return max(0, $this->getSubtotal() - $this->getDiscount());
    }

    public function applyCoupon(string $code): array
    {
        if ($this->getCart() === []) {
            return ['valid' => false, 'message' => 'Giỏ hàng trống.'];
        }

        $result = $this->couponService->validateAndCalculate($code, $this->getCheckoutItems());
        if ($result['valid']) {
            $this->session->setCouponCode($result['coupon']->code);
        }

        return $result;
    }

    public function setCoupon(string $code): void
    {
        $this->session->setCouponCode($code);
    }

    public function removeCoupon(): void
    {
        $this->session->clearCouponCode();
    }

    public function getCouponCode(): ?string
    {
        return $this->session->couponCode();
    }

    public function getDiscount(): int
    {
        $code = $this->getCouponCode();
        if (! $code) {
            return 0;
        }

        $result = $this->couponService->validateAndCalculate($code, $this->getCheckoutItems());
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
        $this->session->clearCart();
    }

    private function makeRowId(string $type, int $productId, ?int $variantId): string
    {
        return md5("{$type}_{$productId}_{$variantId}");
    }
}
