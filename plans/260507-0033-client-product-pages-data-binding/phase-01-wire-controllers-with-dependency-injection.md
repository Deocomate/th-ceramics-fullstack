---
phase: 1
title: "Wire Controllers with Dependency Injection"
status: completed
priority: P1
effort: "1h"
dependencies: []
---

# Phase 1: Wire Controllers with Dependency Injection

## Overview

Add `__construct()` with `private readonly` service properties to all 8 unwired controllers. Import the required Service classes. No logic changes yet — just make dependencies available.

## Controller → Service Mapping

### 1. `DenGomSuController`
- **Services:** `DenGomSuService`
- **File:** `app/Http/Controllers/Client/ProductPages/DenGomSuController.php`

### 2. `GachCoBatTrangController`
- **Services:** `GachCoBatTrangService`, `GachCoBatTrangCtService`, `DinhMucGachCoBatTrangService`, `GiaTriVuotTroiService`
- **File:** `app/Http/Controllers/Client/ProductPages/GachCoBatTrangController.php`

### 3. `GachHoaThongGioController`
- **Services:** `GachHoaThongGioService`, `GachHoaThongGioCtService`, `DinhMucGachHoaThongGioService`
- **File:** `app/Http/Controllers/Client/ProductPages/GachHoaThongGioController.php`

### 4. `GachTrangTriController`
- **Services:** `GachTrangTriService`, `GachTrangTriCtService`, `DinhMucGachTrangTriService`
- **File:** `app/Http/Controllers/Client/ProductPages/GachTrangTriController.php`

### 5. `LanCanGomSuController`
- **Services:** `LanCanGomXuService` (note: model is `LanCanGomXu`, not `LanCanGomSu`)
- **File:** `app/Http/Controllers/Client/ProductPages/LanCanGomSuController.php`

### 6. `LinhVatPhongThuyController`
- **Services:** `LinhVatPhongThuyService`, `LinhVatPhongThuyCtService`
- **File:** `app/Http/Controllers/Client/ProductPages/LinhVatPhongThuyController.php`

### 7. `NgoiHaiVanMieuController`
- **Services:** `NgoiHaiVanMieuService`, `NgoiHaiVanMieuCtService`, `MauSacNgoiHaiVanMieuCtService`, `DinhMucNgoiHaiVanMieuService`
- **File:** `app/Http/Controllers/Client/ProductPages/NgoiHaiVanMieuController.php`

### 8. `PhuKienNgoiController`
- **Services:** `PhuKienNgoiService`, `NgoiBoNocCtService`, `BoNocChuVanCtService`
- **File:** `app/Http/Controllers/Client/ProductPages/PhuKienNgoiController.php`

## Constructor Pattern

Follow exactly the `NgoiAmDuongController` pattern — PHP 8.1 constructor property promotion:

```php
use App\Services\XxxService;
use App\Services\YyyService;

class XxxController extends Controller
{
    public function __construct(
        private readonly XxxService $xxxService,
        private readonly YyyService $yyyService,
    ) {}
}
```

## Related Code Files

- **Modify (8):**
  - `app/Http/Controllers/Client/ProductPages/DenGomSuController.php`
  - `app/Http/Controllers/Client/ProductPages/GachCoBatTrangController.php`
  - `app/Http/Controllers/Client/ProductPages/GachHoaThongGioController.php`
  - `app/Http/Controllers/Client/ProductPages/GachTrangTriController.php`
  - `app/Http/Controllers/Client/ProductPages/LanCanGomSuController.php`
  - `app/Http/Controllers/Client/ProductPages/LinhVatPhongThuyController.php`
  - `app/Http/Controllers/Client/ProductPages/NgoiHaiVanMieuController.php`
  - `app/Http/Controllers/Client/ProductPages/PhuKienNgoiController.php`

## Implementation Steps

1. For each controller, add `use` imports for all required Service classes
2. Add `__construct()` with `private readonly` properties matching the mapping above
3. Verify no syntax errors: run `php -l` on each file

## Success Criteria

- [x] All 8 controllers have `__construct()` with correct Service type-hints
- [x] All `use` imports resolve to existing Service classes
- [x] No syntax errors (`php -l` passes)
- [x] Existing `index()` and `detail()` methods still work (they currently only return views with no data)

## Risk Assessment

- **Risk:** Typo in service class name (e.g., `LanCanGomXuService` vs `LanCanGomSuService`). **Mitigation:** Verify against `app/Services/` directory for exact class names.
- **Risk:** Missing import causes fatal error on route hit. **Mitigation:** Verify each file parses correctly with `php -l`.
