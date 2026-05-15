# Lan Can Gom Su Dynamic Product Pages -- Full Implementation Complete

**Date**: 2026-05-15
**Severity**: N/A (Milestone Completion)
**Component**: Product Pages / Lan Can Gom Su (Ceramic Railing)
**Status**: Resolved

## What Happened

Completed a full-stack rebuild of the Lan Can Gom Su (Ceramic Railing) product section across 8 phases: controller injection, index page rebuild, detail page rebuild, model accessors, service-layer eager loading, CartService integration, admin configuration, and SEO with JSON-LD structured data. The implementation replaced hardcoded Blade views with fully dynamic data-driven pages powered by a new `LanCanGomSuCtService` and reusable `desktop-product-card` component.

119/119 tests passing. 1 code review fix applied (JSON-LD escaping via `e()` helper). 1 known gap deferred: the admin product picker for section arrays uses hardcoded product IDs rather than a dynamic picker widget.

## The Brutal Truth

This felt clean. No late-night firefighting, no cascading regressions, no "works on my machine" surprises. The 8-phase plan held together because each phase had a narrow scope and a verifiable output. The only real frustration was the admin section -- the migration added 6 nullable columns (`section_1_image` through `section_3_image`, and matching `section_1_title` through `section_3_title`) and the admin form wired them up correctly, but the product picker for section content arrays remains hardcoded. It works, but it's ugly and anyone maintaining it will curse us. Deferred because building a proper dynamic picker would have doubled the admin phase scope.

The biggest risk we dodged: N+1 queries. By centralizing eager loading in the service layer (`with('phanLoais')` constrained to active variants only), we avoided scattering `with()` calls across a dozen controller methods and Blade partials. If someone adds a new query path later and forgets to eager load, the whole page silently degrades to 50+ queries. The service layer boundary is the single point of failure for performance.

## Technical Details

**Key files changed/created:**

| File | Action | Purpose |
|------|--------|---------|
| `app/Http/Controllers/Client/ProductPages/PhuKienNgoiController.php` | Modified | Injected `LanCanGomSuCtService`, passes dynamic data to views |
| `app/Services/LanCanGomSuCtService.php` | Created | Centralized service with `getAll()` and `findById()`, eager loads `phanLoais` constrained to active variants |
| `app/Models/LanCanGomSuCt.php` | Modified | Added `display_code` and `display_price` accessors |
| `resources/views/clients/products/phu-kien-ngoi/index.blade.php` | Rewritten | Header padding fix (`pt-[58px] xl:pt-[108px]`), two category sections, masonry grid |
| `resources/views/clients/products/phu-kien-ngoi/detail.blade.php` | Created | Breadcrumb, image gallery, variant JS, specs, JSON-LD |
| `app/Services/CartService.php` | Modified | Added `getLanCanGomSuDetails()` handler |
| `app/Http/Requests/Cart/AddToCartRequest.php` | Modified | Added validation rules for `lan_can_gom_su_ct_id` + variant |
| `database/migrations/2026_05_15_xxxxxx_add_section_config_to_lan_can_gom_xu.php` | Created | 6 nullable columns for section images/titles |
| `resources/views/admin/lan-can-gom-su-ct/edit.blade.php` | Modified | Section image uploads, title text inputs |

**Error that surfaced in code review:**
The initial JSON-LD block used `{{ }}` Blade syntax to echo product data into a `<script type="application/ld+json">` tag. This broke whenever product names or descriptions contained double quotes. Fixed by wrapping in `e()` -- `{!! e(json_encode(...)) !!}` -- which properly escapes HTML entities without double-encoding.

**CSS fix worth noting:**
The index page header had a spacing collision with the fixed navbar. The original `pt-[58px]` worked on mobile but broke on desktop where the navbar height differs. The fix: responsive padding `pt-[58px] xl:pt-[108px]`.

## What We Tried

No dead ends. The implementation followed the plan linearly with one adjustment: the `display_code` and `display_price` accessors were extracted mid-implementation when we noticed the same `($item->ma_sp ?? 'N/A')` ternary pattern repeated across 4 Blade partials. Moving it to the model saved ~15 lines of duplicated logic.

## Root Cause Analysis

This was not a bugfix -- it was a feature completion. The root cause of the _prior_ state was that Lan Can Gom Su was the last product category still using hardcoded Blade views from the initial site build. The other product categories (Ngoi, Phu Kien Ngoi) had already been migrated to dynamic service-driven pages. This implementation brought Lan Can Gom Su up to parity.

## Lessons Learned

1. **Service-layer eager loading as a contract**: By making the service responsible for eager loading, we turned N+1 prevention from a per-developer discipline into an architectural invariant. This is the right pattern for all product services going forward.

2. **Model accessors over inline ternaries**: `$product->display_code` beats `$product->ma_sp ?? 'N/A'` every time. Two benefits: single source of truth for formatting, and the accessor name documents intent (it's a _display_ value, not a raw field).

3. **Nullable columns for admin config tables are the right call when the feature isn't critical-path**: The 6 new `lan_can_gom_xu` columns are all nullable. This means the migration runs without data loss, the admin form works with or without section data, and the frontend gracefully degrades when sections are empty. Zero downtime, zero required data backfill.

4. **JSON-LD in Blade requires double-encoding awareness**: `json_encode()` + `e()` is the correct pair. `json_encode()` alone is vulnerable to XSS if any user-controlled data ends up in the JSON. `e()` alone would double-encode JSON structure characters. The pair handles both concerns.

## Next Steps

- **Deploy**: Merge to main, run migration, `npm run build`, deploy. No breaking changes.
- **Deferred**: Build a dynamic product picker widget for the admin section array configuration. Currently the section products are hardcoded by ID. Owner: TBD. Priority: Low -- sections display correctly, just not configurable via UI.
- **Pattern propagation**: Apply the same service-layer eager loading pattern to any remaining product services that predate this convention. Check `NgoiService` and `PhuKienNgoiService` for consistency.
