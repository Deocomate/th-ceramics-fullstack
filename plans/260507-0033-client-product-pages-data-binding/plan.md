---
title: "Wire Client Product Pages with Database Data"
description: "Wire 8 client product controllers with Service DI, implement index/detail logic, and update Blade views to render dynamic DB data while preserving existing UI templates"
status: completed
priority: P2
branch: "main"
tags: [client, products, controllers, views, data-binding]
blockedBy: []
blocks: []
created: "2026-05-06T17:44:37.375Z"
createdBy: "ck:plan"
source: skill
---

# Wire Client Product Pages with Database Data

## Overview

Wire all 8 unwired client product controllers (all except `NgoiAmDuongController` which is the reference implementation) with Service dependency injection, implement `index()` and `detail()` logic to fetch from DB, and update Blade views to render dynamic data — all while preserving existing UI templates unchanged.

**Reference:** `NgoiAmDuongController` at `app/Http/Controllers/Client/ProductPages/NgoiAmDuongController.php` — 5 services injected, `index()` returns config/products/giaTriVuotTroi, `detail($id)` returns product/colors/dinhMuc/relatedProducts with `is_delete` 404 guard.

## Phases

| Phase | Name | Status | Priority | Effort |
|-------|------|--------|----------|--------|
| 1 | [Wire Controllers with Dependency Injection](./phase-01-wire-controllers-with-dependency-injection.md) | Complete | P1 | 1h |
| 2 | [Implement index() Logic and Update index Views](./phase-02-implement-index-logic-and-update-index-views.md) | Complete | P1 | 4h |
| 3 | [Implement detail() Logic and Update detail Views](./phase-03-implement-detail-logic-and-update-detail-views.md) | Complete | P1 | 4h |
| 4 | [Handle Special Cases](./phase-04-handle-special-cases.md) | Complete | P2 | 2h |

## Dependencies

- Phase 2 depends on Phase 1 (controllers must have DI before index logic works)
- Phase 3 depends on Phase 1 (controllers must have DI before detail logic works)
- Phase 2 and Phase 3 can run in parallel after Phase 1
- Phase 4 depends on Phases 2 and 3

## Key Constraints

1. **DO NOT change UI templates** — only replace static text/image paths with Blade `{{ }}` and `@foreach`
2. All services already exist and follow the `getAll($status)` / `getFirstRecord()` / `findById($id)` pattern
3. All models cast `images`, `des`, `size_des` to `array` via `casts()` — no manual `json_decode` needed in views
4. Use `Storage::url()` for DB-stored image paths, `asset()` for static assets that remain

## Scope

- **Controllers to wire (8):** DenGomSu, GachCoBatTrang, GachHoaThongGio, GachTrangTri, LanCanGomSu, LinhVatPhongThuy, NgoiHaiVanMieu, PhuKienNgoi
- **Already wired (reference):** NgoiAmDuongController
- **Not in scope:** NgoiHaiCo (no client routes exist), admin controllers, shared layout components

## Completion Notes (2026-05-07)

All 4 phases completed. Key implementation details:

- 8 controllers wired with PHP 8.1 constructor property promotion
- All service references verified against `app/Services/` directory
- `LanCanGomSuController` uses `LanCanGomXuService` (model/DB name is "Xu" not "Su")
- Shared components updated: `product-grid` (accepts `routeName` prop), `product-image-swiper`, `product-detail-container`, `trang-tri-process`
- No CT tables exist for `DenGomSu` and `LanCanGomSu` — detail pages kept static per spec (Option A)
- `PhuKienNgoi` cross-table lookup uses try/catch with `ModelNotFoundException`
- All views use `Storage::url()` for DB image paths with null-safe guards
- Price fallback pattern: `$product->price > 0 ? number_format(...) . 'đ' : 'Liên hệ'`
