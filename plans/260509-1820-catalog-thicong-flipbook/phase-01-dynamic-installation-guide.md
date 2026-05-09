---
phase: 1
title: "Dynamic Installation Guide"
status: completed
priority: P1
effort: "30m"
dependencies: []
---

# Phase 1: Dynamic Installation Guide

## Overview

Wire `HuongDanThiCongController` to query `ThiCong` model and pass data to `installation-guide-content.blade.php`. Replace 8 static HTML rows with a `@foreach` loop. The design uses an alternating zigzag layout: odd rows (0-index: 0,2,4,6) are left-aligned text, even rows (1,3,5,7) are right-aligned text.

## Requirements

- Functional: Page displays all `thi_cong` records ordered by newest first
- Functional: Each row shows title (`tieu_de`), thumbnail image (`anh`), and YouTube link button (`link_youtube`)
- Functional: Alternating left/right text alignment preserved per original design
- Non-functional: Zero changes to HTML structure or Tailwind classes — only Blade directives
- Non-functional: Graceful empty state when no records exist

## Architecture

```
HuongDanThiCongController::index()
  → ThiCong::query()->latest()->get()
  → view('clients.dich-vu-khach-hang.huong-dan-thi-cong', ['guides' => $guides])
    → installation-guide-content.blade.php (@foreach with $loop->even)
```

## Related Code Files

- **Modify**: `app/Http/Controllers/Client/DichVuKhachHang/HuongDanThiCongController.php`
- **Modify**: `resources/views/clients/dich-vu-khach-hang/partials/customer-service/installation-guide-content.blade.php`

## Implementation Steps

### Step 1: Fix Controller

File: `app/Http/Controllers/Client/DichVuKhachHang/HuongDanThiCongController.php`

Replace the broken stub with:

```php
<?php

namespace App\Http\Controllers\Client\DichVuKhachHang;

use App\Http\Controllers\Controller;
use App\Models\ThiCong;

class HuongDanThiCongController extends Controller
{
    public function index()
    {
        $guides = ThiCong::query()->latest()->get();

        return view('clients.dich-vu-khach-hang.huong-dan-thi-cong', compact('guides'));
    }
}
```

Note: The current file has `reutrn` (typo) — full replace needed anyway.

### Step 2: Dynamic View

File: `resources/views/clients/dich-vu-khach-hang/partials/customer-service/installation-guide-content.blade.php`

**Approach**: Keep the header (h1 title + grid container opening), then replace all 8 static rows with a single `@foreach` loop. The original layout alternates between left-aligned and right-aligned text per row.

**Loop structure** (preserving exact Tailwind classes):

```blade
@forelse($guides as $guide)
  {{-- Left-aligned row (index 0, 2, 4, 6...) --}}
  @if($loop->even)
    <div class="bg-[#D9D9D9] w-full aspect-[3/2] lg:aspect-[9/6] order-{{ $loop->index + 1 }} md:order-{{ $loop->index + 2 }}">
      <img src="{{ asset('storage/' . $guide->anh) }}" alt="{{ $guide->tieu_de }}" class="w-full h-full object-cover" />
    </div>
    <div class="flex flex-col justify-center items-start px-0 lg:ml-8 lg:p-8 bg-transparent order-{{ $loop->index + 2 }} md:order-{{ $loop->index + 1 }} mt-5 lg:mt-0 mb-12 lg:mb-0">
      <h3 class="text-2xl lg:text-[32px] font-semibold text-primary mb-4 font-archivo leading-[32px]">{{ $guide->tieu_de }}</h3>
      @if($guide->link_youtube)
        <a href="{{ $guide->link_youtube }}" target="_blank" rel="noopener noreferrer"
          class="flex items-center justify-center lg:justify-between gap-2 px-2 py-1.5 border border-primary text-primary text-[12px] lg:text-[14px] font-extralight hover:bg-primary hover:text-white transition-all font-archivo w-[111px] h-[32px] lg:w-fit lg:h-auto lg:min-w-[140px]"
        >
          Xem hướng dẫn
          <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1] ml-4" />
        </a>
      @endif
    </div>
  @else
    {{-- Right-aligned row (index 1, 3, 5, 7...) --}}
    <div class="bg-[#D9D9D9] w-full aspect-[3/2] lg:aspect-[9/6] order-{{ $loop->index + 1 }}">
      <img src="{{ asset('storage/' . $guide->anh) }}" alt="{{ $guide->tieu_de }}" class="w-full h-full object-cover" />
    </div>
    <div class="flex flex-col justify-center items-end lg:mr-8 lg:p-8 bg-transparent text-right order-{{ $loop->index + 2 }} mt-5 lg:mt-0 mb-12 lg:mb-0">
      <h3 class="text-2xl lg:text-[32px] font-semibold text-primary mb-4 font-archivo leading-[32px]">{{ $guide->tieu_de }}</h3>
      @if($guide->link_youtube)
        <a href="{{ $guide->link_youtube }}" target="_blank" rel="noopener noreferrer"
          class="flex items-center justify-center lg:justify-between gap-2 px-2 py-1.5 border border-primary text-primary text-[12px] lg:text-[14px] font-extralight hover:bg-primary hover:text-white transition-all font-archivo w-[111px] h-[32px] lg:w-fit lg:h-auto lg:min-w-[140px]"
        >
          Xem hướng dẫn
          <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1] ml-4" />
        </a>
      @endif
    </div>
  @endif
@empty
  <div class="col-span-2 text-center py-16">
    <p class="text-primary/60 text-lg font-archivo">Đang cập nhật hướng dẫn thi công...</p>
  </div>
@endforelse
```

**IMPORTANT**: The original design uses manual `order-1` through `order-16` for an alternating zigzag layout. However, on mobile devices, maintaining a single-column layout requires the image to always be placed above the text. To ensure perfect mobile UX while retaining the desktop zigzag layout, we will keep a single consistent DOM structure (Image first, Text second) and only apply Tailwind's responsive `md:order-*` classes to conditionally swap the order on desktop.

**Simpler approach** (recommended): Keep a consistent DOM structure and use `md:order-*` classes based on `$loop->even`:

```blade
@forelse($guides as $guide)
  {{-- Khối bọc chung cho 1 item (để grid tự flow) --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-0 w-full mb-12 md:mb-0">
      
      {{-- PHẦN ẢNH: Luôn nằm trên cùng ở Mobile. Trên Desktop sẽ đảo trái/phải --}}
      <div class="bg-[#D9D9D9] w-full aspect-[3/2] lg:aspect-[9/6] {{ $loop->even ? 'md:order-2' : 'md:order-1' }}">
        <img src="{{ asset('storage/' . $guide->anh) }}" alt="{{ $guide->tieu_de }}" class="w-full h-full object-cover" />
      </div>

      {{-- PHẦN TEXT: Luôn nằm dưới Ảnh ở Mobile. --}}
      <div class="flex flex-col justify-center px-0 mt-5 md:mt-0 {{ $loop->even ? 'md:order-1 items-start md:mr-8 lg:p-8' : 'md:order-2 items-end md:ml-8 lg:p-8 text-right' }}">
        <h3 class="text-2xl lg:text-[32px] font-semibold text-primary mb-4 font-archivo leading-[32px]">
            {{ $guide->tieu_de }}
        </h3>
        
        @if($guide->link_youtube)
          <a href="{{ $guide->link_youtube }}" target="_blank" rel="noopener noreferrer"
            class="flex items-center justify-center lg:justify-between gap-2 px-2 py-1.5 border border-primary text-primary text-[12px] lg:text-[14px] font-extralight hover:bg-primary hover:text-white transition-all font-archivo w-[111px] h-[32px] lg:w-fit lg:h-auto lg:min-w-[140px]">
            Xem hướng dẫn
            <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1] ml-4" />
          </a>
        @endif
      </div>

  </div>
@empty
  <div class="col-span-1 md:col-span-2 text-center py-16">
    <p class="text-primary/60 text-lg font-archivo">Đang cập nhật hướng dẫn thi công...</p>
  </div>
@endforelse
```

The full file structure:
```
<div class="flex-1 lg:pl-12">
  <h1 ...>Hướng dẫn thi công</h1>
  <div class="grid grid-cols-1 md:grid-cols-2 w-full gap-y-0">
    @forelse($guides as $guide)
      {{-- alternating rows as above --}}
    @empty
      {{-- empty state --}}
    @endforelse
  </div>
</div>
```

## Success Criteria

- [ ] Visiting `/dich-vu/huong-dan-thi-cong` renders all `thi_cong` records
- [ ] Thumbnail images display using `asset('storage/' . $guide->anh)`
- [ ] YouTube links render as `<a>` with `target="_blank"` when `link_youtube` is non-null
- [ ] Rows alternate left/right alignment matching original design
- [ ] Empty state message shown when no records in DB
- [ ] No broken HTML, all Tailwind classes preserved
- [ ] No `reutrn` typo remaining in controller

## Risk Assessment

| Risk | Mitigation |
|------|-----------|
| Removing `order-*` classes changes layout | Test on desktop + mobile after change. If broken, add inline `style="order: {{ $loop->iteration }}"` |
| Images may not exist in storage | Browser handles broken images gracefully; `alt` text provides fallback |
| No data in `thi_cong` table | `@forelse`/`@empty` handles this |
