---
phase: 2
title: "Dynamic Catalog List"
status: completed
priority: P1
effort: "45m"
dependencies: []
---

# Phase 2: Dynamic Catalog List

## Overview

Wire `CatalogController::index()` to query `Catalog` model, split into featured (first item) + grid (remaining items), and pass to `catalog-content.blade.php`. Replace static featured + 6 grid items with dynamic `@foreach` loops. All Tailwind classes preserved exactly — only swap placeholder gray boxes with real `<img>` tags and static text with `{{ }}` bindings.

## Requirements

- Functional: First catalog record shown as large featured item
- Functional: Remaining records shown in 3-column grid (2-column on mobile)
- Functional: Each item shows thumbnail (`anh_dai_dien`), title (`tieu_de`), and "Xem chi tiết" button
- Functional: "Xem chi tiết" links to flipbook reader (Phase 3) when `file` exists, otherwise disabled button
- Non-functional: Zero Tailwind class changes — only Blade directives + `{{ }}`
- Non-functional: Empty state when no records exist

## Architecture

```
CatalogController::index()
  → Catalog::query()->latest()->get()
  → $featuredCatalog = $catalogs->shift()  (first item)
  → view('clients.dich-vu-khach-hang.tai-catalog', [
      'featuredCatalog' => $featuredCatalog,
      'catalogs' => $catalogs
    ])
    → catalog-content.blade.php (@if featured + @foreach grid)
```

## Related Code Files

- **Modify**: `app/Http/Controllers/Client/DichVuKhachHang/CatalogController.php`
- **Modify**: `resources/views/clients/dich-vu-khach-hang/partials/customer-service/catalog-content.blade.php`

## Implementation Steps

### Step 1: Update Controller

File: `app/Http/Controllers/Client/DichVuKhachHang/CatalogController.php`

Replace the empty stub:

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
}
```

### Step 2: Dynamic View

File: `resources/views/clients/dich-vu-khach-hang/partials/customer-service/catalog-content.blade.php`

Keep the opening `<div class="flex-1 lg:pl-12">` with h1 title and description paragraph exactly as-is.

**Featured Section** — Replace the static featured block:

```blade
@if($featuredCatalog)
  <div class="flex flex-col lg:flex-row gap-6 lg:gap-24 bg-transparent mb-16 lg:mb-12">
    <div class="w-full lg:w-[480px] aspect-[1/1.1] bg-[#D9D9D9] rounded-sm flex-shrink-0">
      <img src="{{ asset('storage/' . $featuredCatalog->anh_dai_dien) }}" alt="{{ $featuredCatalog->tieu_de }}" class="w-full h-full object-cover rounded-sm" />
    </div>
    <div class="flex flex-col justify-end">
      <div class="flex items-center gap-3 mt-3 lg:mt-0 mb-2.5">
        <h3 class="text-sm lg:text-base font-semibold text-primary font-archivo">{{ $featuredCatalog->tieu_de }}</h3>
      </div>
      <div>
        @if($featuredCatalog->file)
          <a href="{{ route('client.dich-vu.tai-catalog.read', $featuredCatalog->catalog_id) }}"
            class="flex items-center font-extralight justify-center lg:justify-between gap-4 px-2 py-1.5 border border-primary text-primary text-xs lg:text-sm hover:bg-primary hover:text-white transition-all w-fit min-w-[97px] lg:min-w-[110px]"
          >
            <span class="font-archivo">Xem chi tiết</span>
            <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1]" />
          </a>
        @else
          <span
            class="flex items-center font-extralight justify-center lg:justify-between gap-4 px-2 py-1.5 border border-primary/30 text-primary/30 text-xs lg:text-sm cursor-not-allowed w-fit min-w-[97px] lg:min-w-[110px]"
          >
            <span class="font-archivo">Xem chi tiết</span>
            <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1]" />
          </span>
        @endif
      </div>
    </div>
  </div>
@endif
```

**Grid Section** — Replace all 6 static items with a loop:

```blade
<div class="grid grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-16 lg:gap-12">
  @forelse($catalogs as $item)
    <div class="flex flex-col">
      <div class="aspect-[1/1.1] bg-[#D9D9D9] rounded-sm mb-4 lg:mb-6">
        <img src="{{ asset('storage/' . $item->anh_dai_dien) }}" alt="{{ $item->tieu_de }}" class="w-full h-full object-cover rounded-sm" />
      </div>
      <div class="flex items-center gap-3 mb-2.5">
        <h3 class="text-sm lg:text-base font-semibold text-primary font-archivo">{{ $item->tieu_de }}</h3>
      </div>
      <div>
        @if($item->file)
          <a href="{{ route('client.dich-vu.tai-catalog.read', $item->catalog_id) }}"
            class="flex items-center font-extralight justify-center lg:justify-between gap-4 px-2 py-1.5 border border-primary text-primary text-xs lg:text-sm hover:bg-primary hover:text-white transition-all w-fit min-w-[97px] lg:min-w-[110px]"
          >
            <span class="font-archivo">Xem chi tiết</span>
            <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1]" />
          </a>
        @else
          <span
            class="flex items-center font-extralight justify-center lg:justify-between gap-4 px-2 py-1.5 border border-primary/30 text-primary/30 text-xs lg:text-sm cursor-not-allowed w-fit min-w-[97px] lg:min-w-[110px]"
          >
            <span class="font-archivo">Xem chi tiết</span>
            <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1]" />
          </span>
        @endif
      </div>
    </div>
  @empty
    <div class="col-span-full text-center py-16">
      <p class="text-primary/60 text-lg font-archivo">Đang cập nhật catalog...</p>
    </div>
  @endforelse
</div>
```

**Full file structure after changes:**

```blade
<div class="flex-1 lg:pl-12">
  <h1 class="text-[30px] lg:text-[36px] font-arima font-medium text-primary mb-6 lg:mt-[-6px]">Tải Catalog</h1>

  <p class="text-primary/80 text-base leading-[28px] mb-10 max-w-[1000px] font-archivo">
    Để quý khách dễ dàng lựa chọn giải pháp tối ưu, Gốm sứ Thanh Hải trân trọng giới thiệu hệ thống catalog chuyên biệt theo từng
    dòng sản phẩm từ ngói lợp, gạch trang trí đến gốm phong thủy. Bên cạnh đó, bộ catalog tổng thể sẽ giúp quý vị có cái nhìn toàn
    diện nhất về hệ sinh thái sản phẩm đa dạng mà chúng tôi cung cấp.
  </p>

  <div class="space-y-10 lg:space-y-12">
    {{-- Featured Catalog --}}
    @if($featuredCatalog)
      {{-- featured block as above --}}
    @endif

    {{-- Catalog Grid --}}
    {{-- grid with @forelse loop as above --}}
  </div>
</div>
```

### Step 3: Handle Phase 3 Dependency

The `route('client.dich-vu.tai-catalog.read', $item->catalog_id)` reference requires Phase 3's route to exist. For parallel execution:

- If Phase 3 executes first: route exists, links work immediately
- If Phase 2 executes first: the route name `client.dich-vu.tai-catalog.read` won't exist yet, causing `Route [client.dich-vu.tai-catalog.read] not defined` error

**Mitigation for parallel safety**: Use a conditional approach. Instead of `route()`, use a URL helper that falls back gracefully:

```blade
{{-- In featured section: --}}
@if($featuredCatalog->file)
  <a href="{{ url('/dich-vu/tai-catalog/doc/' . $featuredCatalog->catalog_id) }}"
    class="flex items-center ...">
    <span class="font-archivo">Xem chi tiết</span>
    ...
  </a>
@endif

{{-- In grid loop: --}}
@if($item->file)
  <a href="{{ url('/dich-vu/tai-catalog/doc/' . $item->catalog_id) }}"
    class="flex items-center ...">
    <span class="font-archivo">Xem chi tiết</span>
    ...
  </a>
@endif
```

This uses `url()` (always works) instead of `route()` (requires route definition). Phase 3's Task 3.3 will replace `url()` with `route()` for consistency.

## Success Criteria

- [ ] Visiting `/dich-vu/tai-catalog` renders all catalog records from DB
- [ ] First catalog displayed as large featured item (if any exist)
- [ ] Remaining catalogs displayed in responsive grid
- [ ] "Xem chi tiết" links use `url()` pointing to `/dich-vu/tai-catalog/doc/{id}`
- [ ] Items without `file` show disabled button (muted colors, no link)
- [ ] Empty state "Đang cập nhật catalog..." shown when no records
- [ ] All Tailwind classes, grid structure, responsive breakpoints preserved
- [ ] Thumbnails load from `storage/catalog/images/`

## Risk Assessment

| Risk | Mitigation |
|------|-----------|
| `route()` fails if Phase 3 not yet done | Use `url()` helper instead — route-independent |
| `bg-[#D9D9D9]` placeholder visible behind image | `<img>` with `object-cover` fills container; `bg` acts as loading placeholder |
| `$featuredCatalog` is null (empty table) | `@if($featuredCatalog)` guard |
| First item also appears in grid | `slice(1)` in controller ensures it's removed from grid list |
| No images uploaded yet | Gray `bg-[#D9D9D9]` placeholder box remains visible, providing acceptable fallback |
