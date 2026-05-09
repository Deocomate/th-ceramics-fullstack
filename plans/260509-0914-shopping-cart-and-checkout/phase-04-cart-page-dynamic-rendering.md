---
phase: 4
title: "Cart Page Dynamic Rendering"
status: completed
priority: P2
effort: "3h"
dependencies: [3]
---

# Phase 4: Cart Page Dynamic Rendering

## Overview

Replace dummy HTML in `gio-hang.blade.php` with `@foreach($cartItems)` loops. Add AJAX quantity controls that update UI totals without page reload. Handle empty cart state.

## Requirements

- Functional: Render cart items dynamically. +/- quantity buttons with AJAX. Remove item. Update subtotals live.
- Non-functional: Mobile responsive (existing design patterns in the dummy HTML). Graceful empty cart state.

## Related Code Files

- Modify: `resources/views/clients/cart/gio-hang.blade.php`

## Implementation Steps

1. **Replace hardcoded items with `@foreach`**

   Keep the exact same HTML structure and CSS classes from the dummy. Each item block:
   ```blade
   @forelse($cartItems as $item)
   <div class="relative pb-6 md:pt-8 border-b md:border-b-0 md:border-t border-gray-300 last:border-0" data-row-id="{{ $item['row_id'] }}">
       <!-- Desktop: use the current desktop layout -->
       <!-- Mobile: use the current mobile layout -->
       <!-- Image from $item['image'] via asset('storage/' . $item['image']) -->
       <!-- Name: $item['name'] -->
       <!-- SKU: $item['sku'] -->
       <!-- Variant: $item['variant_name'] if set -->
       <!-- Price: number_format($item['price']) -->
       <!-- Qty: $item['quantity'] with +/- buttons -->
       <!-- Item total: number_format($item['price'] * $item['quantity']) -->
   </div>
   @empty
   <div class="text-center py-12">
       <p class="text-lg text-primary/60">Giỏ hàng trống.</p>
       <a href="{{ route('client.products.ngoi-am-duong.index') }}" class="text-secondary underline mt-4 inline-block">Tiếp tục mua sắm</a>
   </div>
   @endforelse
   ```

2. **Update right-side summary totals**
   - Replace dummy totals with `{{ number_format($total) }}`
   - Update cart item count: `{{ count($cartItems) }}`

3. **Add AJAX quantity update script at bottom of file**

   ```javascript
   document.querySelectorAll('.qty-input').forEach(input => {
       input.addEventListener('change', function() {
           const rowId = this.dataset.id;
           const qty = parseInt(this.value);
           if (qty < 1) return;

           fetch('{{ route("client.cart.update") }}', {
               method: 'POST',
               headers: {
                   'Content-Type': 'application/json',
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
               },
               body: JSON.stringify({ row_id: rowId, qty: qty })
           })
           .then(res => res.json())
           .then(data => {
               if (data.status === 'success') {
                   // Update item total in the same row
                   this.closest('[data-row-id]').querySelector('.item-total').textContent =
                       new Intl.NumberFormat('vi-VN').format(data.item_total);
                   // Update cart total
                   document.querySelector('.cart-total-amount').textContent =
                       new Intl.NumberFormat('vi-VN').format(data.cart_total);
               }
           });
       });
   });

   // Decrease/Increase buttons
   document.querySelectorAll('.qty-decrease').forEach(btn => {
       btn.addEventListener('click', function() {
           const input = this.parentElement.querySelector('.qty-input');
           input.value = Math.max(1, parseInt(input.value) - 1);
           input.dispatchEvent(new Event('change'));
       });
   });
   document.querySelectorAll('.qty-increase').forEach(btn => {
       btn.addEventListener('click', function() {
           const input = this.parentElement.querySelector('.qty-input');
           input.value = parseInt(input.value) + 1;
           input.dispatchEvent(new Event('change'));
       });
   });

   // Remove item
   document.querySelectorAll('.remove-item-btn').forEach(btn => {
       btn.addEventListener('click', function() {
           const rowId = this.dataset.id;
           if (!confirm('Xóa sản phẩm khỏi giỏ hàng?')) return;

           fetch('{{ route("client.cart.remove") }}', {
               method: 'POST',
               headers: {
                   'Content-Type': 'application/json',
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
               },
               body: JSON.stringify({ row_id: rowId })
           })
           .then(res => res.json())
           .then(data => {
               if (data.status === 'success') {
                   this.closest('[data-row-id]').remove();
                   document.querySelector('.cart-total-amount').textContent =
                       new Intl.NumberFormat('vi-VN').format(data.cart_total);
                   document.querySelector('.cart-count').textContent = data.cart_count;
                   // Reload if cart empty
                   if (data.cart_count === 0) location.reload();
               }
           });
       });
   });
   ```

4. **Add data attributes to quantity inputs** and `item-total` / `cart-total-amount` CSS classes to summary elements for JS targeting.

5. **Run Pint** (no PHP changes, JS-only, but verify no Blade syntax errors).

## Success Criteria

- [x] Cart page renders all session items with correct name, SKU, variant, price, quantity
- [x] +/- buttons update quantity via AJAX and refresh item total + cart total without page reload
- [x] Remove button deletes item and updates UI
- [x] Empty cart shows "Giỏ hàng trống" message with link to shop
- [x] Mobile layout matches existing design (quantity controls below item info)
- [x] "Thanh toán" button links to checkout page (existing `route('client.cart.checkout')`)

## Risk Assessment

- **Image path**: Items with no image should show placeholder. Add fallback: `{{ asset('storage/' . ($item['image'] ?? 'placeholder.jpg')) }}`
- **JS event binding**: Use event delegation or ensure script runs after DOM ready. Since script is at bottom of Blade file, DOM is ready.
