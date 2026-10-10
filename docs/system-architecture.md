# System Architecture

## Architecture Overview

The Laravel application groups delivery, use cases, business rules, and adapters by six contexts in `app/Domains/`: `Catalog`, `Commerce`, `Content`, `Identity`, `Media`, and `Archive`. Route files in `routes/` preserve public URIs and names while dispatching to context-owned HTTP controllers. The composition root in `bootstrap/app.php` and `app/Providers/AppServiceProvider.php` wires middleware aliases and ports to adapters.

| Layer | Responsibility | Dependency direction |
|---|---|---|
| `Domain` | Framework-free business rules | No Laravel, HTTP, Eloquent, or other context |
| `Application` | Use cases and ports | Own `Domain` and declared ports |
| `Infrastructure` | Eloquent, storage, queue, mail, and cross-context adapters | Implements inward contracts |
| `Http` and `Console` | Requests, controllers, middleware, commands | Invokes application services |

There is no class-alias layer: application code, tests, and seeders import the canonical class under `Infrastructure` or `Domain`. Per-type product rules (detail route, variants, category grouping, variant label, price display) live in `Catalog\Domain\ProductTypeRegistry`; request validation derives its type list from it, and Commerce reads the list through `SellableProductTypesPort`. Shared `AssetPath` remains in `app/Support`; shared view history lives in `app/Infrastructure`. Catalog services, gallery helpers, and product presenters live in Catalog. The dependency rules are checked by `tests/Unit/Architecture`.

## Context ownership

- **Catalog:** products, variants, media, public IDs, search, product administration, client product storefront pages (`clients/catalog/products/*`), and catalog reusable components (`components/client/catalog/*`, `components/admin/catalog/*`).
- **Commerce:** carts, checkout, coupons, orders, consultations, order tracking (`clients/commerce/*`), admin commerce views (`admin/commerce/*`), commerce modals (`components/client/commerce/*`), and commerce mail templates (`components/emails/commerce/*`).
- **Content:** CMS pages, projects, news, FAQs, policies, showroom, factory (`clients/content/*`), admin CMS editors (`admin/content/*`), content section components (`components/client/content/*`), contact mail templates (`components/emails/content/*`), and the admin content-write gate.
- **Identity:** users, authentication, role gate, admin dashboard/user management (`admin/identity/*`), client auth/profile (`clients/identity/*`), and auth notification email templates (`components/emails/identity/*`).
- **Media:** image optimization, staged uploads, and media commands.
- **Archive:** export/import, legacy archive adaptation, content archive admin view (`admin/archive/*`), and the import job. The job owns a tokenized content-write lock for its write window.
- **Shared / Layouts:** Cross-domain layout shells (`components/client/layouts/`, `components/admin/layouts/`), generic UI (`components/client/shared/`, `components/admin/shared/`), and retained vendor mail overrides (`components/vendor/mail/`).

The root HTTP controller base and compatibility classes are not the owners of domain behavior. Product administration and client controllers live under `app/Domains/Catalog/Http`.

### Presentation Layer & View Delivery Ownership

Data delivery into Blade templates follows strict boundary rules:
- **No Blade Queries**: Blade templates are pure presentation layers. Direct Eloquent queries, `DB::` facade calls, and `@inject` service lookups are forbidden in `.blade.php` files.
- **Data Preparation**: View data is prepared by Context HTTP Controllers, Presenters (`CatalogHttpPresenter`), ViewComposers (`ContentViewComposer` for cached contact config & core values; header composer for cart counts; sidebar composer for pending consultation badges), or class-backed ViewComponents (`ProductCard`, `ProductDetailContainer`, `Recommendations`, `Works`, `WorksSimple`, `OutstandingValue`, `CouponBanner`, `AwardsDeck`). Coupon and award components query only when their input is omitted or null; an explicitly supplied empty collection is preserved.
- **Props Contracts**: Templates declare explicit parameters via `@props` with sensible defaults.
- **Component Registrations**: Domain tags (e.g. `<x-client.catalog.shared.product-card />`) are primary; `AppServiceProvider` provides aliases and anonymous paths to ensure existing callers remain unbroken.

## Database ER Overview

```
Canonical Catalog Core Tables (Unified)
│
├── products (Central Product Entity)
│   ├── Columns: id, public_id, type_key, category_type, name, slug, kich_thuoc,
│   │            khoi_luong, quy_cach, dong_goi, do_hut_nuoc, nhiet_do_nung,
│   │            priority, is_active, is_delete, timestamps
│   │
│   ├── product_variants (1:N variants, pricing & SKUs)
│   │   └── Columns: id, product_id, public_id, sku, name, price, is_default, is_active
│   │
│   ├── product_media (1:N polymorphic media items)
│   │   └── Columns: id, product_id, kind (image/video/youtube), path, title,
│   │                is_cover, sort_order (offset protected)
│   │
│   └── product_display_options (Color & Material swatches)
│       └── Columns: id, type_key, product_id, name, color_code, image, sort_order
│
├── CMS Section Configuration Tables (Single-row per category for landing page copy)
│   ├── ngoi_am_duong (banner, intro, video, section images)
│   ├── ngoi_hai_van_mieu
│   ├── gach_hoa_thong_gio
│   ├── gach_trang_tri
│   ├── gach_co_bat_trang
│   ├── linh_vat_phong_thuy
│   ├── den_gom_su
│   ├── phu_kien_ngoi
│   └── lan_can_gom_xu
│
├── Content & Showcase Tables
│   ├── trang_chu (home config: banners, partners, stats)
│   ├── du_an & danh_muc_du_an (project showcase)
│   ├── thi_cong & catalog (installation guides & flipbook PDFs)
│   └── giai_thuong_thanh_tuu & gia_tri_vuot_troi
│
├── Commerce Tables
│   ├── orders (order_code, status, totals, customer details)
│   ├── order_items (order_id, product_id, variant_id, sku, price, quantity)
│   └── coupons (code, type, value, usage limits, date windows)
│
└── Identity & Operations
    ├── users (admin & customer accounts, role RBAC)
    ├── jobs & failed_jobs (database queue for emails)
    └── sessions & cache
```

---

## Request flow examples

- Admin product requests pass through the role and content-write middleware, then a Catalog HTTP controller and Catalog application/infrastructure services. The public route contract remains stable.
- Client product requests enter Catalog HTTP controllers and render category-specific Blade views. Commerce accesses catalog data through its declared port and adapter.
- Archive import runs from `ContentArchiveJob`. It acquires an owner-token lock before importing and releases only its own lock in `finally`. Manual lock commands cannot replace or release the job lock. Legacy catalog rows pass through Archive's `CatalogArchivePort`; its infrastructure adapter owns Catalog persistence and public-ID allocation.

## Architecture Decision Records (ADR)

### ADR-001: Clean Architecture Domain Refactor & Administrative Boundary Protection (2026-09-29)

- **Status**: Accepted. Current behavior is checked by the test suite and route inventory; the plan acceptance criteria remain the completion authority.
- **Context**:
  - Codebase previously had business logic split between `app/Http/Controllers`, `app/Services`, `app/Models`, and `app/Domains/`.
  - Class naming mixed Vietnamese and English with ambiguous abbreviations (e.g. `Ct`).
  - Security audit identified that `customer` role was not gated on administrative routes (`routes/web.php`), and Google OAuth callback on client flow permitted auto-linking to administrative (`admin`, `superadmin`) accounts.
- **Decisions**:
  1. **Strict Clean Architecture Layering**: Within each bounded context (`Catalog`, `Commerce`, `Content`, `Identity`, `Media`, `Archive`), `Domain` contains pure PHP business rules (no Laravel dependencies); `Application` contains use cases and ports; `Infrastructure` contains Eloquent models and external adapters; `Http` contains controllers, requests, and presenters.
  2. **English Class Naming**: All project-defined PHP classes will be standardized to English according to the approved glossary (`plans/260929-1002-clean-architecture-domain-refactor/reports/inventory-and-glossary.md`). Database tables, column names, route URIs, route names, and view keys remain strictly unchanged to preserve backward compatibility.
  3. **Administrative Boundary Hardening**: Before any class moves, enforce role-based access control (`role:superadmin,admin`) on the authenticated admin route group in `routes/web.php` immediately following `auth` (preceding staged images and content write gates), deny `customer` access at admin login, and block Google OAuth client callback from auto-linking to privileged administrative accounts.
- **Consequences**:
  - Architecture dependency rules will be verified via automated Pest/PHP unit tests (`token_get_all`).
  - Route contract baseline is snapshotted before and after authorization hardening.

### ADR-002: Blade Views Architecture Optimization & Domain Organization (2026-10-02)

- **Status**: Accepted.
- **Context**:
  - Codebase had 286 Blade views (42,254 lines) across flat directories (`resources/views/{admin,clients,components}`) that did not match the domain architecture.
  - Hotspot templates contained heavy duplicate markup, inconsistent props bags, and direct database queries (e.g. coupon banner, awards deck, sidebar counts).
- **Decisions**:
  1. **Domain-Aligned View Topology**: Group views by domain under `resources/views/{admin,clients,components}/{catalog,content,commerce,identity,archive}/` while preserving all functional folder names, Vietnamese basenames, and public route URIs.
  2. **Strict Query Extraction**: Relocate queries and service resolutions out of Blade templates into Domain HTTP controllers, presenters, ViewComposers (`ContentViewComposer`), or class-backed ViewComponents.
  3. **Explicit Props & Component Contracts**: Replace monolithic prop bags with granular `@props` declarations. Retain class component bindings and anonymous component paths in `AppServiceProvider`.
  4. **Zero Route Delta & Invariant Behavior**: Preserve all 385 routes, route names, middleware order, SEO URLs, DOM data hooks, and e-commerce snapshot semantics.
- **Consequences**:
  - Zero database queries remain in Blade files.
  - Template compile caching (`php artisan view:cache`) verifies syntax cleanly across all 286 views.
  - Full Pest test suite passes (370+ tests) with automated query-count and session-isolation verification.
