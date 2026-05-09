---
phase: 6
title: "Frontend Add-to-Cart Integration"
status: completed
priority: P2
effort: "2h"
dependencies: [3]
---

# Phase 6: Frontend Add-to-Cart Integration

## Overview

Wire up the "THÊM VÀO GIỎ HÀNG" button in `product-detail-container.blade.php` to POST cart data via fetch API. Add variant selection tracking, quantity input, and cart count update in header.

## Requirements

- Functional: Click add-to-cart → validate variant selection if applicable → POST to API → show success feedback → update header cart badge count.
- Non-functional: Works across all 9 product detail pages. Degrades gracefully (alert on error).

## Related Code Files

- Modify: `resources/views/components/products/product-detail-container.blade.php`
- Reference: `resources/views/clients/products/ngoi-am-duong/detail.blade.php` (usage example)
- Reference: `resources/views/clients/products/ngoi-hai-van-mieu/detail.blade.php` (usage example, check if exists)

## Implementation Steps

1. **Add hidden inputs for product_type and product_id** in the component

   Add to `product-detail-container.blade.php`:
   ```blade
   @props(['title' => 'Chi tiết sản phẩm', 'price' => null, 'sku' => null, 'features' => null,
            'colors' => null, 'variants' => null, 'images' => [],
            'productType' => null, 'productId' => null])

   <!-- Hidden product identifiers -->
   <input type="hidden" id="product_type" value="{{ $productType }}">
   <input type="hidden" id="product_id" value="{{ $productId }}">
   ```

2. **Pass productType/productId from all detail pages**

   In each detail.blade.php, add the new props:
   ```blade
   <x-products.product-detail-container
       ...
       productType="ngoi_am_duong_ct"
       productId="{{ $product->ngoi_am_duong_ct_id }}"
   />
   ```

   Do this for ALL 9 detail pages. List of detail files to modify:
   - `clients/products/ngoi-am-duong/detail.blade.php`
   - `clients/products/ngoi-hai-van-mieu/detail.blade.php`
   - `clients/products/gach-hoa-thong-gio/detail.blade.php`
   - `clients/products/gach-trang-tri/detail.blade.php`
   - `clients/products/lan-can-gom-su/detail.blade.php`
   - `clients/products/gach-co-bat-trang/detail.blade.php`
   - `clients/products/linh-vat-phong-thuy/detail.blade.php`
   - `clients/products/den-gom-su/detail.blade.php`
   - `clients/products/phu-kien-ngoi/detail.blade.php`

3. **Add data attributes to variant/color selection elements**

   For types WITH priced variants (ngoi_hai_van_mieu_ct):
   - Add `data-variant-id`, `data-price`, `data-sku` to each color button
   - Add click handler to toggle `.selected` class

   For `ngoi_am_duong_ct` (colors without price):
   - Add `data-color-id` and `data-color-name` for visual selection only
   - variant_id = the color ID, but price comes from product

4. **Replace button with functional version** and add JS

   Update the "THÊM VÀO GIỎ HÀNG" button:
   ```blade
   <button id="btn-add-to-cart" type="button"
       class="w-full md:w-auto {{ $price && $price !== 'Liên hệ' ? 'bg-[#C16A00] hover:bg-secondary cursor-pointer' : 'bg-gray-400 cursor-not-allowed' }} text-[#EFE4DE] px-8 py-4 font-semibold md:transition-colors md:shadow-md rounded-[2px] flex items-center justify-center text-[14px] md:text-sm tracking-[0.28px] md:tracking-normal md:ml-4">
       @if($price && $price !== 'Liên hệ')
           THÊM VÀO GIỎ HÀNG
       @else
           LIÊN HỆ ĐẶT HÀNG
       @endif
   </button>
   ```

5. **Add JavaScript at bottom of the component**

   ```javascript
   <script>
   document.addEventListener('DOMContentLoaded', function() {
       // Variant selection tracking
       document.querySelectorAll('.variant-item').forEach(el => {
           el.addEventListener('click', function() {
               document.querySelectorAll('.variant-item').forEach(e => e.classList.remove('selected'));
               this.classList.add('selected');
           });
       });

       const btnAdd = document.getElementById('btn-add-to-cart');
       if (!btnAdd || btnAdd.classList.contains('cursor-not-allowed')) return;

       btnAdd.addEventListener('click', function() {
           const type = document.getElementById('product_type').value;
           const id = document.getElementById('product_id').value;
           const qtyInput = document.getElementById('quantity_input') ||
                            document.querySelector('[class*="rounded-full"]'); // fallback to counter display
           const qty = qtyInput ? (qtyInput.value || qtyInput.textContent.trim()) : 1;

           // Find selected variant
           const selectedVariant = document.querySelector('.variant-item.selected');
           const variantId = selectedVariant?.dataset.variantId || null;

           // Validate: if there are variants on page but none selected
           const variantElements = document.querySelectorAll('.variant-item');
           if (variantElements.length > 0 && !variantId) {
               alert('Vui lòng chọn màu sắc/phân loại trước khi thêm vào giỏ hàng!');
               return;
           }

           fetch('{{ route("client.cart.add") }}', {
               method: 'POST',
               headers: {
                   'Content-Type': 'application/json',
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
               },
               body: JSON.stringify({
                   product_type: type,
                   product_id: id,
                   variant_id: variantId,
                   qty: qty
               })
           })
           .then(res => res.json())
           .then(data => {
               if (data.status === 'success') {
                   alert('Đã thêm vào giỏ hàng!');
                   const cartCountEl = document.querySelector('.cart-count');
                   if (cartCountEl) cartCountEl.textContent = data.cart_count;
               } else {
                   alert(data.message || 'Có lỗi xảy ra.');
               }
           })
           .catch(() => alert('Lỗi kết nối. Vui lòng thử lại.'));
       });
   });
   </script>
   ```

6. **Ensure CSRF meta tag exists** in `x-layouts.client`:
   ```blade
   <meta name="csrf-token" content="{{ csrf_token() }}">
   ```

7. **Add cart-count badge to header** if not already present. Check the header component.

8. **Run Pint** and verify no syntax errors

## Success Criteria

- [x] Clicking add-to-cart without selecting variant shows "Vui lòng chọn màu sắc/phân loại!" alert
- [x] Clicking add-to-cart with valid data sends POST to `/gio-hang/them`
- [x] Success alert shown: "Đã thêm vào giỏ hàng!"
- [x] Header cart count badge updates after successful add
- [x] Products with price = 0 show "LIÊN HỆ ĐẶT HÀNG" (disabled button) instead of add-to-cart
- [x] Works on all 9 product detail pages

## Risk Assessment

- **Variant selection state not preserved on page reload**: Fine — selection resets naturally on refresh. User must select again.
- **`variant-item` class consistency**: Different product pages may use different CSS classes for variant buttons. Need to check each detail page's variant markup. Use data attributes for reliability.
- **Quantity input selector**: The current design has a `<div class="w-12 h-12...">1</div>` counter without a proper input. Need to add quantity +/- buttons that update a variable, or add a hidden input. Simplest: use the counter display's textContent as quantity.
- **Header cart count element**: Must verify `.cart-count` element exists in header. If not, add it during this phase.
