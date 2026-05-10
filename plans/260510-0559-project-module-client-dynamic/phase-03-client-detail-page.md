---
phase: 3
title: "Client Detail Page"
status: completed
priority: P1
effort: "2h"
dependencies: []
---

# Phase 3: Client Detail Page (Dynamic)

## Overview

Dynamic hóa trang `/du-an/{slug}`: thay thế dữ liệu tĩnh trong `detail.blade.php` bằng dữ liệu từ database. Giữ nguyên layout hero banner, meta bar, gallery grid (desktop + mobile Swiper), CTA section.

## Requirements

- Functional:
  - Fetch `DuAn` theo slug, 404 nếu không tìm thấy
  - Hero banner: tiêu đề dynamic, breadcrumb dynamic
  - Meta bar: địa điểm, năm, sản phẩm từ DB
  - Gallery: render tất cả `$project->images` dạng grid desktop + Swiper mobile
  - GLightbox: click ảnh → xem phóng to, giữ nguyên data-gallery grouping
  - Related projects: cùng danh mục, exclude current
- Non-functional: Giữ nguyên CSS, AOS, GLightbox, Swiper JS hiện có

## Architecture

```
GET /du-an/{slug}
  │
ProjectController::detail($slug)
  ├── DuAn::where('slug', $slug)->with('danhMuc')->firstOrFail()
  ├── Related: DuAn::where('danh_muc_du_an_id', $project->danh_muc_du_an_id)
  │            ->where('du_an_id', '!=', $project->du_an_id)
  │            ->latest()->limit(4)->get()
  └── return view('clients.projects.detail', compact('project', 'relatedProjects'))

View: detail.blade.php
  ├── Hero Banner  → dynamic title + breadcrumb
  ├── Meta Bar     → dynamic dia_diem, nam, san_pham
  ├── Gallery      → dynamic grid + swiper
  ├── Related      → optional related projects
  └── CTA          → giữ nguyên (tĩnh)
```

## Related Code Files

- Modify: `app/Http/Controllers/Client/ProjectController.php`
- Modify: `resources/views/clients/projects/detail.blade.php`
- Read for context: `resources/views/clients/projects/partials/detail-hero.blade.php`, `detail-specs.blade.php`, `detail-gallery.blade.php`, `detail-related.blade.php`

## Implementation Steps

### Step 1: Update `ProjectController::detail()`

```php
use App\Models\DuAn;

public function detail($slug)
{
    $project = DuAn::where('slug', $slug)->with('danhMuc')->firstOrFail();
    
    $relatedProjects = DuAn::where('danh_muc_du_an_id', $project->danh_muc_du_an_id)
        ->where('du_an_id', '!=', $project->du_an_id)
        ->latest()
        ->limit(4)
        ->get();
    
    return view('clients.projects.detail', compact('project', 'relatedProjects'));
}
```

### Step 2: Dynamic-ify Hero Banner

```blade
<section class="relative w-full h-[300px] md:h-[380px] flex items-center justify-center overflow-hidden">
  <div class="absolute inset-0 z-0">
    <img src="{{ asset('storage/' . ($project->images[0] ?? 'assets/images/factory-01.jpg')) }}"
         alt="{{ $project->ten_du_an }}" class="w-full h-full object-cover"
         onerror="this.src='{{ asset('assets/images/factory-01.jpg') }}'" />
    <div class="absolute inset-0 bg-primary/60"></div>
  </div>
  
  <div class="relative z-10 text-center text-white px-4 pt-0 md:pt-12" data-aos="fade-up">
    <p class="text-[11px] md:text-xs font-bold uppercase tracking-widest text-white/70 mb-3">
      Dự Án Nổi Bật
    </p>
    <h1 class="text-2xl md:text-[36px] lg:text-[48px] font-arima font-medium leading-tight drop-shadow-lg mb-4">
      {{ $project->ten_du_an }}
    </h1>
    <p class="text-[11px] md:text-sm text-white/80 drop-shadow-md">
      <a href="{{ route('client.home') }}" class="hover:text-secondary transition-colors">Trang chủ</a>
      <!-- breadcrumb chevron -->
      <a href="{{ route('client.projects.index') }}" class="hover:text-secondary transition-colors">Dự án</a>
      <!-- breadcrumb chevron -->
      <span class="text-white/60">{{ $project->ten_du_an }}</span>
    </p>
  </div>
</section>
```

### Step 3: Dynamic-ify Meta Bar

```blade
<section class="bg-white border-b border-neutral-200">
  <div class="w-[85%] max-w-[1320px] mx-auto py-6 flex flex-wrap gap-x-10 gap-y-3 items-center" data-aos="fade-up">
    <div class="flex items-center gap-3">
      <span class="text-[11px] font-bold text-primary/50 uppercase tracking-widest">Địa điểm</span>
      <span class="w-px h-4 bg-primary/20"></span>
      <span class="text-sm font-arima text-primary font-medium">{{ $project->dia_diem }}</span>
    </div>
    @if($project->nam)
    <div class="flex items-center gap-3">
      <span class="text-[11px] font-bold text-primary/50 uppercase tracking-widest">Năm</span>
      <span class="w-px h-4 bg-primary/20"></span>
      <span class="text-sm font-arima text-primary font-medium">{{ $project->nam }}</span>
    </div>
    @endif
    <div class="flex items-center gap-3">
      <span class="text-[11px] font-bold text-primary/50 uppercase tracking-widest">Sản phẩm</span>
      <span class="w-px h-4 bg-primary/20"></span>
      <span class="text-sm font-arima text-primary font-medium">{{ $project->san_pham }}</span>
    </div>
  </div>
</section>
```

### Step 4: Dynamic-ify Gallery Grid

**Strategy:** Render mảng images thành grid layout giữ nguyên pattern hiện tại:
- Nếu >= 3 ảnh: Row 1 (2+1), Row 2 (4 equal), Row 3 (1+2)
- Nếu < 3 ảnh: Fallback grid 2-cols đơn giản
- Desktop: CSS grid với class gốc
- Mobile: Swiper carousel

```blade
<!-- Desktop Gallery -->
@if(count($project->images) >= 3)
  <!-- Row 1: 3 cols (2-col featured + 1-col side) -->
  <div class="hidden md:grid grid-cols-3 gap-3 mb-3" data-aos="fade-up">
    <a href="{{ asset('storage/' . $project->images[0]) }}" class="glightbox col-span-2 aspect-[16/9] overflow-hidden block group" data-gallery="project-gallery">
      <img src="{{ asset('storage/' . $project->images[0]) }}" alt="{{ $project->ten_du_an }} — ảnh 1"
           class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
    </a>
    <a href="{{ asset('storage/' . $project->images[1]) }}" class="glightbox overflow-hidden block group" 
       style="aspect-ratio: 16/9;" data-gallery="project-gallery">
      <img src="{{ asset('storage/' . $project->images[1]) }}" alt="{{ $project->ten_du_an }} — ảnh 2"
           class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
    </a>
  </div>

  <!-- Row 2: 4 equal cols (ảnh 3..6) -->
  @if(count($project->images) >= 7)
  <div class="hidden md:grid grid-cols-4 gap-3 mb-3" data-aos="fade-up" data-aos-delay="80">
    @for($i = 2; $i < min(count($project->images), 6); $i++)
      <a href="{{ asset('storage/' . $project->images[$i]) }}" class="glightbox aspect-square overflow-hidden block group" data-gallery="project-gallery">
        <img src="{{ asset('storage/' . $project->images[$i]) }}" alt="{{ $project->ten_du_an }} — ảnh {{ $i+1 }}"
             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
      </a>
    @endfor
  </div>
  @endif

  <!-- Row 3: reversed featured (if images left) -->
  @if(count($project->images) >= 6)
  <div class="hidden md:grid grid-cols-3 gap-3" data-aos="fade-up" data-aos-delay="160">
    @php $remaining = array_slice($project->images, min(count($project->images), 6)); @endphp
    ...
  </div>
  @endif
@else
  <!-- Simple grid for 1-2 images -->
  <div class="hidden md:grid grid-cols-2 gap-3" data-aos="fade-up">
    @foreach($project->images as $img)
      <a href="{{ asset('storage/' . $img) }}" class="glightbox aspect-[16/9] overflow-hidden block group" data-gallery="project-gallery">
        <img src="{{ asset('storage/' . $img) }}" alt="{{ $project->ten_du_an }}"
             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
      </a>
    @endforeach
  </div>
@endif

<!-- Mobile Swiper -->
<div class="md:hidden" data-aos="fade-up">
  <div class="swiper detail2-gallery-swiper relative">
    <div class="swiper-wrapper pb-10">
      @foreach($project->images as $img)
        <div class="swiper-slide h-[260px]">
          <a href="{{ asset('storage/' . $img) }}" class="glightbox h-full w-full block" data-gallery="project-gallery-mobile">
            <img src="{{ asset('storage/' . $img) }}" alt="{{ $project->ten_du_an }}"
                 class="w-full h-full object-cover" />
          </a>
        </div>
      @endforeach
    </div>
    <div class="swiper-pagination detail2-pagination flex justify-center !bottom-0 !mt-2"></div>
  </div>
</div>
```

### Step 5: Related Projects (Optional Enhancement)

Nếu có `$relatedProjects`:
```blade
@if($relatedProjects->isNotEmpty())
<section class="py-14 lg:py-20 bg-white">
  <div class="w-[90%] max-w-[1400px] mx-auto">
    <h2 class="text-[26px] md:text-[34px] font-arima font-medium text-primary text-center mb-10">Dự án tương tự</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      @foreach($relatedProjects as $related)
        <a href="{{ route('client.projects.detail', $related->slug) }}" class="group block overflow-hidden shadow-md hover:shadow-lg transition-shadow bg-white">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="{{ asset('storage/' . ($related->images[0] ?? 'assets/images/placeholder.jpg')) }}"
                 alt="{{ $related->ten_du_an }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
          </div>
          <div class="p-3 text-center">
            <h3 class="text-sm font-archivo font-extrabold text-primary group-hover:text-secondary transition-colors">{{ \Illuminate\Support\Str::upper($related->ten_du_an) }}</h3>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif
```

## Success Criteria

- [x] `/du-an/{slug}` hiển thị đúng dự án từ database
- [x] `/du-an/slug-khong-ton-tai` trả về 404
- [x] Hero banner có tiêu đề dynamic, background là ảnh đầu tiên của dự án
- [x] Breadcrumb hiển thị: Trang chủ > Dự án > {Tên dự án}
- [x] Meta bar hiển thị địa điểm, năm (nếu có), sản phẩm từ DB
- [x] Gallery desktop: grid layout giữ nguyên pattern (2+1, 4 equal, 1+2)
- [x] Gallery mobile: Swiper carousel với tất cả ảnh
- [x] GLightbox hoạt động trên tất cả ảnh (cả desktop và mobile)
- [x] Related projects hiển thị (nếu có dự án cùng danh mục)
- [x] 100% class Tailwind, AOS, JS không đổi

## Risk Assessment

- **Dự án có ít ảnh (< 3):** Fallback grid đơn giản 2-cols. Hero dùng ảnh đầu tiên hoặc placeholder.
- **Ảnh không tồn tại:** Dùng `onerror` fallback cho thẻ `<img>`.
- **Related projects rỗng:** Dùng `@if($relatedProjects->isNotEmpty())` để ẩn section.
- **Swiper không khởi tạo khi không có JS:** Component Swiper đã có sẵn trong `@push('scripts')`, chỉ cần dynamic-ify slides.
- **GLightbox conflict giữa tabs:** `data-gallery="project-gallery"` cho desktop, `"project-gallery-mobile"` cho mobile để tránh lẫn ảnh.
