# Codebase Summary

## Overview

Laravel 12 monolith with Blade frontend. 38 database tables, layered architecture (Controller -> Service -> Model), no repository pattern. Custom RBAC with `superadmin`/`admin` roles. Vietnamese SEO URLs with 301 redirects from legacy English paths.

## Directory Tree

```
th-ceramics-fullstack/
├── app/
│   ├── Helpers/
│   │   └── FileUploadHelper.php          # Image upload/replace/delete
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                    # 32 files: CRUD per product category
│   │   │   └── Client/
│   │   │       └── ProductPages/         # 9 product page controllers
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php        # RBAC: superadmin, admin
│   │   └── Requests/                     # 10 form validation classes
│   ├── Models/                           # 38 Eloquent models
│   ├── Providers/
│   │   └── AppServiceProvider.php        # Empty registration
│   └── Services/                         # 33 service classes (business logic)
├── bootstrap/
│   ├── app.php                           # Middleware, routing, exception config
│   └── providers.php                     # Service provider registration
├── config/                               # 10 Laravel config files
├── database/
│   ├── factories/
│   ├── migrations/                       # 5 files → 38 tables
│   └── seeders/                          # 4 files (User, ProductType, ProductDetail)
├── public/
│   └── assets/
│       ├── css/main.css
│       ├── images/                       # Logo, icons
│       └── js/app.js
├── resources/
│   └── views/
│       ├── admin/                        # 58 files (32 sub-directories)
│       ├── clients/                      # 134 files (36 sub-directories)
│       └── components/                   # 28 shared Blade components
├── routes/
│   ├── web.php                           # Admin routes (/admin/*)
│   ├── client.php                        # Public routes (Vietnamese URLs)
│   └── console.php
├── tests/
│   ├── Feature/
│   ├── Unit/
│   ├── Pest.php
│   └── TestCase.php
├── vendor/                               # Composer dependencies
├── composer.json
├── vite.config.js                        # Configured but unused
└── CLAUDE.md                             # Laravel Boost project guidelines
```

## File Counts

| Directory | Count | Description |
|-----------|-------|-------------|
| `app/Models/` | 38 | Eloquent models mapping to DB tables |
| `app/Services/` | 33 | Business logic layer |
| `app/Http/Controllers/Admin/` | 32 | Admin CRUD controllers |
| `app/Http/Controllers/Client/` | 10 | Public page controllers |
| `app/Http/Controllers/Client/ProductPages/` | 9 | Product page controllers |
| `app/Http/Requests/` | 10 | Form request validators |
| `app/Helpers/` | 1 | FileUploadHelper |
| `app/Http/Middleware/` | 1 | RoleMiddleware |
| `routes/` | 3 | web.php, client.php, console.php |
| `database/migrations/` | 5 | 5 migration files = 38 tables |
| `database/seeders/` | 4 | User, ProductType, ProductDetail seeders |
| `resources/views/admin/` | 58 | Admin Blade templates |
| `resources/views/clients/` | 134 | Client Blade templates |
| `resources/views/components/` | 28 | Shared Blade components |
| `config/` | 10 | App, database, cache, session, etc. |
| `tests/` | 4 | Pest test files |

## Models Breakdown

### Parent Section Models (10)
Single-row config per product category: `NgoiAmDuong`, `NgoiHaiVanMieu`, `GachHoaThongGio`, `PhuKienNgoi`, `GachTrangTri`, `LanCanGomXu`, `GachCoBatTrang`, `LinhVatPhongThuy`, `DenGomSu`, `GiaTriVuotTroi`

### Child Detail Models (14)
Multi-row product items: `NgoiAmDuongCt`, `NgoiHaiCoCt`, `NgoiHaiVanMieuCt`, `GachHoaThongGioCt`, `GachTrangTriCt`, `GachCoBatTrangCt`, `LinhVatPhongThuyCt`, `NgoiBoNocCt`, `BoNocChuVanCt`

### Sub-Resource Models (10)
`MauSacNgoiAmDuongCt`, `MauSacNgoiHaiCoCt`, `MauSacNgoiHaiVanMieuCt`, `PhanLoaiNgoiBoNocCt`, `PhanLoaiBoNocChuVanCt`, `DauAnGachTrangTri`, `GiaTriGachHoaThongGio`, `LinhVat`, `DenGomSuAnh`, `GachCoBatTrangAnh`, `GachHoaThongGioAnh`, `LinhVatPhongThuyAnh`

### Dinh Muc Models (6)
Rating/estimation tables: `DinhMucNgoiAmDuong`, `DinhMucNgoiHaiCo`, `DinhMucNgoiHaiVanMieu`, `DinhMucGachHoaThongGio`, `DinhMucGachTrangTri`, `DinhMucGachCoBatTrang`

### System Models (1)
`User` — authentication with role-based access

## Key Architectural Decisions

1. **No Repository Pattern**: Business logic lives in Service classes; controllers are thin and delegate to services via constructor DI
2. **Custom Primary Keys**: All tables use `{table_name}_id` instead of default `id`
3. **Boolean Soft-Delete**: `is_delete` column (0=active, 1=deleted) instead of Laravel's built-in soft delete trait
4. **JSON Columns**: `images`, `des`, `size_des` columns store arrays as JSON
5. **Single-Record Tables**: Product section tables hold exactly one row (updated in place, never deleted)
6. **CDN Frontend**: Tailwind CSS loaded via CDN; Vite configured but unused because entry points don't exist in `resources/css/` or `resources/js/`
7. **Database Drivers**: Session, cache, and queue all use the `database` driver
8. **Global Code Uniqueness**: `GlobalProductCodeService` enforces unique product codes across 9 detail tables
