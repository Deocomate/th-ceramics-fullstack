<?php

namespace App\Domains\Commerce\Http\Client;

use App\Domains\Commerce\Application\CartService;
use App\Domains\Commerce\Application\CheckoutService;
use App\Domains\Commerce\Application\CouponService;
use App\Domains\Commerce\Application\Ports\ProductCartOptionsPort;
use App\Domains\Commerce\Http\Requests\Cart\AddToCartRequest;
use App\Domains\Commerce\Http\Requests\Cart\CheckoutRequest;
use App\Domains\Commerce\Http\Requests\Cart\ProductCartOptionsRequest;
use App\Domains\Commerce\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Controllers\Controller;
use App\Support\AssetPath;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cart(CartService $cartService)
    {
        $cartItems = $cartService->getCheckoutItems();

        return view('clients.commerce.cart.gio-hang', [
            'cartItems' => $cartItems,
            'subtotal' => $cartService->getSubtotal(),
            'total' => $cartService->getTotal(),
            'currentCoupon' => $cartService->getCouponCode(),
            'currentDiscount' => $cartService->getDiscountAmount(),
        ]);
    }

    public function checkout(CartService $cartService)
    {
        $cartItems = $cartService->getCheckoutItems();
        if (empty($cartItems)) {
            return redirect()->route('client.cart.index');
        }

        return view('clients.commerce.cart.thanh-toan', [
            'cartItems' => $cartItems,
            'total' => $cartService->getTotal(),
            'couponCode' => $cartService->getCouponCode(),
            'discountAmount' => $cartService->getDiscountAmount(),
        ]);
    }

    public function add(AddToCartRequest $request, CartService $cartService)
    {
        try {
            $cartService->add(
                $request->product_type,
                $request->product_id,
                $request->variant_id,
                $request->qty
            );

            $cart = $cartService->getCart();
            $rowId = md5("{$request->product_type}_{$request->product_id}_{$request->variant_id}");
            $item = $cart[$rowId] ?? null;

            return response()->json([
                'status' => 'success',
                'message' => 'Đã thêm vào giỏ hàng.',
                'cart_count' => $cartService->getCount(),
                'cart_total' => $cartService->getTotal(),
                'item' => $item ? [
                    'name' => $item['name'],
                    'variant_name' => $item['variantName'] ?? $item['variant_name'] ?? null,
                    'quantity' => $item['qty'] ?? $item['quantity'] ?? 1,
                    'price_formatted' => number_format($item['price'], 0, ',', '.').' đ',
                ] : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function productOptions(ProductCartOptionsRequest $request, ProductCartOptionsPort $optionsService): JsonResponse
    {
        try {
            return response()->json([
                'status' => 'success',
                'data' => $this->presentCartOptions(
                    $optionsService->get($request->product_type, (int) $request->product_id)
                ),
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sản phẩm không tồn tại.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function mini(CartService $cartService): JsonResponse
    {
        $items = collect($cartService->getCart())
            ->take(5)
            ->map(fn (array $item) => [
                'row_id' => $item['row_id'] ?? $item['rowId'] ?? '',
                'name' => $item['name'] ?? '',
                'variant_name' => $item['variant_name'] ?? $item['variantName'] ?? null,
                'quantity' => (int) ($item['quantity'] ?? $item['qty'] ?? 1),
                'price_formatted' => number_format((int) ($item['price'] ?? 0), 0, ',', '.').' đ',
                'line_total_formatted' => number_format((int) (($item['price'] ?? 0) * ($item['quantity'] ?? $item['qty'] ?? 1)), 0, ',', '.').' đ',
                'image_url' => AssetPath::url($item['image'] ?? null),
            ])
            ->values()
            ->all();

        $subtotal = $cartService->getSubtotal();

        return response()->json([
            'status' => 'success',
            'cart_count' => $cartService->getCount(),
            'subtotal' => $subtotal,
            'subtotal_formatted' => number_format($subtotal, 0, ',', '.').' đ',
            'items' => $items,
        ]);
    }

    public function update(UpdateCartRequest $request, CartService $cartService)
    {
        $cartService->update($request->row_id, $request->qty);
        $cart = $cartService->getCart();
        $itemTotal = $cart[$request->row_id]['price'] * $request->qty;

        return response()->json([
            'status' => 'success',
            'item_total' => $itemTotal,
            'cart_total' => $cartService->getTotal(),
            'cart_count' => $cartService->getCount(),
        ]);
    }

    public function remove(Request $request, CartService $cartService)
    {
        $cartService->remove($request->row_id);

        return response()->json([
            'status' => 'success',
            'cart_total' => $cartService->getTotal(),
            'cart_count' => $cartService->getCount(),
        ]);
    }

    public function applyCoupon(Request $request, CartService $cartService, CouponService $couponService): JsonResponse
    {
        $code = strtoupper(trim($request->input('code', '')));

        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập mã giảm giá.',
            ]);
        }

        $cartItems = $cartService->getCart();
        if (empty($cartItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng trống.',
            ]);
        }

        $result = $couponService->validateAndCalculate($code, $cartService->getCheckoutItems());

        if (! $result['valid']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ]);
        }

        $cartService->setCoupon($code);

        return response()->json([
            'success' => true,
            'discount' => $result['discount'],
            'new_total' => $cartService->getTotal(),
            'message' => $result['message'],
        ]);
    }

    public function removeCoupon(CartService $cartService): JsonResponse
    {
        $cartService->removeCoupon();

        return response()->json([
            'success' => true,
            'new_total' => $cartService->getTotal(),
            'message' => 'Đã xóa mã giảm giá.',
        ]);
    }

    public function processCheckout(CheckoutRequest $request, CartService $cartService, CheckoutService $checkoutService)
    {
        $cartItems = $cartService->getCheckoutItems();
        $orderCode = $checkoutService->placeOrder(
            $request->validated(),
            $cartItems,
            $cartService->getCouponCode(),
            auth()->id(),
        );
        if ($orderCode === null) {
            return redirect()->route('client.cart.index');
        }

        $cartService->clear();
        $cartService->removeCoupon();

        return redirect()->route('client.home')
            ->with('success', 'Đặt hàng thành công! Mã đơn hàng: '.$orderCode);
    }

    /** @param array<string, mixed> $options */
    private function presentCartOptions(array $options): array
    {
        foreach ($options['variants'] as &$variant) {
            $variant['image_url'] = AssetPath::url($variant['image'] ?? null);
            unset($variant['image']);
        }
        unset($variant);

        $options['image_url'] = AssetPath::url($options['image'] ?? null);
        unset($options['image']);

        return $options;
    }
}
