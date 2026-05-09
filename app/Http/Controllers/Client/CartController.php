<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\CheckoutRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function cart(CartService $cartService)
    {
        return view('clients.cart.gio-hang', [
            'cartItems' => $cartService->getCart(),
            'total' => $cartService->getTotal(),
        ]);
    }

    public function checkout(CartService $cartService)
    {
        $cartItems = $cartService->getCart();
        if (empty($cartItems)) {
            return redirect()->route('client.cart.index');
        }

        return view('clients.cart.thanh-toan', [
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

            return response()->json([
                'status' => 'success',
                'message' => 'Đã thêm vào giỏ hàng.',
                'cart_count' => $cartService->getCount(),
                'cart_total' => $cartService->getTotal(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
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

        $result = $couponService->validateAndCalculate($code, $cartItems);

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

    public function processCheckout(CheckoutRequest $request, CartService $cartService, CouponService $couponService)
    {
        $cartItems = $cartService->getCart();
        if (empty($cartItems)) {
            return redirect()->route('client.cart.index');
        }

        // Re-validate coupon server-side
        $couponCode = $cartService->getCouponCode();
        $discount = 0;

        if ($couponCode) {
            $result = $couponService->validateAndCalculate($couponCode, $cartItems);
            if ($result['valid']) {
                $discount = $result['discount'];
            } else {
                $cartService->removeCoupon();
                $couponCode = null;
            }
        }

        $subtotal = $cartService->getSubtotal();
        $shippingFee = 0;
        $totalAmount = max(0, $subtotal - $discount + $shippingFee);
        $order = null;

        DB::transaction(function () use ($request, $cartItems, $couponCode, $subtotal, $shippingFee, $discount, $totalAmount, $couponService, &$order) {
            $order = Order::create([
                'user_id'        => auth()->id(),
                'order_code'     => Order::generateOrderCode(),
                'customer_name'  => $request->customer_name,
                'phone'          => $request->phone,
                'email'          => $request->email,
                'address'        => $request->address,
                'note'           => $request->note,
                'subtotal'       => $subtotal,
                'shipping_fee'   => $shippingFee,
                'discount'       => $discount,
                'total_amount'   => $totalAmount,
                'status'         => $request->payment_method === 'banking' ? 'pending_payment' : 'processing',
                'payment_method' => $request->payment_method,
                'coupon_code'    => $couponCode,
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_type' => $item['product_type'],
                    'product_id'   => $item['product_id'],
                    'variant_id'   => $item['variant_id'],
                    'product_name' => $item['name'],
                    'variant_name' => $item['variant_name'],
                    'sku'          => $item['sku'],
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                    'total'        => $item['price'] * $item['quantity'],
                ]);
            }

            if ($couponCode) {
                $couponService->incrementUsage($couponCode);
            }
        });

        $cartService->clear();
        $cartService->removeCoupon();

        return redirect()->route('client.home')
            ->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $order->order_code);
    }
}
