# Codebase Summary

## Overview

Laravel 12 monolith with Blade frontend. 45 database tables, layered architecture (Controller -> Service -> Model), no repository pattern. Custom RBAC with `superadmin`/`admin` roles. Vietnamese SEO URLs with 301 redirects from legacy English paths. Home page fully dynamic via HomeController (queries TrangChu, DuAn, NgoiAmDuongCt, NgoiHaiVanMieuCt, GachHoaThongGioCt). Session-based cart with AJAX controls, checkout flow with Orders/OrderItems persistence (COD-only payment), email notification system (order confirmation + status updates via database queue), coupon/discount code system with percent and fixed discount types. Admin panel includes page configuration (factory tour, contact, FAQ) with Alpine.js (auto-resize textareas, tab navigation, image management), order management (list, detail, status update with email notification), and coupon management (full CRUD with restore). Client-side dynamic order status tracking page with tab filtering. Dynamic customer service pages: installation guide (ThiCong model), catalog list with PDF flipbook reader (Catalog model, PDF.js + StPageFlip).

## Directory Tree

```
th-ceramics-fullstack/
├── app/
│   ├── Helpers/
│   │   └── FileUploadHelper.php          # Image upload/replace/delete
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                    # 48 files: CRUD per product category + page config + order management
│   │   │   └── Client/
│   │   │       ├── ProductPages/         # 9 product page controllers
│   │   │       └── FactoryController, ContactController, FaqController, CartController  # page + cart controllers
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php        # RBAC: superadmin, admin
│   │   └── Requests/                     # 15 form validation classes
│   ├── Mail/                             # 2 Mailable classes (ShouldQueue)
│   │   ├── OrderCreatedMail.php          # Order confirmation email
│   │   └── OrderStatusUpdatedMail.php    # Status change notification
│   ├── Notifications/
│   │   └── ResetPasswordNotification.php # Custom password reset (ShouldQueue, role-based routing)
│   ├── Models/                           # 55 Eloquent models
│   ├── Providers/
│   │   └── AppServiceProvider.php        # Empty registration
│   └── Services/                         # 49 service classes (business logic)
├── bootstrap/
│   ├── app.php                           # Middleware, routing, exception config
│   └── providers.php                     # Service provider registration
├── config/                               # 10 Laravel config files
├── database/
│   ├── factories/
│   ├── migrations/                       # 15 files → 44 tables
│   └── seeders/                          # 8 files (User, ProductType, ProductDetail, DinhMuc, HomeAndAboutUs, PageConfig, DuAn, DatabaseSeeder)
├── public/
│   └── assets/
│       ├── css/main.css
│       ├── images/                       # Logo, icons
│       └── js/app.js
├── resources/
│   └── views/
│       ├── admin/                        # 58 files (32 sub-directories)
│       ├── clients/                      # 136 files (36 sub-directories)
│       ├── emails/                       # 3 files: order confirmation, status update, password reset
│       │   ├── auth/
│       │   │   └── reset_password.blade.php  # Vietnamese branded password reset email
│       │   └── orders/
│       └── components/                   # 29 shared Blade components
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
| `app/Models/` | 55 | Eloquent models mapping to DB tables |
| `app/Services/` | 49 | Business logic layer |
| `app/Http/Controllers/Admin/` | 48 | Admin CRUD controllers (incl. OrderController) |
| `app/Http/Controllers/Client/` | 10 | Public page controllers (auth, cart, pages) |
| `app/Http/Controllers/Client/DichVuKhachHang/` | 8 | Customer service controllers (catalog, installation guide, policies) |
| `app/Http/Controllers/Client/ProductPages/` | 9 | Product page controllers |
| `app/Http/Requests/` | 15 | Form request validators |
| `app/Mail/` | 2 | Mailable classes (ShouldQueue: order created, status updated) |
| `app/Notifications/` | 1 | ResetPasswordNotification (ShouldQueue, role-based routing) |
| `app/Helpers/` | 1 | FileUploadHelper |
| `app/Http/Middleware/` | 1 | RoleMiddleware |
| `routes/` | 3 | web.php (/admin + /admin/pages/*), client.php, console.php |
| `database/migrations/` | 7 | Migration files |
| `database/seeders/` | 8 | User, ProductType, ProductDetail, DinhMuc, HomeAndAboutUs, PageConfig, DuAn, DatabaseSeeder |
| `resources/views/admin/` | 77 | Admin Blade templates (incl. orders/index, orders/show) |
| `resources/views/clients/` | 136 | Client Blade templates (incl. order status page) |
| `resources/views/emails/` | 3 | Email templates (order created, status updated, password reset) |
| `resources/views/components/` | 30 | Shared Blade components (incl. admin-preview-button) |
| `config/` | 10 | App, database, cache, session, etc. |
| `tests/` | 8 | Pest test files (29 tests, all passing) |

## Models Breakdown

### Parent Section Models (10)
Single-row config per product category: `NgoiAmDuong`, `NgoiHaiVanMieu`, `GachHoaThongGio`, `PhuKienNgoi`, `GachTrangTri`, `LanCanGomXu`, `GachCoBatTrang`, `LinhVatPhongThuy`, `DenGomSu`, `GiaTriVuotTroi`

### Child Detail Models (14)
Multi-row product items: `NgoiAmDuongCt`, `NgoiHaiCoCt`, `NgoiHaiVanMieuCt`, `GachHoaThongGioCt`, `GachTrangTriCt`, `GachCoBatTrangCt`, `LinhVatPhongThuyCt`, `NgoiBoNocCt`, `BoNocChuVanCt`

### Sub-Resource Models (10)
`MauSacNgoiAmDuongCt`, `MauSacNgoiHaiCoCt`, `MauSacNgoiHaiVanMieuCt`, `PhanLoaiNgoiBoNocCt`, `PhanLoaiBoNocChuVanCt`, `DauAnGachTrangTri`, `GiaTriGachHoaThongGio`, `LinhVat`, `DenGomSuAnh`, `GachCoBatTrangAnh`, `GachHoaThongGioAnh`, `LinhVatPhongThuyAnh`

### Dinh Muc Models (6)
Rating/estimation tables: `DinhMucNgoiAmDuong`, `DinhMucNgoiHaiCo`, `DinhMucNgoiHaiVanMieu`, `DinhMucGachHoaThongGio`, `DinhMucGachTrangTri`, `DinhMucGachCoBatTrang`

### Page Configuration Models (6)
Single-row config for static pages: `PageFactory` (14+ fields for factory tour page), `PageContact` (5 fields for contact page), `PageFaq` (FAQ page config), `Faq` (FAQ items with WYSIWYG answers), `TrangChu` (home page: banner, khach_hang_doi_tac, loi_tri_an, ve_chung_toi_logo, nhung_con_so, showroom_images -- all JSON arrays), `GiaiThuongThanhTuu` (awards: image, des)

### System Models (1)
`User` — authentication with role-based access

### Commerce Models (3)
`Order` — orders with status tracking (pending_payment, processing, shipping, completed, canceled, returned), payment method (COD only since banking disabled), auto-generated order codes (`THC-YYYYMMDD-XXXX`), optional coupon_code, `statusLabel()` static helper for Vietnamese labels, `generateOrderCode()` with uniqueness check
`OrderItem` — polymorphic line items (product_type, product_id, variant_id) with price and quantity tracking
`Coupon` — discount codes with percent/fixed types, max discount cap, min order value, product-type filtering, usage limits, validity dates, banner support

### Customer Service Models (2)
`ThiCong` — installation guide records (table `thi_cong`, PK `thi_cong`), fields: tieu_de, anh (image), link_youtube, used in dynamic installation guide page
`Catalog` — product catalog PDFs (table `catalog`, PK `catalog_id`), fields: tieu_de, anh_dai_dien (thumbnail), file (PDF path), used in catalog list + PDF flipbook reader

### Project Module Models (2)
`DanhMucDuAn` — project categories (table `danh_muc_du_an`, PK `danh_muc_du_an_id`), fields: ten_danh_muc, is_delete; hasMany DuAns
`DuAn` — individual projects (table `du_an`, PK `du_an_id`), fields: ten_du_an, dia_diem, san_pham, nam, images (JSON array), slug, danh_muc_du_an_id; BelongsTo DanhMucDuAn

## Key Architectural Decisions

1. **No Repository Pattern**: Business logic lives in Service classes; controllers are thin and delegate to services via constructor DI
2. **Custom Primary Keys**: All tables use `{table_name}_id` instead of default `id`
3. **Boolean Soft-Delete**: `is_delete` column (0=active, 1=deleted) instead of Laravel's built-in soft delete trait
4. **JSON Columns**: `images`, `des`, `size_des` columns store arrays as JSON
5. **Single-Record Tables**: Product section tables hold exactly one row (updated in place, never deleted)
6. **CDN Frontend**: Tailwind CSS loaded via CDN; Vite configured but unused because entry points don't exist in `resources/css/` or `resources/js/`
7. **Database Drivers**: Session, cache, and queue all use the `database` driver
8. **Global Code Uniqueness**: `GlobalProductCodeService` enforces unique product codes across 9 detail tables
