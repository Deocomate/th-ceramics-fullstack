# Dynamic Customer Service Pages: Plan for Catalog, Installation Guide, and PDF Flipbook

**Date**: 2026-05-09 18:36
**Severity**: Low (planning phase, no code written yet)
**Component**: Customer-service pages (Catalog, Installation Guide, Flipbook)
**Status**: Pending execution

## What Happened

Created a 3-phase implementation plan to convert two static customer-service Blade views into dynamic pages driven by existing database models, plus a new PDF flipbook reader. Plan saved to `plans/260509-1820-catalog-thicong-flipbook/`.

## The Brutal Truth

Somebody built the entire backend -- models, migrations, services with full CRUD -- and then shipped controller stubs that literally do nothing. `CatalogController::index()` returns `view("")`. That is not a typo; that is an empty string passed to `view()`. `HuongDanThiCongController::index()` has `reutrn view("")` -- both a typo AND an empty view. The services (`CatalogService`, `ThiCongService`) are feature-complete. The database tables exist. The routes are registered. The Blade views are fully styled with Tailwind. Every piece of infrastructure is sitting there, wired up, waiting for someone to write the 5 lines of Eloquent that connect them. Instead, the pages render whatever `view("")` resolves to -- probably a blank screen or a Laravel error depending on the framework version.

This is the "last 10% that takes 90% of the time" problem, except the controllers aren't even partially done. They are placeholder corpses. The fact that both controllers were written this way suggests a deliberate handoff that never happened, or a feature that was descoped at the last minute and the stubs were left as documentation of intent.

## Technical Details

**Current state of `CatalogController.php` (line 9-10):**
```php
public function index(){
    return view("");
}
```

**Current state of `HuongDanThiCongController.php` (line 9-10):**
```php
public function index(){
    reutrn view("");
}
```

The `reutrn` typo means this controller would throw a PHP fatal error if ever actually routed to. Nobody noticed because nobody tested it. Or nobody visits the installation guide page.

**What exists and works:**
- `CatalogService` and `ThiCongService` -- both with full CRUD, file uploads, image handling
- `Catalog` model (table `catalog`, PK `catalog_id`, fields: `tieu_de`, `anh_dai_dien`, `file`, timestamps)
- `ThiCong` model (table `thi_cong`, PK `thi_cong`, fields: `tieu_de`, `anh`, `link_youtube`, timestamps)
- Routes registered in `routes/client.php` under `dich-vu` prefix group
- Static Blade views in `catalog-content.blade.php` (1 featured item + 6 hardcoded grid items) and `installation-guide-content.blade.php` (8 hardcoded alternating rows)

## Plan Architecture Decisions

**Three parallel phases with one merge conflict:**

| Phase | Files Touched | Independent? |
|-------|--------------|-------------|
| 1 - Installation Guide | `HuongDanThiCongController.php`, `installation-guide-content.blade.php` | Yes |
| 2 - Catalog List | `CatalogController.php`, `catalog-content.blade.php` | Yes (alone) |
| 3 - Flipbook Reader | `routes/client.php`, `CatalogController.php`, `flipbook.blade.php` (new), `catalog-content.blade.php` | Conflicts with Phase 2 |

Phase 2 and Phase 3 both modify `catalog-content.blade.php` and `CatalogController.php`. The mitigation is that Phase 2 writes `url()` helpers for catalog links, and Phase 3 replaces them with `route()` helpers. This is a deliberate two-step pattern: Phase 2 can ship independently with working links pointing to a URL pattern that Phase 3 will later define as a named route.

**Rejected alternative:** Sequential execution. Would have avoided the merge conflict entirely but at the cost of 2x wall-clock time for what amounts to 3 simple file edits each.

**`url()` vs `route()` tradeoff:** Phase 2 uses `url('/dich-vu/tai-catalog/doc/' . $id)` instead of `route('client.dich-vu.tai-catalog.read', $id)` because the named route does not exist until Phase 3 creates it. This means the initial catalog page ships with hardcoded URL strings. Ugly, but it works in isolation. Phase 3's Task 3.3 cleans this up.

**`order-*` classes problem in Phase 1:** The original static view uses manual `order-1` through `order-16` Tailwind classes to create a zigzag text/image layout. Replacing 8 static rows with a `@foreach` loop breaks this because order values must be computed per iteration. The plan recommends dropping `order-*` entirely and letting CSS Grid's natural flow handle alternation. This is a layout behavior change disguised as a "zero HTML/CSS change" constraint -- worth flagging because if the grid doesn't auto-alternate correctly, someone has to go back and add inline `style="order: {{ $loop->iteration }}"`.

**Zero HTML/CSS changes constraint:** Ambitious but necessary to avoid scope creep. The original pages have specific Tailwind classes for every element. The only allowed changes are Blade directives (`@foreach`, `@if`, `@forelse`, `{{ }}`). If any layout breaks because of the transition from static to dynamic, that is a bug, not a design change.

**Flipbook as standalone page:** Phase 3 creates a new Blade view that does not extend any layout. Full-screen `<html>` document with inline CSS. This is intentional -- the flipbook reader is an immersive reading experience, not a standard page. The dark background (`#1a1a2e`) and full-viewport layout would clash with the site's standard header/footer. Tradeoff: no navigation chrome except a back button.

**PDF.js version pin at 2.16.105:** CDN URL is hardcoded. If Cloudflare or jsdelivr deprecates this version, the flipbook silently breaks. No fallback, no integrity hash. This is fine for a P2 feature, would be unacceptable for production-critical functionality.

## Lessons Already Extractable (Before a Single Line of Code)

1. **Empty controller stubs are a code smell that should trigger an immediate task.** When you see `return view("")`, that is not "to be implemented later." That is a bug. If the services, models, and views all exist, the controller should have been wired on the same day.

2. **"reutrn" typos in PHP means zero test coverage for that controller method.** A single `php artisan route:list` would not catch this, but hitting the route once in a browser would. This implies the `/dich-vu/huong-dan-thi-cong` page has never been QA'd.

3. **Phase 2/Phase 3 file overlap on `catalog-content.blade.php` is a known coordination hazard.** The `url()` → `route()` dance is clever but fragile. If Phase 3 never runs, the catalog page ships with hardcoded URLs forever. If the route name changes between plan and implementation, both files have broken links.

4. **The existing journal on the coupon module noted the same parallel-execution risk.** "The moment checkout touched CartService (also modified in phase 3), it should have gone sequential. Got lucky this time." We are knowingly repeating this pattern. The only difference is this time we documented the merge conflict up front.

## Next Steps

- Execute Phase 1, 2, and 3 in parallel (3 subagents)
- Phase 3 must run Step 3.3 (route cleanup) after Phase 2 finishes on `catalog-content.blade.php` -- or Phase 3 agent handles the merge manually
- Verify all 3 pages render with real database data after implementation
- Fix the `reutrn` typo in any case -- even if Phase 1 fails, this should not survive another commit
