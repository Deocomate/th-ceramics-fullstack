---
phase: 5
title: "Checkout Page & Order Processing"
status: completed
priority: P2
effort: "3h"
dependencies: [4]
---

# Phase 5: Checkout Page & Order Processing

## Overview

Build checkout form in `thanh-toan.blade.php` with customer info fields, order summary from cart, payment method selection, and order submission to database. Auto-fill fields for logged-in users. Use `DB::transaction()` to ensure atomicity and include `shipping_fee` in order totals.

## Requirements

- Functional: Full checkout form → save Order + OrderItems → clear cart → redirect to thank-you. Auto-fill name/email/phone for authenticated users.
- Non-functional: Validate all required fields. Prevent empty cart checkout.

## Architecture

```
thanh-toan.blade.php
├── Left column (55%): Customer form
│   ├── Shipping address section
│   │   ├── Họ và tên (required)
│   │   ├── Số điện thoại (required)
│   │   ├── Email (optional)
│   │   └── Địa chỉ đầy đủ (required textarea)
│   ├── Ghi chú (optional textarea)
│   └── Payment method (radio: COD / Chuyển khoản)
│   └── Submit button: "Hoàn tất đơn hàng"
└── Right column (45%): Order summary
    ├── Cart items list (compact, image + name + variant + qty badge + total)
    ├── Subtotal / Shipping_fee / Discount / Total
    └── Coupon input (visual only for now)
```

## Related Code Files

- Modify: `resources/views/clients/cart/thanh-toan.blade.php`
- Modify: `app/Http/Controllers/Client/CartController.php` (add `processCheckout`)
- Create: `app/Http/Requests/Cart/CheckoutRequest.php`
- Reference: `app/Models/Order.php`
- Reference: `app/Models/OrderItem.php`

## Implementation Steps

1. **Create CheckoutRequest**
   ```bash
   php artisan make:request Cart/CheckoutRequest --no-interaction
   ```
   Rules:
   - `customer_name`: required, string, max:255
   - `phone`: required, string, max:20, regex:/^[0-9]{10,11}$/
   - `email`: nullable, email
   - `address`: required, string, max:500
   - `note`: nullable, string, max:1000
   - `payment_method`: required, in:cod,banking

2. **Replace dummy form in `thanh-toan.blade.php`**

   Keep existing layout structure. Replace inputs with real form and auto-fill for authenticated users.

3. **Update right column order summary**

   Replace dummy items with `@foreach($cartItems)` and include `shipping_fee` and `total` display.

4. **Implement `processCheckout` in CartController**

   ```php
   public function processCheckout(CheckoutRequest $request, CartService $cartService)
   {
       $cartItems = $cartService->getCart();
       if (empty($cartItems)) return redirect()->route('client.cart.index');

       $order = null;
       DB::transaction(function () use ($request, $cartItems, $cartService, &$order) {
           $subtotal = $cartService->getTotal();
           $shippingFee = 0; // calculate or fetch shipping rule
           $total = $subtotal + $shippingFee - 0; // discount if any

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
               'discount'       => 0,
               'total_amount'   => $total,
               'status'         => $request->payment_method === 'banking' ? 'pending_payment' : 'processing',
               'payment_method' => $request->payment_method,
           ]);

           foreach ($cartItems as $item) {
               $order->items()->create([
                   'product_type'  => $item['product_type'],
                   'product_id'    => $item['product_id'],
                   'variant_id'    => $item['variant_id'],
                   'product_name'  => $item['name'],
                   'variant_name'  => $item['variant_name'],
                   'sku'           => $item['sku'],
                   'price'         => $item['price'],
                   'quantity'      => $item['quantity'],
                   'total'         => $item['price'] * $item['quantity'],
               ]);
           }
       });

       $cartService->clear();

       // Dispatch mail job
       dispatch(new \App\Jobs\SendOrderConfirmationMail($order));

       return redirect()->route('client.home')
           ->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $order->order_code);
   }
   ```

5. **Add flash message display to layout** if not already present (check `x-layouts.client`)

6. **Add error display** in form with `@error('field')` messages

7. **Run Pint**: `vendor/bin/pint --dirty`

## Success Criteria

- [x] Checkout form renders with auto-filled data for logged-in users
- [x] Validation errors displayed per field for empty/invalid inputs
- [x] `processCheckout` creates Order + OrderItems in DB transaction and includes `shipping_fee` and `total_amount`
- [x] Cart is cleared after successful order
- [x] Redirect with success flash message including order code
- [x] Empty cart redirects to cart page (not checkout)
- [x] Order summary matches cart totals

## Risk Assessment

- **Concurrent checkout**: Two tabs submitting simultaneously. Session cart is stable per-request. DB transaction handles atomicity.
- **User model phone field**: Check if `users` table has `phone` column. If not, skip auto-fill for phone or add migration.
