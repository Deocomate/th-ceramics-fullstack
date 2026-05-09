---
title: "Shopping Cart & Checkout System"
description: "Polymorphic session-based cart for 9 product types, cart page, checkout page, and order processing with database persistence."
status: completed
priority: P1
branch: "main"
tags: ["cart", "checkout", "orders", "session", "polymorphic"]
blockedBy: []
blocks: []
created: "2026-05-09T09:23:09.105Z"
createdBy: "ck:plan"
source: skill
---

# Shopping Cart & Checkout System

## Overview

Implement a polymorphic shopping cart using Laravel sessions to unify 9 heterogeneous product tables (each with different variant structures). Includes Add to Cart API, dynamic cart page, checkout form, and order persistence to database.

## Core Challenge

9 product detail tables (`ngoi_am_duong_ct`, `ngoi_hai_van_mieu_ct`, `gach_hoa_thong_gio_ct`, etc.) with different schemas. Some have variant subtables (`mau_sac_ngoi_hai_van_mieu_ct`, `phan_loai_ngoi_bo_noc_ct`), some don't. A single `CartService` normalizes all into one cart item structure via session-based polymorphic storage.

## Architecture

```
Browser JS (fetch POST) → CartController (add/update/remove) → CartService (session)
                                                                    ↓
                                              getProductDetails(type, id, variantId) → DB
                                                                    ↓
Browser (Blade) → CartController (cart/checkout) → CartService.getCart()
                                                                    ↓
Checkout POST → Order + OrderItem (DB) → Clear session → Redirect
```

## Phases

| Phase | Name | Status | Priority | Effort |
|-------|------|--------|----------|--------|
| 1 | [Database Schema for Orders](./phase-01-database-schema-for-orders.md) | Completed | P1 | 1h |
| 2 | [Polymorphic CartService](./phase-02-polymorphic-cartservice.md) | Completed | P1 | 4h |
| 3 | [Cart API Routes & Controller](./phase-03-cart-api-routes-controller.md) | Completed | P1 | 2h |
| 4 | [Cart Page Dynamic Rendering](./phase-04-cart-page-dynamic-rendering.md) | Completed | P2 | 3h |
| 5 | [Checkout Page & Order Processing](./phase-05-checkout-page-order-processing.md) | Completed | P2 | 3h |
| 6 | [Frontend Add-to-Cart Integration](./phase-06-frontend-add-to-cart-integration.md) | Completed | P2 | 2h |

## Dependencies

- Phase 2 depends on Phase 1 (Order/OrderItem models for processCheckout)
- Phase 3 depends on Phase 2 (CartService must exist)
- Phase 4 depends on Phase 3 (routes must be defined)
- Phase 5 depends on Phase 4 (cart page pattern informs checkout summary)
- Phase 6 depends on Phase 3 (API routes must exist)

## Key Business Rules

- Products with variants: user MUST select variant before add-to-cart. Price/SKU from variant table.
- Price = 0 ("Contact us"): block add-to-cart button, show "Contact for order" instead.
- Guest users: session-based cart. Login preserves session cart (no merge needed).
- Row uniqueness: md5(product_type + product_id + variant_id).
