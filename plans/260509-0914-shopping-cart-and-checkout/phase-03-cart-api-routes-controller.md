---
phase: 3
title: "Cart API Routes & Controller"
status: completed
priority: P1
effort: "2h"
dependencies: [2]
---

# Phase 3: Cart API Routes & Controller

## Overview

Update `CartController` with add/update/remove actions returning JSON responses. Define POST routes for cart manipulation. Add CSRF-protected AJAX endpoints.

## Requirements

- Functional: JSON API for add, update quantity, remove item. Return updated cart totals for UI refresh.
- Non-functional: Validate all inputs. Return consistent JSON structure `{status, message, cart_count, cart_total}`.

## Architecture

```
POST /gio-hang/them          → CartController@add        (JSON)
POST /gio-hang/cap-nhat      → CartController@update     (JSON)
POST /gio-hang/xoa           → CartController@remove     (JSON)
GET  /gio-hang               → CartController@cart       (HTML page)
GET  /thanh-toan             → CartController@checkout   (HTML page)
POST /thanh-toan/xu-ly       → CartController@processCheckout (HTML redirect)
```

## Related Code Files

- Modify: `app/Http/Controllers/Client/CartController.php`
- Modify: `routes/client.php`
- Create: `app/Http/Requests/Cart/AddToCartRequest.php`
- Create: `app/Http/Requests/Cart/UpdateCartRequest.php`

## Implementation Steps

1. **Create Form Request classes**
   ```bash
   php artisan make:request Cart/AddToCartRequest --no-interaction
   php artisan make:request Cart/UpdateCartRequest --no-interaction
   ```

   `AddToCartRequest` rules:
   - `product_type`: required, string, in:ngoi_am_duong_ct,ngoi_hai_van_mieu_ct,gach_hoa_thong_gio_ct,gach_trang_tri_ct,gach_co_bat_trang_ct,linh_vat_phong_thuy_ct,lan_can_gom_xu,den_gom_su,phu_kien_ngoi
   - `product_id`: required, integer, min:1
   - `variant_id`: nullable, integer
   - `qty`: required, integer, min:1, max:99999

   `UpdateCartRequest` rules:
   - `row_id`: required, string
   - `qty`: required, integer, min:1, max:99999

2. **Update CartController**

   ```php
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
               'status'     => 'success',
               'message'    => 'Đã thêm vào giỏ hàng.',
               'cart_count' => $cartService->getCount(),
               'cart_total' => $cartService->getTotal(),
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'status'  => 'error',
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
           'status'     => 'success',
           'item_total' => $itemTotal,
           'cart_total' => $cartService->getTotal(),
           'cart_count' => $cartService->getCount(),
       ]);
   }

   public function remove(Request $request, CartService $cartService)
   {
       $cartService->remove($request->row_id);

       return response()->json([
           'status'     => 'success',
           'cart_total' => $cartService->getTotal(),
           'cart_count' => $cartService->getCount(),
       ]);
   }

   // cart() and checkout() already exist but need to pass $cartItems/$total
   public function cart(CartService $cartService)
   {
       return view('clients.cart.gio-hang', [
           'cartItems' => $cartService->getCart(),
           'total'     => $cartService->getTotal(),
       ]);
   }

   public function checkout(CartService $cartService)
   {
       $cartItems = $cartService->getCart();
       if (empty($cartItems)) return redirect()->route('client.cart.index');

       return view('clients.cart.thanh-toan', [
           'cartItems' => $cartItems,
           'total'     => $cartService->getTotal(),
       ]);
   }
   ```

3. **Add routes to `routes/client.php`**
   ```php
   // Cart AJAX endpoints
   Route::post('/gio-hang/them', [CartController::class, 'add'])->name('cart.add');
   Route::post('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
   Route::post('/gio-hang/xoa', [CartController::class, 'remove'])->name('cart.remove');
   Route::post('/thanh-toan/xu-ly', [CartController::class, 'processCheckout'])->name('cart.checkout.process');
   ```

4. **Run Pint**: `vendor/bin/pint --dirty`

## Success Criteria

- [x] `POST /gio-hang/them` adds item and returns JSON with updated count/total
- [x] `POST /gio-hang/cap-nhat` updates quantity and returns new item_total + cart_total
- [x] `POST /gio-hang/xoa` removes item and returns updated totals
- [x] Invalid product_type returns 422 validation error
- [x] Missing variant when required returns meaningful error message
- [x] CSRF token validation works (Laravel auto-handles via `VerifyCsrfToken` for AJAX)

## Risk Assessment

- **CSRF for AJAX**: All POST requests need `X-CSRF-TOKEN` header. Blade views already use `csrf_token()` meta tag. Frontend JS must include it (Phase 6).
- **N+1 queries**: `getProductDetails()` uses `findOrFail()` — single queries per add. Fine for cart operations (infrequent, one item at a time).
