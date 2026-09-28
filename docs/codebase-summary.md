# Codebase Summary

## Overview

**TH Ceramics Fullstack** is a Laravel 12 monolith with a Blade frontend, transitioning from a flat layered architecture into a **Modular Domain-Driven Monolith** (`app/Domains/`). The core product system was completely refactored from 16 fragmented category tables into a **Canonical Catalog** architecture (`products`, `product_variants`, `product_media`, `product_display_options`). 

Features include:
- **Canonical Product Catalog**: All ceramic products unified under a single flexible entity with variants, polymorphic media (images, YouTube links, MP4 videos), and dynamic display options.
- **Data Portability**: Full backup and restore via `database.zip` through the Archive Domain, with two-way backward compatibility for legacy exports.
- **E-commerce & Consultation**: Global toggle (`is_ecommerce_enabled`) switching between session-based cart/checkout (with coupon discounts and queued order status emails) and B2B consultation request mode.
- **Dynamic CMS**: Home page hero banner, project showcase (`du_an`), factory tour, FAQ, installation guides (`thi_cong`), and PDF flipbook catalog reader (`catalog`).
- **Role-Based Auth**: Custom RBAC (`superadmin`, `admin`, `customer`) with throttled logins, Google OAuth, and queued branded Vietnamese password reset notifications.

## Directory Tree

```
th-ceramics-fullstack/
├── app/
│   ├── Domains/                          # Bounded Contexts (Domain Layer)
│   │   ├── Archive/                      # Content backup/restore (ContentArchiveService, LegacyV1ArchiveAdapter)
│   │   ├── Catalog/                      # Core Product Catalog
│   │   │   ├── Http/Admin/               # BaseProductItemController, BaseProductVariantController
│   │   │   ├── Models/                   # Product, ProductVariant, ProductMedia, ProductDisplayOption
│   │   │   ├── Services/                 # CatalogQueryService
│   │   │   ├── ProductWriter.php         # Atomic mutations, SKU uniqueness, gallery reordering
│   │   │   ├── ProductTypeRegistry.php   # Category metadata & definitions
│   │   │   └── PublicIdAllocator.php     # Stable public ID mapping for SEO URLs
│   │   ├── Commerce/                     # Cart, Orders, Coupons
│   │   │   ├── Http/                     # CartController, OrderController
│   │   │   ├── Models/                   # Order, OrderItem, Coupon
│   │   │   └── Services/                 # CartService, CouponService
│   │   ├── Content/                      # CMS pages, projects, customer services
│   │   │   ├── Http/                     # HomeController, ProjectController, etc.
│   │   │   ├── Models/                   # TrangChu, DuAn, DanhMucDuAn, ThiCong, Catalog, Page*
│   │   │   └── Services/                 # TrangChuService, DuAnService, etc.
│   │   ├── Identity/                     # Authentication & User Management
│   │   │   ├── Http/                     # Admin\AuthController, Client\AuthController
│   │   │   ├── Models/                   # User
│   │   │   └── Services/                 # AuthService
│   │   └── Media/                        # Media upload pipeline & WebP optimization
│   │       ├── Http/                     # Staged image uploads
│   │       └── Services/                 # StagedImageUploadService, FileUploadHelper
│   ├── Http/                             # Application Delivery Layer
│   │   ├── Controllers/
│   │   │   ├── Admin/                    # Thin product stubs inheriting BaseProductItemController + CMS config
│   │   │   └── Client/
│   │   │       ├── ProductPages/         # 9 product controllers orchestrating distinct Blade templates
│   │   │       └── DichVuKhachHang/      # Customer service view controllers
│   │   ├── Middleware/                   # RoleMiddleware, EnsureEcommerceEnabled, ContentWriteGate
│   │   └── Requests/                     # Form request validators
│   ├── Mail/                             # OrderCreatedMail, OrderStatusUpdatedMail (ShouldQueue)
│   ├── Notifications/                    # ResetPasswordNotification (ShouldQueue)
│   ├── Models/                           # Legacy CMS section configuration models
│   ├── Providers/                        # AppServiceProvider
│   ├── Rules/                            # Custom rules (e.g., YoutubeUrl)
│   ├── Services/                         # Cross-cutting services (ProductBulkRename, ViewHistory)
│   ├── Support/                          # ClientProductType, ProductPriority, ProductJourneyVideo
│   └── View/Components/                  # Blade view components
├── config/                               # Application configurations
├── database/
│   ├── migrations/                       # 24 migration files (Canonical catalog + CMS + commerce)
│   └── seeders/                          # 21 seeders using Canonical ProductWriter
├── resources/views/
│   ├── admin/                            # Admin Blade templates
│   ├── clients/                          # Client Blade templates (9 distinct product layouts)
│   ├── emails/                           # Order & password reset email templates
│   └── components/                       # Shared Blade components
├── routes/
│   ├── web.php                           # Admin routes
│   ├── client.php                        # Client SEO routes
│   └── domains/                          # Route files split by domain context
└── tests/
    └── Feature/ & Unit/                  # 293 automated tests (100% green)
```

## Models Breakdown

### 1. Canonical Catalog Models (`app/Domains/Catalog/Models`)
- `Product`: Central entity representing a product item across all categories. Stores `type_key`, dimensions, weight, rating criteria, priority, and visibility.
- `ProductVariant`: Pricing and SKU entity. Supports multi-variant products (e.g. Ngói Hài Văn Miếu) and single-variant defaults.
- `ProductMedia`: Polymorphic media gallery storing cover images, gallery photos, uploaded MP4 videos, and external YouTube embeds with unique sequence ordering.
- `ProductDisplayOption`: Category or product-level color swatches and finish options.

### 2. CMS Section Models (`app/Models`)
Single-row configuration records controlling the marketing intro and landing sections for each category:
`NgoiAmDuong`, `NgoiHaiVanMieu`, `GachHoaThongGio`, `PhuKienNgoi`, `GachTrangTri`, `LanCanGomXu`, `GachCoBatTrang`, `LinhVatPhongThuy`, `DenGomSu`.

### 3. Commerce Models (`app/Domains/Commerce/Models`)
- `Order`: Full order lifecycle tracking with auto-generated order codes (`THC-YYYYMMDD-XXXX`), status workflows, and email notifications.
- `OrderItem`: Snapshot of ordered items (product_type, product_id, variant_id, price, quantity).
- `Coupon`: Discount code engine (percentage / fixed discount, product-type restriction, usage limits).

### 4. Content & Showcase Models (`app/Domains/Content/Models`)
- `TrangChu`: Home page dynamic configuration (hero slides, partner logos, statistics, showroom gallery).
- `DuAn` & `DanhMucDuAn`: Portfolio project showcase with category filtering and gallery images.
- `ThiCong`: Technical installation guides with YouTube video links.
- `Catalog`: Downloadable PDF product catalogs with an integrated flipbook reader.
- `PageFactory`, `PageContact`, `PageFaq`, `Faq`: Static and informational page configurations.

---

## Architectural Notes on Product Controllers

### Why are Product Controllers in `app/Http/Controllers/`?
- **Domain Core vs Delivery Mechanism**: The core business logic, database transactions, SKU validation, and gallery reordering are fully encapsulated inside `app/Domains/Catalog` (`ProductWriter`, `CatalogQueryService`, `BaseProductItemController`).
- **Contract & Route Preservation**: Admin controllers in `app/Http/Controllers/Admin` are thin subclasses (~20 lines) extending `BaseProductItemController`. Keeping them in `app/Http` preserves all 385 existing route references, preview URL lookups, and test configurations without a mass-renaming refactor.
- **Bespoke View Presentation**: The 9 controllers in `app/Http/Controllers/Client/ProductPages/` manage the distinct Blade layouts, interactive tile calculators, and technical drawing specs unique to each ceramic product category.
