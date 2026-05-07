# Codebase Summary

## Overview

Laravel 12 monolith with Blade frontend. 42 database tables, layered architecture (Controller -> Service -> Model), no repository pattern. Custom RBAC with `superadmin`/`admin` roles. Vietnamese SEO URLs with 301 redirects from legacy English paths. Admin panel includes page configuration (factory tour, contact, FAQ) with Alpine.js + TinyMCE.

## Directory Tree

```
th-ceramics-fullstack/
├── app/
│   ├── Helpers/
│   │   └── FileUploadHelper.php          # Image upload/replace/delete
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                    # 36 files: CRUD per product category + page config panels
│   │   │   └── Client/
│   │   │       ├── ProductPages/         # 9 product page controllers
│   │   │       └── FactoryController, ContactController, FaqController  # 3 dynamic page controllers
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php        # RBAC: superadmin, admin
│   │   └── Requests/                     # 13 form validation classes
│   ├── Models/                           # 42 Eloquent models
│   ├── Providers/
│   │   └── AppServiceProvider.php        # Empty registration
│   └── Services/                         # 36 service classes (business logic)
├── bootstrap/
│   ├── app.php                           # Middleware, routing, exception config
│   └── providers.php                     # Service provider registration
├── config/                               # 10 Laravel config files
├── database/
│   ├── factories/
│   ├── migrations/                       # 9 files → 42 tables
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
| `app/Models/` | 42 | Eloquent models mapping to DB tables |
| `app/Services/` | 36 | Business logic layer |
| `app/Http/Controllers/Admin/` | 36 | Admin CRUD controllers |
| `app/Http/Controllers/Client/` | 13 | Public page controllers |
| `app/Http/Controllers/Client/ProductPages/` | 9 | Product page controllers |
| `app/Http/Requests/` | 13 | Form request validators |
| `app/Helpers/` | 1 | FileUploadHelper |
| `app/Http/Middleware/` | 1 | RoleMiddleware |
| `routes/` | 3 | web.php (/admin + /admin/pages/*), client.php, console.php |
| `database/migrations/` | 9 | 9 migration files = 42 tables |
| `database/seeders/` | 4 | User, ProductType, ProductDetail seeders |
| `resources/views/admin/` | 61 | Admin Blade templates |
| `resources/views/clients/` | 134 | Client Blade templates |
| `resources/views/components/` | 28 | Shared Blade components |
| `config/` | 10 | App, database, cache, session, etc. |
| `tests/` | 7 | Pest test files (14 tests, 31 assertions, all passing) |

## Models Breakdown

### Parent Section Models (10)
Single-row config per product category: `NgoiAmDuong`, `NgoiHaiVanMieu`, `GachHoaThongGio`, `PhuKienNgoi`, `GachTrangTri`, `LanCanGomXu`, `GachCoBatTrang`, `LinhVatPhongThuy`, `DenGomSu`, `GiaTriVuotTroi`

### Child Detail Models (14)
Multi-row product items: `NgoiAmDuongCt`, `NgoiHaiCoCt`, `NgoiHaiVanMieuCt`, `GachHoaThongGioCt`, `GachTrangTriCt`, `GachCoBatTrangCt`, `LinhVatPhongThuyCt`, `NgoiBoNocCt`, `BoNocChuVanCt`

### Sub-Resource Models (10)
`MauSacNgoiAmDuongCt`, `MauSacNgoiHaiCoCt`, `MauSacNgoiHaiVanMieuCt`, `PhanLoaiNgoiBoNocCt`, `PhanLoaiBoNocChuVanCt`, `DauAnGachTrangTri`, `GiaTriGachHoaThongGio`, `LinhVat`, `DenGomSuAnh`, `GachCoBatTrangAnh`, `GachHoaThongGioAnh`, `LinhVatPhongThuyAnh`

### Dinh Muc Models (6)
Rating/estimation tables: `DinhMucNgoiAmDuong`, `DinhMucNgoiHaiCo`, `DinhMucNgoiHaiVanMieu`, `DinhMucGachHoaThongGio`, `DinhMucGachTrangTri`, `DinhMucGachCoBatTrang`

### Page Configuration Models (4)
Single-row config for static pages: `PageFactory` (14+ fields for factory tour page), `PageContact` (5 fields for contact page), `PageFaq` (FAQ page config), `Faq` (FAQ items with WYSIWYG answers)

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
