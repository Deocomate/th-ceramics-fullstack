---
phase: 2
title: "Implement index() Logic and Update index Views"
status: completed
priority: P1
effort: "4h"
dependencies: [1]
---

# Phase 2: Implement index() Logic and Update index Views

## Overview

Implement `index()` in all 8 controllers to fetch data from services and pass to views. Then update each `index.blade.php` and its partials to render dynamic data — replacing hardcoded values with `{{ }}` expressions and `@foreach` loops. UI layout stays 100% intact.

## Common index() Pattern

Based on `NgoiAmDuongController::index()`:

```php
public function index()
{
    $config = $this->configService->getFirstRecord();
    $products = $this->productService->getAll('active');

    return view('clients.products.xxx.index', compact('config', 'products'));
}
```

## Per-Controller index() Implementation

### 2.1 DenGomSuController

```php
public function index()
{
    $config = $this->denGomSuService->getFirstRecord(); // includes anh() relation for gallery
    return view('clients.products.den-gom-su.index', compact('config'));
}
```

**View updates** (`den-gom-su/index.blade.php` + partials):
- Banner image: `Storage::url($config->thumbnail_main)` instead of `asset('assets/images/...')`
- Gallery images (relation `anh`): `@foreach($config->anh as $anh)` loop
- Title text from `$config->title1`, `$config->title2` if present in DB schema

### 2.2 GachCoBatTrangController

```php
public function index()
{
    $config = $this->gachCoBatTrangService->getFirstRecord();
    $products = $this->gachCoBatTrangCtService->getAll('active');
    $giaTriVuotTroi = $this->giaTriVuotTroiService->getAll();

    return view('clients.products.gach-co-bat-trang.index', compact('config', 'products', 'giaTriVuotTroi'));
}
```

**View updates** (`gach-co-bat-trang/index.blade.php`):
- Banner: `Storage::url($config->thumbnail_main)`
- Product cards loop: `@foreach($products as $product)` replacing hardcoded `@for` loops
  - Image: `Storage::url($product->images[0] ?? '')` (first image from JSON array)
  - Name: `{{ $product->name }}`
  - Code: `{{ $product->code }}`
  - Price: `{{ number_format($product->price) }}đ/viên`
  - Link: `{{ route('client.products.gach-co-bat-trang.detail', $product->gach_co_bat_trang_ct_id) }}`
- Product info partial: pass dynamic `$product->size`, `$product->des[]` instead of hardcoded `tableRows`
- Video: `{{ $config->video }}` (YouTube URL)
- Cong doan (process) images: `@foreach($config->images as $img)` if `images` is JSON array on config table

### 2.3 GachHoaThongGioController

```php
public function index()
{
    $config = $this->gachHoaThongGioService->getFirstRecord(); // includes anh() and giaTri() relations
    $products = $this->gachHoaThongGioCtService->getAll('active');

    return view('clients.products.gach-hoa-thong-gio.index', compact('config', 'products'));
}
```

**View updates** (`gach-hoa-thong-gio/index.blade.php` + 7 partials):
- Hero background: `Storage::url($config->image)` (column name is `image` per model)
- Video: `{{ $config->video }}`
- Process section (`images` JSON): `@foreach($config->images as $img)`
- Value section: `@foreach($config->giaTri as $giaTri)` — relation `giaTri()` on model
- Gallery section: `@foreach($config->anh as $anh)` — relation `anh()` on model
- Product cards: `@foreach($products as $product)` with same pattern as GachCoBatTrang

### 2.4 GachTrangTriController

```php
public function index()
{
    $config = $this->gachTrangTriService->getFirstRecord(); // includes dauAn() relation
    $products = $this->gachTrangTriCtService->getAll('active');

    return view('clients.products.gach-trang-tri.index', compact('config', 'products'));
}
```

**View updates** (`gach-trang-tri/index.blade.php`):
- Banner: `Storage::url($config->thumbnail_main)`
- Dau an section: `@foreach($config->dauAn as $dauAn)`
- Product cards: `@foreach($products as $product)` with same pattern

### 2.5 LanCanGomSuController

```php
public function index()
{
    $config = $this->lanCanGomXuService->getFirstRecord();

    return view('clients.products.lan-can-gom-su.index', compact('config'));
}
```

**View updates** (`lan-can-gom-su/index.blade.php` + partials):
- Banner image: `Storage::url($config->thumbnail_main)`
- Video: `{{ $config->video }}`
- No product CT table — only config data

### 2.6 LinhVatPhongThuyController

```php
public function index()
{
    $config = $this->linhVatPhongThuyService->getFirstRecord(); // includes linhVat() and anh() relations
    $products = $this->linhVatPhongThuyCtService->getAll('active');

    return view('clients.products.linh-vat-phong-thuy.index', compact('config', 'products'));
}
```

**View updates** (`linh-vat-phong-thuy/index.blade.php` + 3 partials):
- Banner: `Storage::url($config->thumbnail_main)`
- Linh vat collection: `@foreach($config->linhVat as $linhVat)`
  - Image: `Storage::url($linhVat->image)`
  - Title: `{{ $linhVat->title }}`
  - Description: `{{ $linhVat->description }}` (check exact column name)
- Product cards: `@foreach($products as $product)` with same pattern

### 2.7 NgoiHaiVanMieuController

```php
public function index()
{
    $config = $this->ngoiHaiVanMieuService->getFirstRecord();
    $products = $this->ngoiHaiVanMieuCtService->getAll('active');

    return view('clients.products.ngoi-hai-van-mieu.index', compact('config', 'products'));
}
```

**View updates** (`ngoi-hai-van-mieu/index.blade.php`):
- Banner thumbnails: `Storage::url($config->thumbnail_main)`, `Storage::url($config->thumbnail1/2/3)` (check exact column names)
- Titles: `{{ $config->title1 }}`, `{{ $config->title2 }}`, `{{ $config->title3 }}`
- Video: `{{ $config->video }}`
- Cong doan images: `@foreach($config->images as $img)`
- Product cards: `@foreach($products as $product)` with same pattern
  - Note: `NgoiHaiVanMieuCt` has no `code` column — code is on `mauSacs` relation. Skip code display or use first mauSac code.

### 2.8 PhuKienNgoiController

```php
public function index()
{
    $config = $this->phuKienNgoiService->getFirstRecord();
    $ngoiBoNocProducts = $this->ngoiBoNocCtService->getAll('active');
    $boNocChuVanProducts = $this->boNocChuVanCtService->getAll('active');

    return view('clients.products.phu-kien-ngoi.index', compact(
        'config', 'ngoiBoNocProducts', 'boNocChuVanProducts'
    ));
}
```

**View updates** (`phu-kien-ngoi/index.blade.php` + 2 partials):
- Banner: `Storage::url($config->thumbnail_main)` (check column name)
- Gallery images (JSON): `@foreach($config->images as $img)`
- Two product sections:
  - Ngói bộ nóc: `@foreach($ngoiBoNocProducts as $product)`
  - Bộ nóc chữ vạn: `@foreach($boNocChuVanProducts as $product)`

## Blade Expression Reference

| Static content | Dynamic replacement |
|---|---|
| `src="{{ asset('assets/images/xxx.png') }}"` | `src="{{ Storage::url($config->thumbnail_main) }}"` |
| `Gạch Bát 300x300x50` | `{{ $product->name }}` |
| `75.000 đ/viên` | `{{ number_format($product->price) }}đ/viên` |
| `MSP: GB-300-50` | `MSP: {{ $product->code }}` |
| `href="./detail-xxx.html"` | `href="{{ route('client.products.xxx.detail', $product->xxx_id) }}"` |
| Hardcoded `@for` loop | `@foreach($products as $product)` |
| `<img src="...gach-bat-01.jpg">` (product card) | `<img src="{{ Storage::url($product->images[0] ?? '') }}">` |
| YouTube video URL string | `{{ $config->video }}` |

## JSON Array Columns in Views

Models cast these to `array` automatically. In Blade, use standard `@foreach`:

```blade
@foreach($product->des as $line)
    <p>{{ $line }}</p>
@endforeach

@foreach($product->images as $img)
    <img src="{{ Storage::url($img) }}" />
@endforeach
```

## Related Code Files

- **Modify (8 controllers):** Same as Phase 1
- **Modify (8+ index views):**
  - `resources/views/clients/products/den-gom-su/index.blade.php`
  - `resources/views/clients/products/gach-co-bat-trang/index.blade.php`
  - `resources/views/clients/products/gach-hoa-thong-gio/index.blade.php`
  - `resources/views/clients/products/gach-trang-tri/index.blade.php`
  - `resources/views/clients/products/lan-can-gom-su/index.blade.php`
  - `resources/views/clients/products/linh-vat-phong-thuy/index.blade.php`
  - `resources/views/clients/products/ngoi-hai-van-mieu/index.blade.php`
  - `resources/views/clients/products/phu-kien-ngoi/index.blade.php`
- **Modify (partials):** All `partials/` subdirectories under the above that contain hardcoded data

## Implementation Steps

1. Implement `index()` in each of the 8 controllers following the mapping above
2. For each index view, identify hardcoded data (names, prices, image paths, product cards)
3. Replace with dynamic Blade expressions (see reference table)
4. For product card sliders, replace `@for` loops with `@foreach` over `$products`
5. For JSON array fields, use `@foreach` over the casted array property
6. Handle edge case: empty `$products` collection — Blade `@forelse` / `@empty` or check with `@if($products->count())`
7. Handle edge case: `$config->getFirstRecord()` returns null if DB has no row — consider `first()` + null check vs `firstOrFail()`

## Success Criteria

- [x] All 8 `index()` methods fetch and pass data to views
- [x] All index views render dynamic data from `$config`, `$products`, etc.
- [x] Product names, prices, codes, images come from DB not hardcoded strings
- [x] UI layout unchanged — same CSS classes, same section structure, same responsive design
- [x] Pages don't crash when accessed via browser (no 500 errors)
- [x] Empty state handled gracefully (no products in DB, no config row)

## Risk Assessment

- **Risk:** Config row doesn't exist in DB → `firstOrFail()` throws 404 on index page. **Mitigation:** Use `first()` + null check, or ensure seeders populate config tables.
- **Risk:** Column name mismatch between spec and actual DB. **Mitigation:** Cross-reference with model `$fillable` and migration files before writing Blade expressions.
- **Risk:** `Storage::url()` returns wrong path for local disks. **Mitigation:** Verify `FILESYSTEM_DISK` config and run `php artisan storage:link` if needed.
