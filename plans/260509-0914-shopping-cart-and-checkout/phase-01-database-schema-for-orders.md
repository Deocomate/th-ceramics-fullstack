---
phase: 1
title: "Database Schema for Orders"
status: completed
priority: P1
effort: "1h"
dependencies: []
---

# Phase 1: Database Schema for Orders

## Overview

Create `orders` and `order_items` tables with migrations, Eloquent models, and factories for testability. Include `shipping_fee` and the updated 6-order-status enum.

## Requirements

- Functional: Store customer order data with polymorphic product references. Generate unique order codes.
- Non-functional: Indexed on `order_code`, `user_id`, `status` for query performance.

## Architecture

```
orders (1) ──< (N) order_items
  │                  │
  └─ user_id (FK)    └─ order_id (FK)
                      └─ product_type (varchar, polymorphic key)
                      └─ product_id, variant_id (nullable)
```

## Related Code Files

- Create: `database/migrations/xxxx_xx_xx_create_orders_table.php`
- Create: `database/migrations/xxxx_xx_xx_create_order_items_table.php`
- Create: `app/Models/Order.php`
- Create: `app/Models/OrderItem.php`
- Create: `database/factories/OrderFactory.php`
- Create: `database/factories/OrderItemFactory.php`

## Implementation Steps

1. **Create orders migration**
   ```bash
   php artisan make:migration create_orders_table --no-interaction
   ```
   Columns: `id` (bigIncrements), `user_id` (nullable foreignId on users), `order_code` (string unique, e.g. `THC-20260509-XXXX`), `customer_name` (string), `phone` (string), `email` (string nullable), `address` (text), `note` (text nullable), `subtotal` (bigInteger, in VND), `shipping_fee` (bigInteger default 0), `discount` (bigInteger default 0), `total_amount` (bigInteger), `payment_method` (enum: `cod`,`banking`, default `cod`), `status` (enum: `pending_payment`, `processing`, `shipping`, `completed`, `canceled`, `returned`), `timestamps`.

2. **Create order_items migration**
   ```bash
   php artisan make:migration create_order_items_table --no-interaction
   ```
   Columns: `id`, `order_id` (foreignId on orders cascade), `product_type` (string 50), `product_id` (bigInteger), `variant_id` (nullable bigInteger), `product_name` (string), `variant_name` (string nullable), `sku` (string nullable), `price` (bigInteger), `quantity` (integer), `total` (bigInteger), `timestamps`.
   Index: `['order_id']`, `['product_type', 'product_id']`.

3. **Create Order model**
   ```bash
   php artisan make:model Order --no-interaction
   ```
   - `$fillable`: all columns except id/timestamps
   - `casts()`: `status` as enum cast
   - Relationship: `items()` → hasMany OrderItem
   - Relationship: `user()` → belongsTo User (nullable)
   - Static helper: `generateOrderCode()` → `THC-{date}-{random4}` with uniqueness check

4. **Create OrderItem model**
   ```bash
   php artisan make:model OrderItem --no-interaction
   ```
   - `$fillable`: all columns except id/timestamps
   - Relationship: `order()` → belongsTo Order

5. **Create factories** for test data generation

6. **Run migration**
   ```bash
   php artisan migrate --no-interaction
   ```

## Success Criteria

- [x] `orders` table exists with all specified columns including `shipping_fee` and `total_amount`
- [x] `order_items` table exists with FK to `orders`
- [x] `Order::generateOrderCode()` produces unique `THC-YYYYMMDD-XXXX` codes
- [x] Models have working relationships (`$order->items`, `$item->order`)
- [x] Factories can generate valid Order + OrderItem test data

## Notes
- Default `status` logic: set `pending_payment` for `banking` payment, `processing` for `cod`. Enforce unique index on `order_code` to prevent collisions and use a retry loop when generating codes.

## Risk Assessment

- **Order code collision**: mitigate with `do...while` uniqueness loop + unique index
- **Missing user FK on delete**: set nullable, don't cascade. Order history preserved even if user deleted.
