# Codebase Summary

## Overview

Laravel 12 monolith with Blade frontend. 44 database tables, layered architecture (Controller -> Service -> Model), no repository pattern. Custom RBAC with `superadmin`/`admin`/`customer` roles. Vietnamese SEO URLs with 301 redirects from legacy English paths. Home page fully dynamic via HomeController. Session-based cart with AJAX controls, checkout flow with Orders/OrderItems persistence (COD-only payment), email notification system (order confirmation + status updates via database queue), coupon/discount code system with percent and fixed discount types. Admin panel includes page configuration (factory tour, contact, FAQ) with Alpine.js, order management, and coupon management. Client-side auth (email/password + Google OAuth) with custom Vietnamese-branded password reset notifications. Dynamic customer service pages: installation guide (ThiCong model), catalog list with PDF flipbook reader (Catalog model, PDF.js + StPageFlip). JSON-LD structured data on product detail pages.

## Directory Tree

```
th-ceramics-fullstack/
├── app/
│   ├── Helpers/
│   │   └── FileUploadHelper.php          # Image upload/replace/delete
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                    # 51 files: CRUD per product category + page config + order + coupon management
│   │   │   └── Client/
│   │   │       ├── ProductPages/         # 9 product page controllers
│   │   │       ├── DichVuKhachHang/      # 8 customer service controllers (catalog, install guide, policies, order status)
│   │   │       └── 12 page/auth/cart controllers (Home, About, Factory, Contact, Faq, Showroom, News, Project, Cart, Auth, GlobalSearch, CustomerService)
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php        # RBAC: superadmin, admin
│   │   └── Requests/                     # 31 form validation classes
│   ├── Mail/                             # 2 Mailable classes (ShouldQueue)
│   │   ├── OrderCreatedMail.php          # Order confirmation email
│   │   └── OrderStatusUpdatedMail.php    # Status change notification
│   ├── Notifications/
│   │   └── ResetPasswordNotification.php # Custom password reset (ShouldQueue, role-based routing)
│   ├── Models/                           # 55 Eloquent models
│   ├── Providers/
│   │   └── AppServiceProvider.php        # Empty registration
│   └── Services/                         # 53 service classes (business logic)
├── bootstrap/
│   ├── app.php                           # Middleware, routing, exception config
│   └── providers.php                     # Service provider registration
├── config/                               # 10 Laravel config files
├── database/
│   ├── factories/
│   ├── migrations/                       # 15 files → 44 tables
│   └── seeders/                          # 26 files (core: User, DinhMuc, PageConfig, HomeAndAboutUs, ProductType, ProductDetail, DuAn, DatabaseSeeder + 18 individual product & detail seeders)
├── public/
│   └── assets/
│       ├── css/main.css
│       ├── images/                       # Logo, icons
│       └── js/app.js
├── resources/
│   └── views/
│       ├── admin/                        # 86 files (multiple sub-directories per category)
│       ├── clients/                      # 142 files (per-category sub-directories + home parts + service pages)
│       ├── emails/                       # 4 files: order confirmation, status update, password reset, combined layout
│       │   ├── auth/
│       │   │   └── reset_password.blade.php  # Vietnamese branded password reset email
│       │   └── orders/
│       └── components/                   # 32 shared Blade components
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
| `app/Services/` | 53 | Business logic layer (incl. CartService, CouponService, GlobalProductCodeService) |
| `app/Http/Controllers/Admin/` | 51 | Admin CRUD controllers (incl. OrderController, CouponController) |
| `app/Http/Controllers/Client/` | 12 | Top-level public page controllers (Home, About, Factory, Contact, Faq, Showroom, News, Project, Cart, Auth, GlobalSearch, CustomerService) |
| `app/Http/Controllers/Client/DichVuKhachHang/` | 8 | Customer service controllers (catalog, installation guide, policies, order status) |
| `app/Http/Controllers/Client/ProductPages/` | 9 | Product page controllers |
| `app/Http/Requests/` | 31 | Form request validators |
| `app/Mail/` | 2 | Mailable classes (ShouldQueue: order created, status updated) |
| `app/Notifications/` | 1 | ResetPasswordNotification (ShouldQueue, role-based routing) |
| `app/Helpers/` | 1 | FileUploadHelper |
| `app/Http/Middleware/` | 1 | RoleMiddleware |
| `routes/` | 3 | web.php (/admin + /admin/pages/*), client.php, console.php |
| `database/migrations/` | 15 | Migration files (grouped batch + individual) |
| `database/seeders/` | 26 | Core + individual product seeders |
| `resources/views/admin/` | 86 | Admin Blade templates (all categories + orders + coupons + config) |
| `resources/views/clients/` | 142 | Client Blade templates (all products + home + service pages) |
| `resources/views/emails/` | 4 | Email templates (order created, status updated, password reset, combined layout) |
| `resources/views/components/` | 32 | Shared Blade components (incl. desktop-product-card, preview-button) |
| `config/` | 10 | App, database, cache, session, etc. |
| `tests/` | 25 | Pest test files (covering admin pages, auth, client product pages) |

## Models Breakdown

### Parent Section Models (10)
Single-row config per product category: `NgoiAmDuong`, `NgoiHaiVanMieu`, `GachHoaThongGio`, `PhuKienNgoi`, `GachTrangTri`, `LanCanGomXu`, `GachCoBatTrang`, `LinhVatPhongThuy`, `DenGomSu`, `GiaTriVuotTroi`

### Child Detail Models (10)
Multi-row product items: `NgoiAmDuongCt`, `NgoiHaiCoCt`, `NgoiHaiVanMieuCt`, `GachHoaThongGioCt`, `GachTrangTriCt`, `GachCoBatTrangCt`, `LinhVatPhongThuyCt`, `DenVuonGomSuCt`, `PhuKienNgoiCt`, `LanCanGomSuCt`

### Sub-Resource Models (12)
`MauSacNgoiAmDuongCt`, `MauSacNgoiHaiCoCt`, `MauSacNgoiHaiVanMieuCt`, `GiaTriGachHoaThongGio`, `LinhVat`, `DenGomSuAnh`, `GachCoBatTrangAnh`, `GachHoaThongGioAnh`, `LinhVatPhongThuyAnh`, `PhanLoaiDenVuonGomSuCt`, `PhanLoaiLanCanGomSuCt`, `PhanLoaiPhuKienNgoiCt`

### Dinh Muc Models (6)
Rating/estimation tables: `DinhMucNgoiAmDuong`, `DinhMucNgoiHaiCo`, `DinhMucNgoiHaiVanMieu`, `DinhMucGachHoaThongGio`, `DinhMucGachTrangTri`, `DinhMucGachCoBatTrang`

### Page Configuration Models (7)
Single-row config for static pages: `PageFactory` (14+ fields for factory tour page), `PageContact` (5 fields for contact page), `PageFaq` (FAQ page config), `Faq` (FAQ items with WYSIWYG answers), `TrangChu` (home page: banner, khach_hang_doi_tac, loi_tri_an, ve_chung_toi_logo, nhung_con_so, showroom_images -- all JSON arrays), `GiaiThuongThanhTuu` (awards: image, des), `VeChungToi` (about us page)

### System Models (1)
`User` — authentication with role-based access

### Commerce Models (3)
`Order` — orders with status tracking (pending_payment, processing, shipping, completed, canceled, returned), payment method (COD only since banking disabled), auto-generated order codes (`THC-YYYYMMDD-XXXX`), optional coupon_code, `statusLabel()` static helper for Vietnamese labels, `generateOrderCode()` with uniqueness check
`OrderItem` — polymorphic line items (product_type, product_id, variant_id) with price and quantity tracking
`Coupon` — discount codes with percent/fixed types, max discount cap, min order value, product-type filtering, usage limits, validity dates, banner support

### Customer Service Models (2)
`ThiCong` — installation guide records (table `thi_cong`, PK `thi_cong`), fields: tieu_de, anh (image), link_youtube, used in dynamic installation guide page
`Catalog` — product catalog PDFs (table `catalog`, PK `catalog_id`), fields: tieu_de, anh_dai_dien (thumbnail), file (PDF path), used in catalog list + PDF flipbook reader

### News/Content Models (3)
`TinTuc` — news articles, fields: tieu_de, slug, noi_dung, hinh_anh, tac_gia_id, danh_muc_tin_tuc_id, ngay_dang, is_delete
`DanhMucTinTuc` — news categories (table `danh_muc_tin_tuc`, PK `danh_muc_tin_tuc_id`), fields: ten_danh_muc, slug, is_delete
`TacGia` — news authors (table `tac_gia`, PK `tac_gia_id`), fields: ten_tac_gia, slug

### Project Module Models (2)
`DanhMucDuAn` — project categories (table `danh_muc_du_an`, PK `danh_muc_du_an_id`), fields: ten_danh_muc, is_delete; hasMany DuAns
`DuAn` — individual projects (table `du_an`, PK `du_an_id`), fields: ten_du_an, dia_diem, san_pham, nam, images (JSON array), slug, danh_muc_du_an_id; BelongsTo DanhMucDuAn

## Key Architectural Decisions

1. **No Repository Pattern**: Business logic lives in Service classes; controllers are thin and delegate to services via constructor DI
2. **Custom Primary Keys**: All tables use `{table_name}_id` instead of default `id`
3. **Boolean Soft-Delete**: `is_delete` column (0=active, 1=deleted) instead of Laravel's built-in soft delete trait; queries must manually filter `WHERE is_delete = 0`
4. **JSON Columns**: `images`, `des`, `size_des` columns store arrays as JSON
5. **Single-Record Tables**: Product section tables hold exactly one row (updated in place, never deleted)
6. **Frontend**: Tailwind CSS via CDN for client main layout; Vite used for auth layout; assets served from `public/assets/` for legacy content
7. **Database Drivers**: Session, cache, and queue all use the `database` driver (no Redis dependency)
8. **Global Code Uniqueness**: `GlobalProductCodeService` enforces unique product codes across 9 detail tables
9. **Role-Based Auth**: Simple string `role` column on `users` table (`superadmin`, `admin`, `customer`) -- no Spatie Permission package
10. **Client Authentication**: Email/password + Google OAuth via Socialite 5.27; custom Vietnamese ResetPasswordNotification (ShouldQueue)
11. **JSON-LD Structured Data**: Product schema markup injected on product detail pages for SEO
