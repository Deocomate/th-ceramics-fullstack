# Codebase Summary

## Overview

**TH Ceramics Fullstack** is a Laravel 12 monolith with a Blade frontend, organized as a **Modular Domain-Driven Monolith** (`app/Domains/`). The catalog introduces canonical `products`, `product_variants`, `product_media`, and `product_display_options` tables while legacy product compatibility remains part of the migration path.

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
│   │   ├── Archive/                      # Content backup/restore (Domain, Application, Infrastructure, Http, Jobs)
│   │   ├── Catalog/                      # Core Product Catalog (Domain, Application, Infrastructure, Http)
│   │   ├── Commerce/                     # Cart, Orders, Coupons, consultations (Domain, Application, Infrastructure, Http)
│   │   ├── Content/                      # CMS pages, projects, customer services (Domain, Application, Infrastructure, Http)
│   │   ├── Identity/                     # Authentication & User Management (Domain, Application, Infrastructure, Http)
│   │   └── Media/                        # Media upload pipeline & WebP optimization (Console, Http, Infrastructure)
│   ├── Http/                             # Shared controller base and retained compatibility classes
│   ├── Mail/                             # Compatibility aliases for queued Commerce mail payloads
│   ├── Notifications/                    # Queued notification compatibility shims
│   ├── Jobs/                             # Queue job compatibility shims (ContentArchiveJob)
│   ├── Models/                           # Legacy Catalog model aliases for serialized payloads
│   ├── Infrastructure/                   # Shared infrastructure such as view history
│   ├── Providers/                        # AppServiceProvider
│   ├── Rules/                            # Custom rules (e.g., YoutubeUrl)
│   ├── Support/                          # Shared asset path helper
│   └── View/Components/                  # Blade view components
├── config/                               # Application configurations
├── database/
│   ├── migrations/                       # 24 migration files (Canonical catalog + CMS + commerce)
│   └── seeders/                          # English-named seeders and SQL data fixtures
├── resources/views/
│   ├── admin/                            # Admin Blade templates by domain (catalog, content, commerce, identity, archive)
│   ├── clients/                          # Client storefront templates by domain (catalog, content, commerce, identity)
│   └── components/                       # Blade view components (admin, client, emails, vendor/mail)
│       ├── admin/                        # Admin layout shell, shared UI, and catalog tabs/modals
│       ├── client/                       # Client layout shell, shared UI, and domain sections (catalog, content, commerce, identity)
│       ├── emails/                       # Domain-organized Markdown emails (identity/auth, commerce/orders, commerce/consultation, content/contact)
│       └── vendor/mail/                  # Retained Markdown email HTML/text overrides and CSS theme
├── routes/
│   ├── web.php                           # Admin routes
│   ├── client.php                        # Client SEO routes
│   └── domains/                          # Route files split by domain context
└── tests/
    └── Feature/ & Unit/                  # Feature and unit tests
```

## Models Breakdown

### 1. Canonical Catalog Models (`app/Domains/Catalog/Infrastructure/Models`)
- `Product`: Central entity representing a product item across all categories. Stores `type_key`, dimensions, weight, rating criteria, priority, and visibility.
- `ProductVariant`: Pricing and SKU entity. Supports multi-variant products (e.g. Ngói Hài Văn Miếu) and single-variant defaults.
- `ProductMedia`: Polymorphic media gallery storing cover images, gallery photos, uploaded MP4 videos, and external YouTube embeds with unique sequence ordering.
- `ProductDisplayOption`: Category or product-level color swatches and finish options.

### 2. CMS Section Models (`app/Domains/Catalog/Infrastructure/Models`)
Single-row configuration records controlling the marketing intro and landing sections for each category:
`YinYangRoofTile`, `VanMieuFishScaleRoofTile`, `BreezeBlock`, `RoofTileAccessory`, `DecorativeTile`, `CeramicBalustrade`, `BatTrangAntiqueBrick`, `FengShuiCreature`, `CeramicLamp`.

### 3. Commerce Models (`app/Domains/Commerce/Infrastructure/Models`)
- `Order`: Full order lifecycle tracking with auto-generated order codes (`THC-YYYYMMDD-XXXX`), status workflows, and email notifications.
- `OrderItem`: Snapshot of ordered items (product_type, product_id, variant_id, price, quantity).
- `Coupon`: Discount code engine (percentage / fixed discount, product-type restriction, usage limits).
- `ConsultationRequest`: Submitted consultation snapshots. Legacy names under `Commerce/Models` remain aliases for queued payload compatibility.

### 4. Content & Showcase Models (`app/Domains/Content/Infrastructure/Models`)
- `HomePageConfig`: Home page dynamic configuration (hero slides, partner logos, statistics, showroom gallery).
- `Project` & `ProjectCategory`: Portfolio project showcase with category filtering and gallery images.
- `InstallationGuide`: Technical installation guides with YouTube video links.
- `Catalog`: Downloadable PDF product catalogs with an integrated flipbook reader.
- `PageFactory`, `PageContact`, `PageFaq`, `Faq`: Static and informational page configurations.

---

## Product controllers

Admin and client product controllers reside in `app/Domains/Catalog/Http`. They preserve the existing route URIs, names, and category-specific Blade views. The remaining classes in root `app/Http` are the shared controller base or compatibility classes with callers; domain behavior belongs to its context.
