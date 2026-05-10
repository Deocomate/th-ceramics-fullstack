# Project Module Client Dynamic -- Smooth But Brittle Gallery Assumptions

**Date**: 2026-05-10 07:47
**Severity**: Low
**Component**: Projects module (Seeder, Client Index, Client Detail)
**Status**: Completed

## What Happened

Three-phase implementation to dynamic-ify the client-facing Projects module. Phase 1 created a seeder with 5 categories and 20 real Vietnamese projects (Chua Bai Dinh, Thien Vien Truc Lam, etc.), each with a 3-image JSON array. Phase 2 dynamic-ified `/du-an` with mobile dropdown and desktop tab filters, a project card grid with empty state, and query-string-preserving pagination. Phase 3 dynamic-ified `/du-an/{slug}` with a hero banner, meta bar, conditional gallery grid, GLightbox integration, and related projects section.

All three phases completed without blockers. The plan was well-structured and the constraints (preserve 100% of original Tailwind classes, AOS animations, HTML structure) were met throughout.

## The Brutal Truth

This went unusually smoothly because the plan anticipated every gotcha before they became gotchas. The image path strategy, the `appends(request()->query())` call for pagination, the dual `data-gallery` attributes for desktop vs mobile GLightbox -- all were thought through upfront. This is what happens when you spend time on the plan phase instead of rushing to code.

The only uncomfortable part is the gallery layout. The conditional rendering with nested `@if($count >= 3)` / `@if($count >= 7)` / `@if($count >= 8)` blocks means the grid layout is hardcoded to specific image counts. If an admin uploads 5 images through the admin panel (which has no image-count limits), the gallery will render rows 1 and 2 but look incomplete because row 2 expects exactly 4 images and row 3 never triggers. This is a time bomb, not a bug.

## Technical Details

**Image path strategy:**
- Seeder stores: `"assets/images/factory-01.jpg"` (relative, no leading slash)
- View renders: `asset('storage/' . $path)` via the `public/storage` symlink
- Seeder copies files at runtime: `File::copyDirectory(public_path('assets/images'), storage_path('app/public/assets/images'))`
- Admin-uploaded images go through `FileUploadHelper` to `du_an/images/` and render via the same `asset('storage/' .`) helper
- Both paths work because the view just prepends `storage/` to whatever the DB stores

**Category filtering without a slug column:**
The `danh_muc_du_an` table has no `slug` column. Instead, the controller does:
```php
$matchedCategory = $categories->first(fn ($cat) => Str::slug($cat->ten_danh_muc) === $categorySlug);
```
This works because `$categories` is always the full set (never more than 5-10 rows). No migration needed. The tradeoff is that renaming a category in admin breaks existing filter URLs -- but the plan explicitly accepted this.

**Slug uniqueness in seeder:**
```php
if (isset($usedSlugs[$slug])) {
    $usedSlugs[$slug]++;
    $slug = $slug . '-' . $usedSlugs[$slug];
} else {
    $usedSlugs[$slug] = 1;
}
```
Manual counter rather than relying on DB auto-increment. All 20 projects have unique Vietnamese names so collisions are unlikely, but the guard is there.

**GLightbox gallery separation:**
Desktop gallery uses `data-gallery="project-gallery"`. Mobile Swiper uses `data-gallery="project-gallery-mobile"`. Without this, GLightbox would pool all images from both viewports into one lightbox collection, doubling the slide count on viewport resize. Simple fix, easy to miss.

**Gallery layout thresholds in `detail.blade.php`:**
| Image count | Renders |
|-------------|---------|
| 1-2 | Simple 2-col grid with all images |
| 3-6 | Row 1 (2-col featured + 1-col side) only |
| 7 | Rows 1 + 2 (4 equal cols) |
| 8+ | Rows 1 + 2 + 3 (1+2 reversed) |

There is no handling for counts 4, 5, or 6 specifically. Images at indices 2-5 in those cases simply never render.

**Custom pagination (no separate view file):**
The `list-pagination.blade.php` partial implements pagination inline with `$projects->getUrlRange()`, `$projects->onFirstPage()`, `$projects->hasMorePages()`. No `resources/views/vendor/pagination/*.blade.php` file was created because this pagination style is specific to the projects page and not reusable elsewhere. The decision respects YAGNI but means this page cannot benefit from future global pagination style updates.

## What We Tried

No failed attempts. The planning phase (done by `planner` agent before implementation started) identified all the edge cases:

- Empty project list: handled with `@if($projects->isEmpty())` and Vietnamese message
- Images accessor returns array: `$project->images` is a JSON column cast to array in the `DuAn` model
- Missing `Str` facade in Blade: plan explicitly required `\Illuminate\Support\Str::` in all Blade files to avoid `Class 'Str' not found`
- Related projects empty: guarded with `@if($relatedProjects->isNotEmpty())`
- Broken images: `<img onerror="this.src='{{ asset('assets/images/factory-01.jpg') }}'">` as direct fallback

## Root Cause Analysis

No root cause to analyze -- this was a clean build. The success is attributable to:

1. The plan was written by someone who understood the codebase's conventions (Tailwind classes, AOS attributes, partial composition pattern)
2. Every view file's static HTML was read before the dynamic template was written, so every `class=""`, `data-aos=""`, and SVG inline path was preserved exactly
3. The image path strategy was designed for two separate upload flows (admin panel vs seeder) without requiring a migration or column change

## Lessons Learned

**Good patterns to repeat:**
- Using `firstOrCreate` with slug as the unique key means the seeder is idempotent -- re-running `db:seed` after code changes won't duplicate data
- The `appends(request()->query())` call on the paginator is the canonical Laravel way to preserve query strings, and it handles `page` params correctly (overrides them rather than duplicating)
- Inline pagination without a vendor view file keeps the code self-contained when the pagination style is genuinely unique to one page

**Patterns to avoid or improve:**
- The gallery layout thresholds are hardcoded to a 3-image seeder. The admin panel lets users upload arbitrary image counts, which means real data could produce visually broken layouts. Either the admin panel should enforce image count limits, or the gallery should dynamically tile based on actual count rather than fixed thresholds
- `\Illuminate\Support\Str::` sprinkled across Blade files is noisy. A Blade directive or a `project_img()` helper would be cleaner

## Next Steps

- **Owner**: Future developer touching admin DuAn edit
- **Action**: If admin image upload stays unlimited, refactor `detail.blade.php` gallery to use `array_chunk` / dynamic tiling instead of hardcoded `$count >= N` thresholds
- **When**: When the first admin-uploaded project with 4, 5, or 6 images renders with missing images
