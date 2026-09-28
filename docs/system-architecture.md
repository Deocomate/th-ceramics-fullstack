# System Architecture

## Architecture Overview (Modular Domain Architecture)

The system is architected as a **Modular Domain-Driven Monolith** built on **Laravel 12**, transitioning from a legacy flat layered structure into distinct **Bounded Contexts** under `app/Domains/`, while retaining thin HTTP delivery endpoints in `app/Http/`.

```
┌────────────────────────────────────────────────────────────────────────┐
│                              HTTP Request                              │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│                           Laravel Router                               │
│  ┌───────────────────────┐  ┌───────────────────────┐  ┌────────────┐  │
│  │ routes/web.php        │  │ routes/client.php     │  │ console.php│  │
│  │ (Admin, requires auth)│  │ (Public SEO routes)   │  │ (Artisan)  │  │
│  └───────────┬───────────┘  └───────────┬───────────┘  └────────────┘  │
│              │                          │                              │
│              └─────────► routes/domains/◄──────────────────────────────┘
│               (catalog, commerce, content, identity, media, archive)
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│                          Middleware Stack                              │
│  ┌───────────┐ ┌──────────────┐ ┌──────────────┐ ┌───────────────────┐ │
│  │ web group │ │ auth / guest │ │ role:admin   │ │ ecommerce toggle  │ │
│  │ (session) │ │ (redirect)   │ │ superadmin   │ │ (EnsureEcommerce) │ │
│  └───────────┘ └──────────────┘ └──────────────┘ └───────────────────┘ │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│                    Delivery / Controller Layer                         │
│                                                                        │
│  ┌─────────────────────────────────┐ ┌──────────────────────────────┐  │
│  │ Domain HTTP (Embedded)          │ │ Application HTTP (app/Http)  │  │
│  │ - Identity: Admin & Client Auth │ │ - Admin: Thin Product Stubs  │  │
│  │ - Commerce: Cart & Checkout     │ │   extending Base Controllers │  │
│  │ - Media: Staged uploads         │ │ - Client: 9 Dedicated Blade  │  │
│  │ - Archive: Export/Import/Writes │ │   Product Page View Runners  │  │
│  │ - Catalog: Base Controllers     │ │ - DichVuKhachHang View Ctrls │  │
│  └────────────────┬────────────────┘ └──────────────┬───────────────┘  │
└───────────────────┼─────────────────────────────────┼──────────────────┘
                    │                                 │
┌───────────────────▼─────────────────────────────────▼──────────────────┐
│                   Domain Logic & Service Layer                         │
│                                                                        │
│  ┌───────────────────┐ ┌────────────────────┐ ┌─────────────────────┐  │
│  │  Catalog Domain   │ │  Commerce Domain   │ │   Content Domain    │  │
│  │ - ProductWriter   │ │ - CartService      │ │ - TrangChu / DuAn   │  │
│  │ - CatalogQuerySvc │ │ - OrderService     │ │ - Page configs      │  │
│  │ - TypeRegistry    │ │ - CouponService    │ │ - GiaiThuong / Faq  │  │
│  │ - PublicIdAlloc   │ └────────────────────┘ └─────────────────────┘  │
│  └───────────────────┘ ┌────────────────────┐ ┌─────────────────────┐  │
│  ┌───────────────────┐ │    Media Domain    │ │   Archive Domain    │  │
│  │  Identity Domain  │ │ - Upload Pipeline  │ │ - ContentArchiveSvc │  │
│  │ - AuthService     │ │ - WebP Image Opt   │ │ - LegacyV1Adapter   │  │
│  │ - Role RBAC       │ │ - Staged file temp │ │ - Canonical ZIP     │  │
│  └───────────────────┘ └────────────────────┘ └─────────────────────┘  │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│                    Canonical Model / Data Layer                        │
│                                                                        │
│  - Catalog Core: Product, ProductVariant, ProductMedia, DisplayOption  │
│  - Commerce: Order, OrderItem, Coupon                                  │
│  - Content: TrangChu, DuAn, DanhMucDuAn, ThiCong, Catalog, Page*       │
│  - Identity: User                                                      │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│                           Database Layer                               │
│  - MariaDB (th_ceramics_fullstack): 24 migrations, unified schema      │
│  - Session, Cache, Queue: database driver                              │
└────────────────────────────────────────────────────────────────────────┘
```

---

## Bounded Contexts (Domains) Breakdown

The application business logic is segmented into 6 domain contexts under `app/Domains/`:

### 1. Catalog Domain (`app/Domains/Catalog`)
The single source of truth for all product data, variants, media galleries, display options, and pricing:
- **`Models/`**:
  - `Product`: Central entity storing common attributes (`type_key`, `category_type`, dimensions, weight, rating criteria, priority, status).
  - `ProductVariant`: SKU (`code`), selling price, classification, default variant flag.
  - `ProductMedia`: Polymorphic media gallery (cover images, gallery photos, uploaded MP4 videos, external YouTube links, with strict `sort_order`).
  - `ProductDisplayOption`: Category or product-level color swatches and display materials.
- **`Services/ & Logic`**:
  - `ProductWriter`: Atomic mutation engine for creating, updating, deleting/restoring products, validating SKU uniqueness, processing gallery reordering, and attaching media.
  - `CatalogQueryService`: Optimized query engine for pagination, price sorting, search, filtering, and cross-category recommendations.
  - `ProductTypeRegistry`: Static registry defining all 10 product groups, their route names, views, table aliases, and variant configurations.
  - `PublicIdAllocator`: Maintains deterministic public identifiers (`public_id`) per product type, guaranteeing 100% backward compatibility for existing SEO URLs.
- **`Http/Admin/`**:
  - `BaseProductItemController`: Abstract controller providing robust CRUD, gallery management, video embeds, drag-drop ordering, and restore actions for standard product items.
  - `BaseProductVariantController`: Abstract controller handling multi-variant product groups (e.g., Ngói Hài Văn Miếu, Ngói Hài Cổ).

### 2. Commerce Domain (`app/Domains/Commerce`)
Handles shopping cart, checkout, coupon validation, and order management:
- **`Models/`**: `Order`, `OrderItem` (records snapshot of product title, price, code at time of purchase), `Coupon`.
- **`Services/`**: `CartService` (session-based cart, mini-cart, option calculations), `CouponService` (percent/fixed discount calculation, validity, usage limits).
- **`Http/`**: `CartController` (AJAX add/update/remove, coupon application), `OrderController` (admin fulfillment, status workflow).

### 3. Content Domain (`app/Domains/Content`)
Manages marketing pages, dynamic home page blocks, and CMS sections:
- **`Models/`**: `TrangChu` (home hero banner, partners, stats, showroom JSON arrays), `DuAn` & `DanhMucDuAn` (project showcase with categories), `ThiCong` (installation guides), `Catalog` (PDF flipbook brochures), `GiaiThuongThanhTuu` (awards), `GiaTriVuotTroi` (brand value propositions), `PageFactory`, `PageContact`, `PageFaq`.
- **`Services/`**: `TrangChuService`, `DuAnService`, `CatalogService`, `ThiCongService`, etc.

### 4. Identity Domain (`app/Domains/Identity`)
Handles user authentication, password resets, and role-based access control:
- **`Models/`**: `User` (with `role` field: `superadmin`, `admin`, `customer`).
- **`Services/`**: `AuthService`.
- **`Http/`**: `Admin\AuthController`, `Client\AuthController` (credentials + Google Socialite OAuth).

### 5. Media Domain (`app/Domains/Media`)
Manages media uploads, temporary staged uploads, and WebP image optimization:
- **`Services/`**: `StagedImageUploadService`, `FileUploadHelper` (resize, format conversion to WebP).
- **`Http/`**: Endpoints for handling chunked and asynchronous media uploads.

### 6. Archive Domain (`app/Domains/Archive`)
Provides complete data portability and content backup/restore:
- **`Services/`**: `ContentArchiveService` exports/imports a single verified `database.zip` bundle.
- **`Adapters/`**: `LegacyV1ArchiveAdapter` seamlessly reads archives from the legacy schema (from `main` branch) and backfills into canonical `Product` models without requiring legacy DB tables.

---

## Architectural Analysis: Why Product Controllers are in `app/Http/`

### 1. The Rationale for Current Controller Placement
In the current project structure, while the core Catalog logic lives in `app/Domains/Catalog`, the individual product controllers remain in:
- `app/Http/Controllers/Admin/*CtController.php` (e.g. `NgoiAmDuongCtController`, `GachCoBatTrangCtController`)
- `app/Http/Controllers/Client/ProductPages/*.php` (e.g. `NgoiAmDuongController`, `LanCanGomSuController`)

This structure exists due to 3 deliberate engineering reasons:

1. **Separation of Domain Core vs Application Delivery**:
   - The **Domain** contains business logic, database transactions, invariant enforcement, media attachments, and query rules (`ProductWriter`, `CatalogQueryService`, `BaseProductItemController`).
   - The **Application HTTP layer** (`app/Http`) acts as the delivery mechanism for specific routes, form views, and Blade layouts.
2. **Contract Preservation & Blast Radius Minimization**:
   - The application has **385 registered routes** and **293 automated test cases**.
   - Admin controllers in `app/Http/Controllers/Admin` were refactored into **declarative thin subclasses** extending `BaseProductItemController`. Each file is only ~20 lines long, defining only `$typeKey`, `$viewPrefix`, and custom rules.
   - Leaving them in `app/Http/Controllers/Admin` preserved all route references (`routes/domains/catalog.php`), guest preview URLs (`@section('preview_url')`), and existing test namespaces without requiring an invasive rename across hundreds of files simultaneously.
3. **Distinct Blade Client Templates**:
   - Unlike generic e-commerce products, each ceramic product category (Ngói Âm Dương, Gạch Thông Gió, Đèn Vườn...) features a distinct page experience: custom tile calculators, installation diagrams, dynamic dimension matrices, and category video journeys. The client controllers in `app/Http/Controllers/Client/ProductPages` specifically orchestrate these diverse Blade presentations.

---

## Is This Structure Optimal? (Evaluation & Roadmap)

| Criteria | Status | Evaluation |
| :--- | :---: | :--- |
| **Business Logic DRY** | **Optimal** | Zero code duplication. 16 legacy services and 16 models were eliminated. All CRUD, image/video processing, priority, and SKU checking are centralized in `ProductWriter` and `BaseProductItemController`. |
| **System Stability & Tests** | **Optimal** | **293/293 test cases pass (100% Green)**. 385 routes remain functional with zero regressions. |
| **Directory Uniformity** | **Sub-optimal (Hybrid)** | Inconsistent with other domains. Domains like `Identity`, `Commerce`, and `Content` encapsulate their own `Http/` controllers, whereas `Catalog` has its base classes in `app/Domains/Catalog/Http/` while derived controllers remain in `app/Http/Controllers/`. |
| **Controller Boilerplate** | **Acceptable (Transitional)** | Having 10 separate admin controllers that only declare `$typeKey` is safe and clear, but creates 10 boilerplate files where a single parameterized controller could suffice. |

### Recommended Future Refinement (Phase 2):
1. **Move Controllers into Domain Context**:
   - Relocate `app/Http/Controllers/Admin/*CtController.php` to `app/Domains/Catalog/Http/Admin/`.
   - Relocate `app/Http/Controllers/Client/ProductPages/` to `app/Domains/Catalog/Http/Client/`.
   - Update `routes/domains/catalog.php` to point to the new domain namespace.
2. **Consolidate Admin CRUD with Dynamic Parameterization**:
   - For standard single-variant products, route `/admin/{type}` directly to a single `CatalogProductController` with route-model/type binding via `ProductTypeRegistry`.

---

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

## Request Flow Examples

### 1. Admin Product Update
```
1. Admin submits PUT /admin/ngoi-am-duong-ct/{id}
2. Route matches -> NgoiAmDuongCtController@update (extends BaseProductItemController)
3. BaseProductItemController delegates payload to ProductWriter::update($product, $data)
4. ProductWriter:
   - Validates unique SKU across product variants
   - Updates `products` and default `product_variants` in DB::transaction()
   - Reorders media gallery (applying +10000 sort_order offset to avoid UNIQUE collisions)
   - Synchronizes new uploaded images and YouTube video URLs
5. Fresh Product loaded, redirect back with flash message
```

### 2. Client Product Page
```
1. Guest visits GET /san-pham/ngoi-am-duong/{id}
2. Route matches -> Client\ProductPages\NgoiAmDuongController@detail
3. Controller:
   - Queries product via CatalogQueryService::findActive('ngoi_am_duong_ct', $id)
   - Tracks recent view history via ViewHistoryService
   - Queries category color swatches from ProductDisplayOption
   - Resolves category-specific YouTube journey video via ProductJourneyVideo
4. Renders Blade view `clients.products.ngoi-am-duong.detail` with structured JSON-LD
```
