---
phase: 2
title: "Client Index Page"
status: pending
priority: P1
effort: "2h"
dependencies: []
---

# Phase 2: Client Index Page (Dynamic)

## Overview

Dynamic hóa trang `/du-an`: thay thế HTML tĩnh trong `list-filters`, `list-grid`, `list-pagination` bằng Blade template động, giữ nguyên 100% Tailwind class và AOS animation.

## Requirements

- Functional:
  - Mobile filter: `<select>` dropdown render từ danh sách danh mục active, `onchange` redirect
  - Desktop filter: tabs `<a>` link với active state (underline `#c76e00`) dựa trên `?category=slug`
  - Grid: `@foreach($projects)` render card dự án với ảnh đại diện, tiêu đề, địa điểm, sản phẩm
  - Pagination: custom pagination giữ nguyên HTML/CSS, dùng `->links()` với custom view
  - Filter category qua query string `?category=slug`, SEO-friendly
  - Link mỗi project card → `route('client.projects.detail', $project->slug)`
- Non-functional: Không thay đổi class Tailwind, AOS attributes, cấu trúc HTML

## Architecture

```
GET /du-an?category={slug}&page={n}
  │
ProjectController::index()
  ├── DanhMucDuAn::where('is_delete', 0)->get()     → $categories
  ├── DuAn::query()
  │   ├── when($categorySlug, filter by danh_muc relation)
  │   └── with('danhMuc')->latest()->paginate(8)      → $projects
  └── return view('clients.projects.index', compact('categories', 'projects'))
  │
View: clients/projects/index.blade.php (giữ nguyên layout wrapper)
  ├── partials/list-intro.blade.php      (giữ nguyên — text tĩnh)
  ├── partials/list-filters.blade.php    (→ dynamic)
  ├── partials/list-grid.blade.php       (→ dynamic)
  └── partials/list-pagination.blade.php (→ dynamic)
```

## Related Code Files

- Modify: `app/Http/Controllers/Client/ProjectController.php`
- Modify: `resources/views/clients/projects/partials/list-filters.blade.php`
- Modify: `resources/views/clients/projects/partials/list-grid.blade.php`
- Modify: `resources/views/clients/projects/partials/list-pagination.blade.php`
- Read for context: `resources/views/clients/projects/index.blade.php`, `resources/views/clients/projects/partials/list.blade.php`

## Implementation Steps

### Step 1: Update `ProjectController::index()`

```php
use App\Models\DanhMucDuAn;
use App\Models\DuAn;
use Illuminate\Support\Str;

public function index(Request $request)
{
    // Lấy toàn bộ danh mục đang active
    $categories = DanhMucDuAn::where('is_delete', 0)->get();
    
    $categorySlug = $request->query('category');
    $query = DuAn::with('danhMuc');

    if ($categorySlug) {
        // Tìm ID của danh mục có slug trùng với query
        $matchedCategory = $categories->first(fn($cat) => Str::slug($cat->ten_danh_muc) === $categorySlug);
        
        if ($matchedCategory) {
            $query->where('danh_muc_du_an_id', $matchedCategory->danh_muc_du_an_id);
        }
    }

    $projects = $query->latest()->paginate(8)->appends($request->query());
    
    return view('clients.projects.index', compact('categories', 'projects'));
}
```

### Step 2: Dynamic-ify `list-filters.blade.php`

**Mobile dropdown:**
```blade
<select class="w-full h-full pl-3 pr-8 border border-[#ACACAC] bg-transparent text-[#565656] text-[14px] font-archivo font-semibold uppercase appearance-none outline-none cursor-pointer"
        onchange="if(this.value) window.location.href=this.value">
  @foreach($categories as $cat)
    <option value="{{ route('client.projects.index', ['category' => \Illuminate\Support\Str::slug($cat->ten_danh_muc)]) }}"
            {{ request('category') == \Illuminate\Support\Str::slug($cat->ten_danh_muc) ? 'selected' : '' }}>
      {{ $cat->ten_danh_muc }}
    </option>
  @endforeach
</select>
```

**Desktop tabs:**
```blade
@foreach($categories as $cat)
  @php $catSlug = \Illuminate\Support\Str::slug($cat->ten_danh_muc); @endphp
  <a href="{{ route('client.projects.index', ['category' => $catSlug]) }}"
     class="text-base md:text-xl font-archivo font-semibold uppercase hover:text-secondary transition-colors
            {{ request('category') == $catSlug ? 'text-secondary relative after:content-[\'\'] after:absolute after:-bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-secondary' : 'text-primary' }}">
    {{ $cat->ten_danh_muc }}
  </a>
@endforeach
```

**Add "Tất cả" option** cho phép clear filter:
- Mobile: `<option value="{{ route('client.projects.index') }}">TẤT CẢ</option>`
- Desktop: `<a>` link không có `?category` param

### Step 3: Dynamic-ify `list-grid.blade.php`

Thay 8 project card tĩnh bằng `@foreach`:

```blade
@foreach($projects as $project)
  <a href="{{ route('client.projects.detail', $project->slug) }}"
     class="group block overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-500 bg-white">
    <div class="aspect-[4/3] overflow-hidden">
      <img src="{{ asset('storage/' . ($project->images[0] ?? 'assets/images/placeholder.jpg')) }}"
           alt="{{ $project->ten_du_an }}"
           class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
    </div>
    <div class="p-3 text-center bg-white">
      <h3 class="text-lg md:text-xl font-archivo font-extrabold text-primary mb-2 group-hover:text-secondary transition-colors">
        {{ \Illuminate\Support\Str::upper($project->ten_du_an) }}
      </h3>
      <p class="text-[15px] text-primary font-archivo font-medium">
        <span class="text-primary font-semibold">Địa điểm:</span> {{ $project->dia_diem }}
      </p>
      <p class="text-[15px] text-primary font-archivo font-medium">
        <span class="text-primary font-semibold">Sản phẩm:</span> {{ $project->san_pham }}
      </p>
    </div>
  </a>
@endforeach
```

**Empty state:** Nếu không có dự án nào → hiển thị message "Chưa có dự án nào trong danh mục này."

### Step 4: Dynamic-ify `list-pagination.blade.php`

Sử dụng `$projects->links()` với custom pagination view giữ nguyên HTML:

```blade
@if($projects->hasPages())
<div class="flex items-center justify-between gap-8 md:gap-12" data-aos="fade-up">
  {{-- Previous --}}
  @if($projects->onFirstPage())
    <span class="text-primary/30 cursor-not-allowed">...</span>
  @else
    <a href="{{ $projects->previousPageUrl() }}" class="text-primary hover:text-secondary transition-colors">
      <svg ...>...</svg>
    </a>
  @endif

  <div class="flex items-center gap-6">
    @foreach($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
      @if($page == $projects->currentPage())
        <span class="w-10 h-10 flex items-center justify-center text-sm font-archivo font-bold bg-white text-secondary rounded-full shadow-sm cursor-pointer border border-[#FFFAF3]">{{ $page }}</span>
      @else
        <a href="{{ $url }}" class="text-sm font-archivo font-bold text-primary hover:text-secondary cursor-pointer">{{ $page }}</a>
      @endif
    @endforeach
  </div>

  {{-- Next --}}
  ...
</div>
@endif
```

**Không cần tạo file pagination view riêng** — viết trực tiếp trong `list-pagination.blade.php` vì pagination này spec-specific, không tái sử dụng.

## Success Criteria

- [ ] `/du-an` render danh sách dynamic từ database
- [ ] Mobile dropdown filter hoạt động, redirect đúng URL có `?category=`
- [ ] Desktop tabs hiển thị active state (underline `#c76e00`) đúng với category hiện tại
- [ ] Grid render đủ 8 project/page, link đúng `/du-an/{slug}`
- [ ] Pagination hoạt động: prev/next, page numbers, active state
- [ ] Filter + pagination kết hợp đúng (category filter được giữ khi chuyển trang)
- [ ] 100% class Tailwind và AOS animation không đổi
- [ ] Empty state hiển thị khi không có dự án

## Risk Assessment

- **Filter by category name (LIKE) không chính xác:** Nếu cần filter chính xác, thêm cột `slug` vào `danh_muc_du_an` table. Hiện tại chưa có cột này → cần migration thêm `slug` vào `danh_muc_du_an`. Hoặc dùng `Str::slug($ten_danh_muc)` để so khớp gián tiếp.
- **Pagination mất query string:** Dùng `->appends($request->query())` để giữ `?category=` khi chuyển trang.
- **Ảnh đại diện rỗng:** Fallback `$project->images[0] ?? 'assets/images/placeholder.jpg'`
