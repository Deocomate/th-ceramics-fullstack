# Desktop Product Slider on Phu Kien Ngoi -- Vanilla JS Pagination

**Date**: 2026-05-14
**Severity**: Low
**Component**: Client-side UI -- Phu Kien Ngoi product pages
**Status**: Resolved

## What Happened

The Phu Kien Ngoi (Roof Accessories) category pages had three blade templates (`category-section-1`, `category-section-2`, `index`) each rendering product grids with a static `->take(4)` call in their respective admin-render controller methods. Desktop users saw at most 4 products. The fix converted each grid from a single row of 4 to a chunked slider using vanilla JS client-side pagination -- each page shows 4 items, previous/next buttons cycle through chunks.

## The Brutal Truth

This was not a backend problem. The admin-side detail controllers were already fetching all products via `BoNocChuVanCtService::getAll()` but the blade templates were calling `->take(4)` in the loop, discarding the rest. The "fix" was four lines of Blade/PHP change: replace `->take(4)` with `->chunk(4)` and assign the chunks to a `$chunks` variable. The rest -- 60+ lines of vanilla JS and CSS -- is presentation fluff.

The frustrating part is that this is a band-aid over the real problem: there is no pagination abstraction shared across category pages. Each template now has its own copy-pasted slider controller with data attributes. The next category page will get the same copy-paste treatment unless someone extracts a reusable component.

## Technical Details

### Blade Changes (all three files, same pattern)

Before:
```php
@foreach ($products->take(4) as $boNocChuVanCt)
    <div class="col-lg-3 col-md-6"> ... </div>
@endforeach
```

After:
```php
@php $chunks = $products->chunk(4); @endphp
@foreach ($chunks as $pageIndex => $chunk)
    <div class="row g-4 product-slider-page"
         data-desktop-slider="bo-noc-chu-van"
         data-slider-page="{{ $pageIndex }}"
         style="display: {{ $pageIndex === 0 ? 'flex' : 'none' }};">
        @foreach ($chunk as $boNocChuVanCt)
            <div class="col-lg-3 col-md-6"> ... </div>
        @endforeach
    </div>
@endforeach

@if ($chunks->count() > 1)
    <div class="slider-controls">
        <button class="btn-slider-prev" data-btn-prev="bo-noc-chu-van">Prev</button>
        <span class="slider-page-indicator">1 / {{ $chunks->count() }}</span>
        <button class="btn-slider-next" data-btn-next="bo-noc-chu-van">Next</button>
    </div>
@endif
```

### JS Slider Controller (inlined in each blade)

Vanilla JS using `querySelectorAll('[data-desktop-slider="..."]')`. Buttons toggle `display: none/flex` on rows and update the page indicator. Disabled states added via `style.opacity` and `pointer-events`. Prev hidden on page 0, next hidden on last page.

### CSS Animation

`@keyframes fadeInSlider` -- `opacity: 0` to `opacity: 1` over 0.4s with `ease-in-out`, applied to `.product-slider-page.switching`.

### What Was Preserved

- GLightbox `data-gallery` attributes left untouched on all product images
- Mobile carousel (Swiper/Glide, whatever is on page) unchanged -- slider only activates on `@media (min-width: 992px)` via CSS hiding the controls
- No backend controllers, services, or routes modified

## Root Cause Analysis

The root cause is architectural neglect: each category page (Ngoi Bo Noc, Den Vuon Gom, Gach Co Bat Trang, Lan Can Gom Su, Linh Vat Phong Thuy) has its own blade, its own controller, its own Service class, and its own FormRequest. All 5 follow the same pattern but share zero code. Adding a slider to one category means copy-pasting to 4 more -- no one did the work, so only Phu Kien Ngoi got it this round.

## Lessons Learned

1. **`->chunk()` on Collections is cheap and effective.** No need for `->skip()`/`->take()` loops. The entire Collection is already in memory; chunking just slices it.

2. **Data attributes beat IDs for JS wiring.** Using `data-desktop-slider="bo-noc-chu-van"` meant the same JS snippet works across all three sub-pages without ID collisions.

3. **Hide buttons, don't remove them.** Toggling `display` on controls conditionally (`@if ($chunks->count() > 1)`) means the DOM stays stable and the JS doesn't need to handle missing elements.

4. **Copy-paste is the enemy.** Next developer adding a slider to Lan Can Gom Su will copy this JS verbatim. The slider logic belongs in a single JS class or Alpine component. Budget 45 minutes to extract it before the next category page request lands.

## Next Steps

- Extract the slider JS into a single `product-slider.js` in `public/js/components/`, initialized via `data-desktop-slider` attribute auto-discovery. Owner: whoever picks up the next Phu Kien Ngoi ticket. No hard deadline.
- The copy-paste across 5 category types remains a broader problem outside this journal's scope. Flagged for architecture discussion.
