<?php

namespace App\Services;

use App\Models\DenGomSu;
use App\Models\GachCoBatTrangCt;
use App\Models\GachHoaThongGioCt;
use App\Models\GachTrangTriCt;
use App\Models\LanCanGomXu;
use App\Models\LinhVatPhongThuyCt;
use App\Models\MauSacNgoiAmDuongCt;
use App\Models\MauSacNgoiHaiCoCt;
use App\Models\MauSacNgoiHaiVanMieuCt;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiHaiCoCt;
use App\Models\NgoiHaiVanMieuCt;
use App\Models\PhuKienNgoi;
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
        return match ($productType) {
            'ngoi_am_duong_ct' => $this->getNgoiAmDuongDetails($productId, $variantId),
            'ngoi_hai_van_mieu_ct' => $this->getNgoiHaiVanMieuDetails($productId, $variantId),
            'ngoi_hai_co_ct' => $this->getNgoiHaiCoDetails($productId, $variantId),
            'gach_hoa_thong_gio_ct' => $this->getSimpleCtDetails(GachHoaThongGioCt::class, $productId, 'gach_hoa_thong_gio_ct_id'),
            'gach_trang_tri_ct' => $this->getSimpleCtDetails(GachTrangTriCt::class, $productId, 'gach_trang_tri_ct_id'),
            'gach_co_bat_trang_ct' => $this->getSimpleCtDetails(GachCoBatTrangCt::class, $productId, 'gach_co_bat_trang_ct_id'),
            'linh_vat_phong_thuy_ct' => $this->getSimpleCtDetails(LinhVatPhongThuyCt::class, $productId, 'linh_vat_phong_thuy_ct_id'),
            'lan_can_gom_xu' => $this->getNoPriceDetails(LanCanGomXu::class, $productId, 'lan_can_gom_xu_id', 'thumbnail_main'),
            'den_gom_su' => $this->getNoPriceDetails(DenGomSu::class, $productId, 'den_gom_su_id', 'thumbnail_main'),
            'phu_kien_ngoi' => $this->getNoPriceDetails(PhuKienNgoi::class, $productId, 'phu_kien_ngoi_id', 'thumbnail_main'),
            default => throw new Exception('Loại sản phẩm không hợp lệ.'),
        };
    }

    public function add(string $productType, int $productId, ?int $variantId, int $qty): void
    {
        $rowId = $this->makeRowId($productType, $productId, $variantId);
        $cart = $this->getCart();

        if (isset($cart[$rowId])) {
            $cart[$rowId]['quantity'] += $qty;
            session([$this->sessionKey => $cart]);

            return;
        }

        $details = $this->getProductDetails($productType, $productId, $variantId);

        if ($details['price'] <= 0) {
            throw new Exception('Vui lòng liên hệ đặt hàng.');
        }

        $cart[$rowId] = [
            'row_id' => $rowId,
            'product_type' => $productType,
            'product_id' => $productId,
            'variant_id' => $variantId,
            'name' => $details['name'],
            'variant_name' => $details['variant_name'],
            'sku' => $details['sku'],
            'price' => $details['price'],
            'image' => $details['image'],
            'quantity' => $qty,
        ];

        session([$this->sessionKey => $cart]);
    }

    public function update(string $rowId, int $qty): void
    {
        $cart = $this->getCart();

        if (! isset($cart[$rowId])) {
            throw new Exception('Sản phẩm không tồn tại trong giỏ hàng.');
        }

        $cart[$rowId]['quantity'] = $qty;
        session([$this->sessionKey => $cart]);
    }

    public function remove(string $rowId): void
    {
        $cart = $this->getCart();
        unset($cart[$rowId]);
        session([$this->sessionKey => $cart]);
    }

    /**
     * @return array<string, array{row_id: string, product_type: string, product_id: int, variant_id: ?int, name: string, variant_name: ?string, sku: ?string, price: int, image: ?string, quantity: int}>
     */
    public function getCart(): array
    {
        return session($this->sessionKey, []);
    }

    public function getTotal(): int
    {
        $subtotal = $this->getSubtotal();
        $discount = $this->getDiscountAmount();

        return max(0, $subtotal - $discount);
    }

    public function getCount(): int
    {
        $count = 0;
        foreach ($this->getCart() as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }

    public function setCoupon(string $code): void
    {
        session(['th_cart_coupon' => $code]);
    }

    public function getCouponCode(): ?string
    {
        return session('th_cart_coupon');
    }

    public function removeCoupon(): void
    {
        session()->forget('th_cart_coupon');
    }

    public function getSubtotal(): int
    {
        return $this->couponService->getCartSubtotal($this->getCart());
    }

    public function getDiscountAmount(): int
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

    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }

    private function makeRowId(string $type, int $productId, ?int $variantId): string
    {
        return md5("{$type}_{$productId}_{$variantId}");
    }

    private function getNgoiAmDuongDetails(int $productId, ?int $variantId): array
    {
        $product = NgoiAmDuongCt::findOrFail($productId);
        $variantName = null;

        if ($variantId) {
            $variant = MauSacNgoiAmDuongCt::find($variantId);
            if (! $variant) {
                throw new Exception('Biến thể không tồn tại.');
            }
            $variantName = $variant->name;
        }

        return [
            'name' => $product->name,
            'variant_name' => $variantName,
            'sku' => $product->code,
            'price' => $product->price,
            'image' => $this->firstImage($product->images),
        ];
    }

    private function getNgoiHaiVanMieuDetails(int $productId, ?int $variantId): array
    {
        $product = NgoiHaiVanMieuCt::findOrFail($productId);

        if ($variantId) {
            $variant = MauSacNgoiHaiVanMieuCt::find($variantId);
            if (! $variant) {
                throw new Exception('Biến thể không tồn tại.');
            }

            return [
                'name' => $product->name,
                'variant_name' => $variant->name,
                'sku' => $variant->code,
                'price' => $variant->price,
                'image' => $variant->image,
            ];
        }

        return [
            'name' => $product->name,
            'variant_name' => null,
            'sku' => null,
            'price' => $product->price,
            'image' => $this->firstImage($product->images),
        ];
    }

    private function getNgoiHaiCoDetails(int $productId, ?int $variantId): array
    {
        $product = NgoiHaiCoCt::findOrFail($productId);

        if ($variantId) {
            $variant = MauSacNgoiHaiCoCt::query()
                ->where('ngoi_hai_co_ct_id', $productId)
                ->find($variantId);

            if (! $variant) {
                throw new Exception('Biến thể không tồn tại.');
            }

            return [
                'name' => $product->name,
                'variant_name' => $variant->name,
                'sku' => $variant->code,
                'price' => $variant->price,
                'image' => $variant->image,
            ];
        }

        return [
            'name' => $product->name,
            'variant_name' => null,
            'sku' => null,
            'price' => 0,
            'image' => $this->firstImage($product->images),
        ];
    }

    private function getSimpleCtDetails(string $modelClass, int $productId, string $pk): array
    {
        $product = $modelClass::findOrFail($productId);

        return [
            'name' => $product->name,
            'variant_name' => null,
            'sku' => $product->code,
            'price' => $product->price,
            'image' => $this->firstImage($product->images),
        ];
    }

    private function getNoPriceDetails(string $modelClass, int $productId, string $pk, string $imageField): array
    {
        $product = $modelClass::findOrFail($productId);

        return [
            'name' => $product->name ?? $product->title ?? '',
            'variant_name' => null,
            'sku' => null,
            'price' => 0,
            'image' => $product->$imageField ?? null,
        ];
    }

    private function firstImage(mixed $images): ?string
    {
        if (is_string($images)) {
            $decoded = json_decode($images, true);

            return is_array($decoded) ? ($decoded[0] ?? null) : $images;
        }

        if (is_array($images)) {
            return $images[0] ?? null;
        }

        return null;
    }
}
