---
phase: 3
title: "PDF Flipbook Reader & Integration"
status: completed
priority: P1
effort: "1.5h"
dependencies: []
---

# Phase 3: PDF Flipbook Reader & Integration

## Overview

Build a full-screen PDF flipbook reader using PDF.js (Mozilla) for rendering PDF pages and StPageFlip (page-flip.js) for the 3D book-flip animation. Add route + controller method, create new Blade view, and wire catalog "Xem chi tiết" buttons to this reader.

## Requirements

- Functional: Reader page loads PDF from storage URL, renders every page to canvas elements
- Functional: 3D page-flip animation (2-page spread on desktop, single page on mobile)
- Functional: Back button to return to catalog list
- Functional: Loading spinner while PDF renders
- Functional: Responsive — `size: "stretch"` adapts to viewport
- Non-functional: CDN-loaded JS dependencies (no npm install needed)
- Non-functional: Dark background to make book stand out
- Integration: Update `catalog-content.blade.php` to use `route()` helper (cleanup from Phase 2's `url()` fallback)

## Architecture

```
GET /dich-vu/tai-catalog/doc/{id} → CatalogController::read($id)
  → Catalog::findOrFail($id)
  → view('clients.dich-vu-khach-hang.flipbook', ['catalog' => $catalog])

flipbook.blade.php:
  1. Inline <style> for dark fullscreen layout + spinner
  2. PDF URL passed via data attribute: data-pdf-url="{{ asset('storage/' . $catalog->file) }}"
  3. <div id="flipbook"> as container
  4. Loading spinner overlay
  5. Back button <a> → route('client.dich-vu.tai-catalog')
  6. Inline <script>:
     a. pdfjsLib.getDocument(url).promise → pdf
     b. Loop pdf.numPages: page.render() → <canvas class="my-page">
     c. Append canvases to #flipbook
     d. new St.PageFlip('#flipbook', {...}).loadFromHTML('.my-page')
     e. Hide spinner
```

## Related Code Files

- **Modify**: `routes/client.php` — add flipbook route
- **Modify**: `app/Http/Controllers/Client/DichVuKhachHang/CatalogController.php` — add `read()` method
- **Create**: `resources/views/clients/dich-vu-khach-hang/flipbook.blade.php`
- **Modify**: `resources/views/clients/dich-vu-khach-hang/partials/customer-service/catalog-content.blade.php` — replace `url()` with `route()`

## Implementation Steps

### Step 1: Register Route

File: `routes/client.php`

Add inside the `dich-vu` prefix group, after the existing `tai-catalog` route:

```php
Route::prefix('dich-vu')->name('dich-vu.')->group(function () {
    // ... existing routes ...
    Route::get('/tai-catalog', [CatalogController::class, 'index'])->name('tai-catalog');
    Route::get('/tai-catalog/doc/{id}', [CatalogController::class, 'read'])->name('tai-catalog.read');
    // ... rest of existing routes ...
});
```

Exact location: After line 74 (`Route::get('/tai-catalog', ...)`) in `routes/client.php`.

### Step 2: Add Controller Method

File: `app/Http/Controllers/Client/DichVuKhachHang/CatalogController.php`

Add `read()` method after `index()`:

```php
public function read($id)
{
    $catalog = Catalog::query()->findOrFail($id);

    if (! $catalog->file) {
        abort(404);
    }

    return view('clients.dich-vu-khach-hang.flipbook', compact('catalog'));
}
```

Full controller after changes:

```php
<?php

namespace App\Http\Controllers\Client\DichVuKhachHang;

use App\Http\Controllers\Controller;
use App\Models\Catalog;

class CatalogController extends Controller
{
    public function index()
    {
        $allCatalogs = Catalog::query()->latest()->get();

        $featuredCatalog = $allCatalogs->first();
        $catalogs = $allCatalogs->slice(1)->values();

        return view('clients.dich-vu-khach-hang.tai-catalog', compact('featuredCatalog', 'catalogs'));
    }

    public function read($id)
    {
        $catalog = Catalog::query()->findOrFail($id);

        if (! $catalog->file) {
            abort(404);
        }

        return view('clients.dich-vu-khach-hang.flipbook', compact('catalog'));
    }
}
```

### Step 3: Create Flipbook View

File: `resources/views/clients/dich-vu-khach-hang/flipbook.blade.php` (NEW)

```blade
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $catalog->tieu_de ?? 'Catalog' }} — Gốm sứ Thanh Hải</title>

  {{-- PDF.js --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
  {{-- StPageFlip --}}
  <script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background: #1a1a2e;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      min-height: 100vh; overflow-x: hidden;
      font-family: system-ui, -apple-system, sans-serif;
    }
    .back-link {
      position: fixed; top: 16px; left: 16px; z-index: 100;
      color: #fff; text-decoration: none; font-size: 14px;
      padding: 8px 16px; border: 1px solid rgba(255,255,255,0.3); border-radius: 4px;
      transition: all 0.2s;
    }
    .back-link:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.6); }
    .spinner-overlay {
      position: fixed; inset: 0; z-index: 200;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      background: #1a1a2e; color: #fff; gap: 16px;
    }
    .spinner {
      width: 40px; height: 40px;
      border: 3px solid rgba(255,255,255,0.2);
      border-top-color: #fff; border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    #flipbook {
      margin: 20px auto;
      overflow: visible;
    }
    .my-page {
      background: #fff;
      box-shadow: 0 0 12px rgba(0,0,0,0.3);
    }
    .my-page canvas {
      display: block;
      width: 100%; height: 100%;
    }
  </style>
</head>
<body>

<a href="{{ route('client.dich-vu.tai-catalog') }}" class="back-link">&larr; Quay lại</a>

<div class="spinner-overlay" id="spinner">
  <div class="spinner"></div>
  <p>Đang tải catalog...</p>
</div>

<div id="flipbook"></div>

<script>
  document.addEventListener('DOMContentLoaded', async function() {
    const pdfUrl = "{{ asset('storage/' . $catalog->file) }}";
    const flipbookEl = document.getElementById('flipbook');
    const spinner = document.getElementById('spinner');

    try {
      // 1. Tải PDF Document
      const pdf = await pdfjsLib.getDocument(pdfUrl).promise;
      const totalPages = pdf.numPages;

      // 2. Tạo nhanh các DOM rỗng để init Book ngay lập tức
      const pageDivs = [];
      for (let i = 1; i <= totalPages; i++) {
        const pageDiv = document.createElement('div');
        pageDiv.className = 'my-page';
        pageDiv.dataset.pageNumber = i; // Đánh dấu số trang
        flipbookEl.appendChild(pageDiv);
        pageDivs.push(pageDiv);
      }

      // 3. Khởi tạo hiệu ứng Flipbook
      const pageFlip = new St.PageFlip(flipbookEl, {
        width: 500,
        height: 700,
        size: 'stretch',
        minWidth: 315,
        maxWidth: 1000,
        minHeight: 420,
        maxHeight: 1350,
        showCover: true,
        mobileScrollSupport: false,
        usePortrait: window.innerWidth < 768,
      });
      pageFlip.loadFromHTML(document.querySelectorAll('.my-page'));
      
      // Ẩn spinner ngay khi sách hiện ra
      spinner.style.display = 'none';

      // 4. Render Canvas ngầm (Background Task)
      for (let i = 1; i <= totalPages; i++) {
        pdf.getPage(i).then(page => {
          const viewport = page.getViewport({ scale: 1.5 });
          const canvas = document.createElement('canvas');
          canvas.width = viewport.width;
          canvas.height = viewport.height;
          
          const ctx = canvas.getContext('2d');
          page.render({ canvasContext: ctx, viewport: viewport }).promise.then(() => {
             // Append canvas vào đúng div của trang đó
             pageDivs[i-1].appendChild(canvas);
          });
        });
      }

    } catch (err) {
      spinner.innerHTML = '<p style="color: #f87171;">Không thể tải catalog. Vui lòng thử lại sau.</p>';
      console.error('Flipbook error:', err);
    }
  });
</script>

</body>
</html>
```

**Key design decisions:**
- Standalone HTML page (no `@extends`) — full-screen immersive reading experience
- `viewport = page.getViewport({ scale: 1.5 })` — balance between quality (higher scale = sharper text) and performance
- `usePortrait: window.innerWidth < 768` — single page on mobile, double spread on desktop
- `size: 'stretch'` — allows flipbook to scale within min/max bounds for responsive behavior
- `showCover: true` — first page displayed as single-page cover

### Step 4: Wire Catalog Links (Integration Cleanup)

File: `resources/views/clients/dich-vu-khach-hang/partials/customer-service/catalog-content.blade.php`

Replace the `url()` helpers (from Phase 2) with `route()` helper:

**In Featured section**, change:
```blade
<a href="{{ url('/dich-vu/tai-catalog/doc/' . $featuredCatalog->catalog_id) }}"
```
To:
```blade
<a href="{{ route('client.dich-vu.tai-catalog.read', $featuredCatalog->catalog_id) }}"
```

**In Grid loop**, change:
```blade
<a href="{{ url('/dich-vu/tai-catalog/doc/' . $item->catalog_id) }}"
```
To:
```blade
<a href="{{ route('client.dich-vu.tai-catalog.read', $item->catalog_id) }}"
```

This is a simple find-and-replace operation — 2 occurrences total.

## Success Criteria

- [ ] Route `/dich-vu/tai-catalog/doc/{id}` returns 200 for valid catalog with PDF file
- [ ] Route returns 404 for non-existent catalog ID or catalog without PDF file
- [ ] PDF renders all pages correctly (verify with a test PDF)
- [ ] Page-flip animation works: clicking/dragging page corners triggers flip
- [ ] Desktop: 2-page spread visible (landscape book layout)
- [ ] Mobile (<768px): single page display (portrait mode)
- [ ] Back button returns to catalog list page
- [ ] Loading spinner visible during PDF rendering, hidden on completion
- [ ] Error state shown if PDF fails to load
- [ ] "Xem chi tiết" buttons in catalog-content.blade.php use `route()` helper (clean URLs)
- [ ] No console errors from PDF.js or StPageFlip

## Risk Assessment

| Risk | Mitigation |
|------|-----------|
| Large PDFs (100+ pages) cause performance issues | `scale: 1.5` balanced; consider `scale: 1.0` for very large PDFs |
| CDN scripts unavailable (offline/blocked) | Flipbook fails gracefully — error message shown in spinner overlay |
| PDF.js version 2.16.105 API compatibility | Pinned version in CDN URL; API is stable |
| StPageFlip CSS conflicts with existing styles | Standalone page with no shared CSS — zero conflict chance |
| CORS issues loading PDF from storage | Laravel's `storage/` serves from same origin — no CORS problem |
| `usePortrait` based on initial load window width | Page reload needed if user rotates mobile device; acceptable for v1 |
| Phase 2 already wrote `url()` links | Step 4 replaces them with `route()` — simple string replacement |
