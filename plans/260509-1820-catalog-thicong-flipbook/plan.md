---
title: "Dynamic Customer Service Pages: Installation Guide, Catalog List & PDF Flipbook"
description: "Convert static customer-service pages to dynamic Blade rendering with data from thi_cong and catalog tables. Build PDF flipbook reader with PDF.js + StPageFlip."
status: completed
priority: P2
branch: "main"
tags: ["frontend", "blade", "catalog", "installation-guide", "pdf-flipbook", "customer-service"]
blockedBy: []
blocks: []
created: "2026-05-09T18:24:05.233Z"
createdBy: "ck:plan"
source: skill
---

# Dynamic Customer Service Pages: Installation Guide, Catalog List & PDF Flipbook

## Overview

Convert 2 static customer-service pages to dynamic Blade rendering using existing `ThiCong` and `Catalog` models. Build new PDF flipbook reader page for catalog files. Zero HTML/CSS changes — only Blade directive injection (`@foreach`, `@if`, `{{ }}`) into existing markup.

## Current State

- **Controllers**: Both `CatalogController` and `HuongDanThiCongController` are empty stubs (`view("")`). `HuongDanThiCongController` has a typo (`reutrn`).
- **Services**: `CatalogService` and `ThiCongService` are fully implemented with CRUD.
- **Models**: `ThiCong` (table `thi_cong`, PK `thi_cong`) and `Catalog` (table `catalog`, PK `catalog_id`) exist.
- **Routes**: Both `/dich-vu/tai-catalog` and `/dich-vu/huong-dan-thi-cong` registered in `client.php`.
- **Views**: `catalog-content.blade.php` (1 featured + 6 grid items) and `installation-guide-content.blade.php` (8 alternating rows) are static HTML.

## Phases

| Phase | Name | Status | Priority | Dependencies |
|-------|------|--------|----------|-------------|
| 1 | [Dynamic Installation Guide](./phase-01-dynamic-installation-guide.md) | Completed | P1 | None |
| 2 | [Dynamic Catalog List](./phase-02-dynamic-catalog-list.md) | Completed | P1 | None |
| 3 | [PDF Flipbook Reader & Integration](./phase-03-pdf-flipbook-reader-integration.md) | Completed | P1 | None |

## Parallel Execution Notes

Phases are **designed for parallel execution** with these considerations:

- **Phase 1** touches: `HuongDanThiCongController.php`, `installation-guide-content.blade.php` — no conflicts.
- **Phase 2** touches: `CatalogController.php`, `catalog-content.blade.php` — no conflicts with Phase 1.
- **Phase 3** touches: `routes/client.php`, `CatalogController.php` (new method), `flipbook.blade.php` (new file), `catalog-content.blade.php` (link wiring).

**Merge consideration**: Phase 2 and Phase 3 both modify `catalog-content.blade.php` and `CatalogController.php`. Phase 2 writes the dynamic loop with `#` placeholder links. Phase 3 adds `read()` method and replaces `#` with `route()`. Merge order: Phase 3 after Phase 2 on those 2 files.

## Existing Plans (No Conflicts)

| Plan | Status |
|------|--------|
| `260509-0914-shopping-cart-and-checkout` | Unknown |
| `260509-1533-coupon-module` | Unknown |
| `260509-1711-order-management-and-email-system` | Unknown |

No file overlap with any existing plan.

## Success Criteria

- [x] Installation guide page renders all records from `thi_cong` table with correct alternating layout
- [x] Catalog page renders featured item + grid from `catalog` table
- [x] Empty states display gracefully on both pages
- [x] PDF flipbook loads PDF, renders all pages, supports page-flip animation
- [x] Flipbook responsive: 2-page spread on desktop, 1-page on mobile
- [x] All existing Tailwind classes, HTML structure preserved exactly
- [x] No console errors, no broken images
