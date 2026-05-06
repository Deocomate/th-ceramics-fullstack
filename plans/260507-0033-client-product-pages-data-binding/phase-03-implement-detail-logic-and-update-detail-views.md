---
phase: 3
title: "Implement detail() Logic and Update detail Views"
status: completed
priority: P1
effort: "4h"
dependencies: [1]
---

# Phase 3: Implement detail() Logic and Update detail Views

## Overview

Implement `detail($id)` in all 8 controllers to fetch single product + related data. Update each `detail.blade.php` to render dynamic product info, color palette (where applicable), construction norms (dinhMuc), and related products. UI layout stays 100% intact.

## Common detail() Pattern

Based on `NgoiAmDuongController::detail()`:

```php
public function detail($id)
{
    $product = $this->productService->findById($id);

    if ($product->is_delete == 1) {
        abort(404);
    }

    $relatedProducts = $this->productService->getAll('active')
        ->where('xxx_id', '!=', $id)
        ->take(4);

    return view('clients.products.xxx.detail', compact('product', 'relatedProducts'));
}
```

## Per-Controller detail() Implementation

### 3.1 DenGomSuController

**No CT table exists** — `detail($id)` returns static view or aborts.

```php
public function detail($id)
{
    // No product detail table exists. Keep static view for SEO/demo.
    return view('clients.products.den-gom-su.detail', compact('id'));
}
```

**View updates:** Keep as-is (no dynamic data available). See Phase 4 for alternative handling.

### 3.2 GachCoBatTrangController

```php
public function detail($id)
{
    $product = $this->gachCoBatTrangCtService->findById($id);

    if ($product->is_delete == 1) {
        abort(404);
    }

    $dinhMuc = $this->dinhMucGachCoBatTrangService->getAll();

    $relatedProducts = $this->gachCoBatTrangCtService->getAll('active')
        ->where('gach_co_bat_trang_ct_id', '!=', $id)
        ->take(4);

    return view('clients.products.gach-co-bat-trang.detail', compact(
        'product', 'dinhMuc', 'relatedProducts'
    ));
}
```

**View updates** (`gach-co-bat-trang/detail.blade.php`):
- Product detail container: Pass dynamic values
  - `title="{{ $product->name }}"`
  - `price="{{ number_format($product->price) }}đ/m2"` (or per unit depending on product type)
- Gallery: `@foreach($product->images as $img)` replacing hardcoded images
- Specifications: `{{ $product->size }}`, `@foreach($product->des as $line)`
- Size image: `Storage::url($product->size_image)`
- Dinh muc table: `@foreach($dinhMuc as $dm)` with `{{ $dm->brick_type }}`, `{{ $dm->rate }}`, etc.
- Related products: `@foreach($relatedProducts as $rel)` in slider/cards

### 3.3 GachHoaThongGioController

```php
public function detail($id)
{
    $product = $this->gachHoaThongGioCtService->findById($id);

    if ($product->is_delete == 1) {
        abort(404);
    }

    $dinhMuc = $this->dinhMucGachHoaThongGioService->getAll();

    $relatedProducts = $this->gachHoaThongGioCtService->getAll('active')
        ->where('gach_hoa_thong_gio_ct_id', '!=', $id)
        ->take(4);

    return view('clients.products.gach-hoa-thong-gio.detail', compact(
        'product', 'dinhMuc', 'relatedProducts'
    ));
}
```

**View updates:** Same pattern as GachCoBatTrang.

### 3.4 GachTrangTriController

```php
public function detail($id)
{
    $product = $this->gachTrangTriCtService->findById($id);

    if ($product->is_delete == 1) {
        abort(404);
    }

    $dinhMuc = $this->dinhMucGachTrangTriService->getAll();

    $relatedProducts = $this->gachTrangTriCtService->getAll('active')
        ->where('gach_trang_tri_ct_id', '!=', $id)
        ->take(4);

    return view('clients.products.gach-trang-tri.detail', compact(
        'product', 'dinhMuc', 'relatedProducts'
    ));
}
```

**View updates:** Same pattern as GachCoBatTrang.

### 3.5 LanCanGomSuController

**No CT table exists** — same as DenGomSu.

```php
public function detail($id)
{
    return view('clients.products.lan-can-gom-su.detail', compact('id'));
}
```

### 3.6 LinhVatPhongThuyController

```php
public function detail($id)
{
    $product = $this->linhVatPhongThuyCtService->findById($id);

    if ($product->is_delete == 1) {
        abort(404);
    }

    $relatedProducts = $this->linhVatPhongThuyCtService->getAll('active')
        ->where('linh_vat_phong_thuy_ct_id', '!=', $id)
        ->take(4);

    return view('clients.products.linh-vat-phong-thuy.detail', compact(
        'product', 'relatedProducts'
    ));
}
```

**Note:** LinhVatPhongThuyCt has `size_des[]` (unique extra field). Loop over it in views same as `des`.

**View updates:**
- `@foreach($product->size_des as $item)` — additional description lines
- No dinhMuc for this product type per spec

### 3.7 NgoiHaiVanMieuController

```php
public function detail($id)
{
    $product = $this->ngoiHaiVanMieuCtService->findById($id);

    if ($product->is_delete == 1) {
        abort(404);
    }

    // Colors via Eloquent hasMany relation (lazy loaded — acceptable for detail page traffic)
    $colors = $product->mauSacs;
    // Future optimization: update findById() in NgoiHaiVanMieuCtService to:
    // NgoiHaiVanMieuCt::with('mauSacs')->findOrFail($id)

    $dinhMuc = $this->dinhMucNgoiHaiVanMieuService->getAll();

    $relatedProducts = $this->ngoiHaiVanMieuCtService->getAll('active')
        ->where('ngoi_hai_van_mieu_ct_id', '!=', $id)
        ->take(4);

    return view('clients.products.ngoi-hai-van-mieu.detail', compact(
        'product', 'colors', 'dinhMuc', 'relatedProducts'
    ));
}
```

**View updates:**
- Color palette: `@foreach($colors as $color)`
  - `{{ $color->name }}`, `Storage::url($color->image)`, `{{ $color->code }}`, `{{ number_format($color->price) }}đ`
- Note: `NgoiHaiVanMieuCt` has no `code` — product code is per color variant

### 3.8 PhuKienNgoiController

**Special case** — handled in Phase 4. Basic stub in Phase 3:

```php
public function detail($id)
{
    // Phase 4 will implement cross-table lookup logic
    return view('clients.products.phu-kien-ngoi.detail', compact('id'));
}
```

## DinhMuc Service Methods

All DinhMuc services follow the same pattern:

| Service | Method | Returns |
|---|---|---|
| `DinhMucGachCoBatTrangService` | `getAll()` | Collection of `DinhMucGachCoBatTrang` |
| `DinhMucGachHoaThongGioService` | `getAll()` | Collection of `DinhMucGachHoaThongGio` |
| `DinhMucGachTrangTriService` | `getAll()` | Collection of `DinhMucGachTrangTri` |
| `DinhMucNgoiAmDuongService` | `getAll()` | Collection of `DinhMucNgoiAmDuong` |
| `DinhMucNgoiHaiVanMieuService` | `getAll()` | Collection of `DinhMucNgoiHaiVanMieu` |

**Blade template for DinhMuc table:**

```blade
@foreach($dinhMuc as $dm)
    <tr>
        <td>{{ $dm->brick_type ?? $dm->roof_type }}</td>
        <td>{{ $dm->rate }}</td>
        <td>{{ $dm->weight ?? $dm->tile_count }}</td>
    </tr>
@endforeach
```

## Blade Expression Reference for detail

| Component attribute | Dynamic value |
|---|---|
| `title="Gạch Cổ Bát Tràng"` | `title="{{ $product->name }}"` |
| `price="675.000 đ/m2"` | `price="{{ number_format($product->price) }}đ"` |
| `image="{{ asset('...') }}"` | `image="{{ Storage::url($product->images[0] ?? '') }}"` |
| Static `<img>` gallery | `@foreach($product->images as $img) <img src="{{ Storage::url($img) }}"> @endforeach` |
| Size specification | `{{ $product->size }}` |
| Description lines | `@foreach($product->des as $line) <p>{{ $line }}</p> @endforeach` |
| Size image | `Storage::url($product->size_image)` |

## Related Code Files

- **Modify (8 controllers):** Same as Phase 1
- **Modify (8 detail views):**
  - `resources/views/clients/products/den-gom-su/detail.blade.php`
  - `resources/views/clients/products/gach-co-bat-trang/detail.blade.php`
  - `resources/views/clients/products/gach-hoa-thong-gio/detail.blade.php`
  - `resources/views/clients/products/gach-trang-tri/detail.blade.php`
  - `resources/views/clients/products/lan-can-gom-su/detail.blade.php`
  - `resources/views/clients/products/linh-vat-phong-thuy/detail.blade.php`
  - `resources/views/clients/products/ngoi-hai-van-mieu/detail.blade.php`
  - `resources/views/clients/products/phu-kien-ngoi/detail.blade.php`

## Implementation Steps

1. Implement `detail($id)` in each controller following the mapping above (7 standard, 1 placeholder for Phase 4)
2. For each detail view, identify hardcoded product data
3. Replace with dynamic Blade expressions from `$product`, `$colors`, `$dinhMuc`, `$relatedProducts`
4. Test with a known product ID from DB
5. Verify 404 behavior for deleted products and non-existent IDs

## Success Criteria

- [x] All detail methods fetch and pass product data to views
- [x] Product name, price, size, descriptions render from DB
- [x] Gallery images render from `$product->images` JSON array
- [x] Color palette renders from `$colors` (NgoiAmDuong, NgoiHaiVanMieu)
- [x] DinhMuc table renders from `$dinhMuc` service
- [x] Related products slider renders from `$relatedProducts`
- [x] Deleted products (`is_delete == 1`) return 404
- [x] Non-existent IDs return 404 (via `findOrFail`)
- [x] UI layout unchanged

## Risk Assessment

- **Risk:** Hardcoded component attributes (e.g., `title="Gạch Cổ Bát Tràng"`) in shared `<x-products.*>` components mean changing to `title="{{ $product->name }}"` — but the component may pass these directly to HTML. **Mitigation:** Verify each shared component accepts these props — they should already work since NgoiAmDuongController uses them.
- **Risk:** `$product->mauSacs` relation may need eager loading. **Mitigation:** Check model relation definition; if `NgoiHaiVanMieuCt` model has `mauSacs()` hasMany, lazy loading works but causes N+1 queries. Acceptable for now given low traffic on detail pages.
