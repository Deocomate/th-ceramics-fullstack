---
phase: 2
title: "Polymorphic CartService"
status: completed
priority: P1
effort: "4h"
dependencies: [1]
---

# Phase 2: Polymorphic CartService

## Overview

Build `App\Services\CartService` that normalizes 9 heterogeneous product tables into a unified session-based cart. Each product type has its own query logic in `getProductDetails()`. The service must use session key `th_cart` and treat the single synced quantity input on the UI as the Source of Truth for quantities.

## Requirements

- Functional: Add/update/remove/get/clear items. Compute total. Validate price > 0.
- Non-functional: All queries via Eloquent (not raw DB). Session key: `th_cart`. Image normalization: always return a single image string.

## Architecture

```
CartService
├── sessionKey = 'th_cart'
├── getProductDetails(type, id, variantId): array  ← 9-way match
├── add(type, id, variantId, qty): void
├── update(rowId, qty): void
├── remove(rowId): void
├── getCart(): array
├── getTotal(): int
├── getCount(): int
└── clear(): void
```

## Implementation Steps

1. **Create CartService class**
   ```bash
   php artisan make:class App/Services/CartService --no-interaction
   ```

2. **Implement `getProductDetails()`** with a `match()` on `$productType` covering all 9 cases. Normalize image output (some products store JSON arrays, others a single string) and always return a single image string.

   Example for type `ngoi_hai_van_mieu_ct` (has variants with price):
   ```php
   'ngoi_hai_van_mieu_ct' => function () use ($productId, $variantId) {
       $product = NgoiHaiVanMieuCt::findOrFail($productId);
       $variant = $variantId ? MauSacNgoiHaiVanMieuCt::find($variantId) : null;

       if ($variantId && !$variant) throw new \Exception('Biến thể không tồn tại.');

       return [
           'name'         => $product->name,
           'variant_name' => $variant?->name,
           'sku'          => $variant?->code ?? $product->code,
           'price'        => $variant?->price ?? $product->price,
           'image'        => $variant?->image ?? (is_string($product->images) ? (json_decode($product->images, true)[0] ?? $product->images) : ($product->images[0] ?? null)),
       ];
   }
   ```

   For types 7-9 (no price/ct table): return `price = 0` to trigger the "contact us" flow on the frontend.

3. **Implement `add()` method**
   - Generate `row_id = md5("{$type}_{$id}_{$variantId}")`.
   - If exists in cart: increment quantity.
   - If new: call `getProductDetails()`. If `price <= 0` throw exception with message: "Vui lòng liên hệ đặt hàng". Build item snapshot (product_type, product_id, variant_id, name, variant_name, sku, price, image, quantity) and persist into session under `th_cart`.

4. **Implement `update()` method**
   - Validate quantity > 0
   - Update session cart item quantity

5. **Implement `remove()` method**
   - Unset item by `row_id` from session cart

6. **Implement `getCart()`, `getTotal()`, `getCount()`, `clear()`**

7. **Register in service container** if desired (the service can be injected where needed; not required to bind explicitly).

8. **Run Pint**: `vendor/bin/pint --dirty`

## Success Criteria

- [x] `getProductDetails()` returns correct name/sku/price/image for all 9 types
- [x] Adding same product+variant increments quantity instead of duplicating
- [x] Adding product with price <= 0 throws exception with clear message
- [x] Cart persists across requests via session
- [x] `getTotal()` returns sum of (price × quantity) for all items
- [x] `clear()` empties the cart

## Risk Assessment

- **Wrong variant mapping**: Double-check FK relationships for `mau_sac_ngoi_hai_van_mieu_ct` vs `mau_sac_ngoi_am_duong_ct` (latter has NO FK to product). Mitigated by explicit schema mapping table above.
- **Image path format**: Some products store JSON array, some single string. Normalize in `getProductDetails()` — always return first image as string.
- **Session driver**: App uses database session driver. Large cart (50+ items) still fine since session serialization handles arrays.
