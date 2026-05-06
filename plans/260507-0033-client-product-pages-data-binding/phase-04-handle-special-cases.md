---
phase: 4
title: "Handle Special Cases"
status: completed
priority: P2
effort: "2h"
dependencies: [2, 3]
---

# Phase 4: Handle Special Cases

## Overview

Address the three special cases that don't fit the standard Controller → Service → View pattern: (1) PhuKienNgoi cross-table `detail($id)` lookup, (2) DenGomSu & LanCanGomSu lacking CT tables, (3) JSON array parsing edge cases in views.

## 4.1 PhuKienNgoi detail($id) — Cross-Table Lookup

### Problem
`PhuKienNgoi` has two separate product tables: `NgoiBoNocCt` and `BoNocChuVanCt`. The route `phu-kien-ngoi/{id}` receives a single `$id` but must determine which table the product belongs to.

### Solution

```php
use Illuminate\Database\Eloquent\ModelNotFoundException;

public function detail($id)
{
    // Try NgoiBoNocCt first
    try {
        $product = $this->ngoiBoNocCtService->findById($id);
        $type = 'bo_noc';
    } catch (ModelNotFoundException $e) {
        // Fall back to BoNocChuVanCt
        try {
            $product = $this->boNocChuVanCtService->findById($id);
            $type = 'chu_van';
        } catch (ModelNotFoundException $e2) {
            abort(404);
        }
    }

    if ($product->is_delete == 1) {
        abort(404);
    }

    // Classifications (phanLoais) from relation
    $phanLoais = $product->phanLoais; // via Eloquent hasMany relation

    // Related products from BOTH tables (4 total)
    $relatedBoNoc = $this->ngoiBoNocCtService->getAll('active')
        ->where('ngoi_bo_noc_ct_id', '!=', $id)
        ->take(2);

    $relatedChuVan = $this->boNocChuVanCtService->getAll('active')
        ->where('bo_noc_chu_van_ct_id', '!=', $id)
        ->take(2);

    $relatedProducts = $relatedBoNoc->concat($relatedChuVan);

    return view('clients.products.phu-kien-ngoi.detail', compact(
        'product', 'type', 'phanLoais', 'relatedProducts'
    ));
}
```

### View updates

Use `$type` flag to conditionally render different product info:

```blade
{{ $product->name }}
{{ $product->size }}

@foreach($product->des as $line)
    <p>{{ $line }}</p>
@endforeach

@foreach($product->size_des as $item)
    <p>{{ $item }}</p>
@endforeach

@foreach($product->images as $img)
    <img src="{{ Storage::url($img) }}" />
@endforeach

@if($type === 'bo_noc')
    {{-- NgoiBoNoc-specific layout --}}
@else
    {{-- BoNocChuVan-specific layout --}}
@endif

{{-- Classifications table --}}
@foreach($phanLoais as $pl)
    <span>{{ $pl->name }}</span>
    <span>{{ $pl->code }}</span>
    <span>{{ number_format($pl->price) }}đ</span>
@endforeach
```

### Implementation Steps
1. Add `use Illuminate\Database\Eloquent\ModelNotFoundException;` to PhuKienNgoiController
2. Implement cross-table lookup logic in `detail($id)`
3. Update `phu-kien-ngoi/detail.blade.php` to use `$type` and render dynamic data

## 4.2 DenGomSu & LanCanGomSu — No CT Tables

### Problem
These categories have config tables (`DenGomSu`, `LanCanGomXu`) but no `_ct` product detail tables. The `detail($id)` route exists but has no product data to fetch.

### Options
| Option | Pros | Cons |
|--------|------|------|
| A. Keep static view | Preserves UI, good for SEO/demo | No dynamic product data |
| B. Redirect to index | Clean UX | Loses any SEO value of detail pages |
| C. `abort(404)` | Simple | Bad UX if links exist |

**Recommendation: Option A** — keep static views for now. The spec explicitly states "không làm phá vỡ giao diện" (don't break the UI).

### Implementation

```php
// DenGomSuController
public function detail($id)
{
    // No CT table — pass id for potential future use, keep static view
    return view('clients.products.den-gom-su.detail', compact('id'));
}

// LanCanGomSuController
public function detail($id)
{
    return view('clients.products.lan-can-gom-su.detail', compact('id'));
}
```

**No view changes needed** for these detail pages — they remain static.

## 4.3 JSON Array Handling in Views

### Problem
DB columns `images`, `des`, `size_des` are stored as JSON. Models cast them to `array` via `casts()`. Views must handle:
- Empty arrays
- Null values (column defaults to null before any data)
- Non-array values (legacy data)

### Safe patterns

```blade
{{-- Safe image loop --}}
@if(!empty($product->images) && is_array($product->images))
    @foreach($product->images as $img)
        <img src="{{ Storage::url($img) }}" alt="{{ $product->name }}" />
    @endforeach
@endif

{{-- Safe description loop --}}
@if(!empty($product->des) && is_array($product->des))
    @foreach($product->des as $line)
        <p>{{ $line }}</p>
    @endforeach
@endif

{{-- First image for thumbnail — with fallback --}}
<img src="{{ Storage::url($product->images[0] ?? '') }}"
     onerror="this.src='https://placehold.co/400x400?text=No+Image'" />
```

### Blade null-safe utilities

Consider adding a `@empty` check or the null-coalesce operator `??`:

```blade
{{-- Fallback for optional fields --}}
{{ $product->size ?? 'Đang cập nhật' }}
{{ $product->price > 0 ? number_format($product->price) . 'đ' : 'Liên hệ' }}
```

## 4.4 Route/URL Consistency

### Verify route names

All routes are prefixed `client.products.` in `routes/client.php`. In views, use:

```blade
{{ route('client.products.gach-co-bat-trang.detail', $product->gach_co_bat_trang_ct_id) }}
{{ route('client.products.gach-hoa-thong-gio.detail', $product->gach_hoa_thong_gio_ct_id) }}
{{ route('client.products.gach-trang-tri.detail', $product->gach_trang_tri_ct_id) }}
{{ route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id) }}
{{ route('client.products.ngoi-hai-van-mieu.detail', $product->ngoi_hai_van_mieu_ct_id) }}
{{ route('client.products.phu-kien-ngoi.detail', $product->getKey()) }}
```

**Note:** For PhuKienNgoi, the key could be `ngoi_bo_noc_ct_id` or `bo_noc_chu_van_ct_id`. Use `$product->getKey()` to get whichever primary key the model has.

## 4.5 Verify Storage URLs

### Check `config/filesystems.php`

Ensure the default disk is configured. If using `local` or `public` disk:

```bash
php artisan storage:link
```

Without this, `Storage::url()` returns wrong paths for non-public disks.

### Check image column values

Make sure DB image paths are just filenames/paths (e.g., `ngoi_am_duong_ct/images/xxx.jpg`), not full URLs. Service methods use `FileUploadHelper` which stores relative paths.

## 4.6 Post-Implementation Verification

After all phases complete:

```bash
# Check for syntax errors
find app/Http/Controllers/Client/ProductPages -name "*.php" -exec php -l {} \;

# Check routes
php artisan route:list --path=san-pham

# Verify views compile
php artisan view:cache
```

## Related Code Files

- **Modify:** `app/Http/Controllers/Client/ProductPages/PhuKienNgoiController.php` — add cross-table lookup
- **Modify:** `resources/views/clients/products/phu-kien-ngoi/detail.blade.php` — dynamic data + `$type` conditional
- **Modify (all views from Phase 2+3):** Add null-safe guards for JSON array fields
- **No changes:** `DenGomSuController`, `LanCanGomSuController` detail methods (stay static)

## Success Criteria

- [x] `phu-kien-ngoi/{id}` correctly routes to NgoiBoNocCt or BoNocChuVanCt based on which table contains the ID
- [x] Non-existent IDs in both tables return 404
- [x] `$type` flag correctly passed to view, conditionals work
- [x] `phanLoais` classifications render for both product types
- [x] Related products from both tables combine correctly (4 total)
- [x] DenGomSu and LanCanGomSu detail pages render without errors (static)
- [x] Empty JSON array columns don't cause Blade errors
- [x] All views handle null/missing optional fields gracefully

## Risk Assessment

- **Risk:** `ModelNotFoundException` catch works but `findById` might use different exception class. **Mitigation:** Check the service's `findById` — it uses Eloquent's `findOrFail()` which throws `Illuminate\Database\Eloquent\ModelNotFoundException`.
- **Risk:** `$product->getKey()` returns different value depending on model. **Mitigation:** This is intentional — the route helper doesn't need to know which type it is.
- **Risk:** `concat()` on two collections may have unexpected behavior. **Mitigation:** Both collections use Eloquent models — `concat()` works correctly for Eloquent Collections.
- **Risk:** Static detail pages cause confusion (user expects product data). **Mitigation:** This is documented — future phases can add CT tables for these categories.
