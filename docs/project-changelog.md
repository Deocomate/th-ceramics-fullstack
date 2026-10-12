# Project Changelog

## 0.7.0 (2026-10-12)

### Added
- **Protection context** (`app/Domains/Protection`): two admin switches on Hệ thống › Cài đặt website for copy deterrence (right-click on the whole page, drag on media, shortcut blocking) and DevTools detection. Detections are stored in `protection_violations` (pruned after 90 days); the third one in 24 hours leads to a legal warning page. Crawlers and admins never receive the scripts.
- While either switch is on, a browser with JavaScript turned off sees only a request to turn it on; the page content stays hidden. Crawlers and admins are not affected.
- **Security headers** on every web response: `X-Frame-Options: SAMEORIGIN`, `Content-Security-Policy: frame-ancestors 'self'`, `X-Content-Type-Options`, `Referrer-Policy`.
- **Rewrite rules** in both `.htaccess` files that refuse site-copy User-Agents and cross-site requests for images, videos and PDFs.
- **`media:convert-webp`** converts legacy images over 1 MB or over their preset dimensions to WebP, rewrites database references and keeps originals on the private disk, with `--verify` and `--restore`. Old JPG/PNG URLs answer 301 to the WebP file.
- **`media:privatize`** moves catalog PDFs and self-hosted videos to the private disk.
- **`scripts/verify-content-protection.php`** checks all of the above against a live site.
- Seven routes: `client.protection.report`, `client.protection.notice`, `client.protection.warning`, `client.media.video`, `admin.catalog.file`, `admin.catalog.pages.store`, `admin.catalog.pages.finalize`. No existing route changed.

### Changed
- **Admin navigation** is grouped into six modules shown as topbar tabs (Tổng quan, Bán hàng, Sản phẩm, Nội dung, Giao diện, Hệ thống); the sidebar lists only the current module and the breadcrumb follows the same tree (`config/admin_navigation.php`). FAQ moved under Nội dung, content backup and admin accounts under Hệ thống.
- **Site settings page** (`/admin/cai-dat`, `admin.settings.*`) now holds the e-commerce switch and the two content protection switches, which left the home page form. Saving either page no longer touches the other's fields.
- **Catalog reader** shows page images rendered in the admin browser; the original PDF is reachable only by admins and catalog uploads accept PDF only.
- **Self-hosted product videos** play through expiring signed URLs under `/media/video/`.
- **Client JS and CSS** moved from `public/assets` to `resources/js` and `resources/css` and are served as committed minified builds (`composer run build:assets`); the old source URLs answer 404.
- **Client pagination** (product lists, projects, news category, related news) swaps the list in place instead of reloading the page: the next page is fetched when the visitor aims at a link, the URL and browser history are kept in step, and the links still navigate normally without JavaScript (`resources/js/pagination-navigation.js`, lists marked with `data-pagination-region`).
- **Per-IP throttle** of 120 requests a minute on client pages (`CONTENT_PROTECTION_PAGE_RATE_LIMIT`).

### Fixed
- Developer tools opened after the page had loaded (for example through right-click › Inspect) often went undetected: the bundled detector compares `console.table` against the slowest `console.log` of the whole page life, so one slow call blinded it until the next load. `content-protection.js` now runs its own per-tick timing comparison next to the library.
- The context menu is blocked on the whole page, not only on media, whenever either protection switch is on, because its Inspect entry opens the developer tools.
- The hotlink allowlist accepted any Referer whose host merely started with `google.` (for example `google.example.com`); it now accepts Google's own domains only.

## 0.6.0 (2026-09-28)

### Added
- **Modular Domain Architecture**: Restructured application into 6 Bounded Contexts under `app/Domains/` (`Catalog`, `Commerce`, `Content`, `Identity`, `Media`, `Archive`).
- **Canonical Product Catalog**: Replaced 16 fragmented product detail tables with a unified 4-table canonical schema (`products`, `product_variants`, `product_media`, `product_display_options`).
- **ProductWriter & CatalogQueryService**: Centralized atomic mutation engine with SKU uniqueness checking, transactional media sort ordering (with collision prevention), and optimized queries.
- **BaseProductItemController & BaseProductVariantController**: Abstract CRUD controllers under `app/Domains/Catalog/Http/Admin/` standardizing admin workflows across all 10 product categories.
- **Two-Way Archive Compatibility**: Added `LegacyV1ArchiveAdapter` to export/import single `database.zip` archives, enabling seamless cross-branch migration between legacy schema and canonical schema.
- **Full Test Suite Verification**: Achieved 293/293 passing tests (100% green).

### Changed
- **Admin Product Controllers**: Refactored in-place into thin subclasses of `BaseProductItemController` in `app/Http/Controllers/Admin/` to preserve 385 existing routes and preview bindings.
- **Deleted Legacy Code**: Removed 16 obsolete `*Ct` Eloquent models and 16 duplicate service classes.

## 0.5.7 (2026-07-11)

### Added
- **Catalog AJAX Upload & Real-time Progress Bar**: Migrated catalog store and update actions to support AJAX requests. Added client-side size and extension pre-validation. Created a dynamic progress bar that shows upload progress in real-time, disables submit buttons, and displays spinners to prevent double submission.
- **Inline Validation Error Feedback**: Server-side validation errors (422) are returned as JSON and displayed inline without closing the edit modal or losing form states. General failures are handled and outputted inside the modal/form.
- **Infrastructure Guidelines**: Documented maximum request body size (`client_max_body_size 220M`) and FastCGI timeouts (`fastcgi_read_timeout 300s`) for Nginx, as well as PHP-FPM upload limits and timeout parameters (`upload_max_filesize = 220M`, `post_max_size = 220M`, `max_execution_time = 300`, `max_input_time = 300`) in `docs/deployment-guide.md` to support uploads up to 200MB.
- **Eloquent Factory & Automated Testing**: Added `HasFactory` trait to the `Catalog` model, created `CatalogFactory`, and implemented Pest feature tests in `CatalogUploadTest` verifying AJAX store, update, validation limits, and file deletion.

## 0.5.6 (2026-05-15)

### Added
- **Lan Can Gom Su Detail Page**: Rebuilt product detail with variant selection (dynamic SKU/price swap via JS), image gallery (thumbnail click to swap main image), quantity control + add-to-cart form (session cart, `lan_can_gom_su_ct` product type), specs list from `des` JSON, contact block (phone + Zalo), and technical drawing section (`size_image` + `size_des` fields)
- **JSON-LD Structured Data**: Product schema with `@type: Product`, brand, offers, price, availability on each Lan Can Gom Su detail page
- **Desktop Product Card Component** (`components/products/desktop-product-card.blade.php`): Reusable card with customizable `aspectClass` and `class` props, hover overlay with "Xem chi tiet" CTA, displays title/code/price
- **LanCanGomSuCt Accessors**: `display_code` (returns first active variant code or "Dang cap nhat") and `display_price` (returns min active variant price formatted as "Gia: X d/m2" or "Lien he")

### Changed
- **Lan Can Gom Su Index Page**: Now fully dynamic -- queries `LanCanGomSuCt` model via `LanCanGomSuCtService::getAll('active')`, passes `$products` collection to view, section 1/2 product grids use `<x-products.desktop-product-card>` with dynamic `display_code`/`display_price`
- **Lan Can Gom Xu Admin Config**: Extended with 6 new section fields (`section_1_image`, `section_1_title`, `section_1_products`, `section_2_image`, `section_2_title`, `section_2_products`) via migration, all JSON-cast in model
- **CartService**: Now supports `lan_can_gom_su_ct` product type via `getLanCanGomSuDetails()` method (queries `LanCanGomSuCt` + `PhanLoaiLanCanGomSuCt`)
- **Header Padding**: Updated to `pt-[58px] xl:pt-[108px]` on both index and detail Lan Can Gom Su pages

## 0.5.5 (2026-05-11)

### Added
- **Admin Preview-as-Guest Floating Button**: A fixed FAB button in the admin panel that maps admin routes to their corresponding client-side URLs for quick guest-view verification. Supports route-to-URL mapping and page-level overrides via `@section('preview_url')`.

### Changed
- **Home Page Fully Dynamic**: All home page sections now pull data from the database
  - `HomeController::index()` queries 5 models: `TrangChu` (banner, partners, thank-you, press logos, stats, showroom), `DuAn` (projects carousel, latest 10), `NgoiAmDuongCt`, `NgoiHaiVanMieuCt`, `GachHoaThongGioCt` (product sections, 8 each)
  - 13 view files updated: `banner.blade.php` (dynamic slides/dots from `TrangChu->banner`), `partners.blade.php` (dynamic logos from `TrangChu->khach_hang_doi_tac`), `thank-you.blade.php` (dynamic paragraphs from `TrangChu->loi_tri_an`), `press.blade.php` (dynamic logos from `TrangChu->ve_chung_toi_logo`), `why-choose-us.blade.php` (dynamic stats from `TrangChu->nhung_con_so`), `showroom.blade.php` (dynamic images from `TrangChu->showroom_images`), `works.blade.php` (dynamic projects from `DuAn`), `product-section.blade.php` (refactored to accept `$products` collection), 3 product-specific partials (`products-ngoi-am-duong`, `products-ngoi-hai`, `products-gach-hoa`) simplified to pass collections, `home-awards.blade.php` (self-queries `GiaiThuongThanhTuu`)

## 0.5.4 (2026-05-10)

### Added
- **Project Module Client Dynamic**: Full dynamic project showcase system
  - `DuAnSeeder` -- seeds 5 project categories (`DanhMucDuAn`: Ngoi Am Duong, Ngoi Hai - Van Mieu, Gach Thong Gio, Gach Trang Tri, San pham khac) + 20 real Vietnamese projects (Chua Bai Dinh, Thien Vien Truc Lam, etc.) with deterministically assigned image arrays and unique slugs
  - `DatabaseSeeder` updated to include `DuAnSeeder::class` (7 seeder calls now)
- **Dynamic Client Project Index** (`/du-an`): Category filters via mobile dropdown + desktop tabs, project grid with pagination (8 per page), empty state fallback, image thumbnails with hover zoom
- **Dynamic Client Project Detail** (`/du-an/{slug}`): Hero banner with overlay, meta bar (dia_diem, nam, san_pham), 3-row staggered gallery grid (GLightbox on desktop), Swiper carousel (mobile), related projects from same category (limit 4), CTA section
- `DanhMucDuAn` and `DuAn` Eloquent models documented (table `du_an`, PK `du_an_id`, `images` JSON cast, `danhMuc()` BelongsTo relationship)

## 0.5.3 (2026-05-10)

### Changed
- **Seeder System Rewrite**: All 6 seeders rewritten from raw SQL/lorem ipsum to 100% real data
  - `UserSeeder`: Verified (no changes needed, already idempotent with firstOrCreate)
  - `DinhMucSeeder`: Changed truncate to firstOrCreate, expanded sizes for gach_trang_tri (5 sizes), gach_hoa_thong_gio (4 sizes), gach_co_bat_trang (4 sizes)
  - `PageConfigSeeder`: Changed create to firstOrCreate, replaced placeholder images with real images from assets/images/, added real factory/contact/FAQ content
  - `HomeAndAboutUsSeeder`: Complete rewrite from DB::unprepared() raw SQL to Eloquent, added GiaTriVuotTroi seeding (4 values, previously unseeded)
  - `ProductTypeSeeder`: Rewrite from DB::table()->insertOrIgnore() to Eloquent firstOrCreate, real product images replacing defaults/placeholder.png across all 9 parent categories
  - `ProductDetailSeeder`: Rewrite with string paths instead of File::copy/Storage::put, real Vietnamese SEO content, proper SKUs (NAD-, GTG-, GTT-, GCB-, LVP-, NHC-, NBN-, BNC-), category-specific pricing (tiles: 15k-80k, linh vat/den: 500k-3.5M)
  - `DatabaseSeeder.php`: Reordered seeder calls: User -> DinhMuc -> PageConfig -> HomeAndAboutUs -> ProductType -> ProductDetail

## 0.5.2 (2026-05-10)

### Added
- **Custom Password Reset Notification**: Branded Vietnamese email with role-based routing and queue support
  - `ResetPasswordNotification` extends Laravel's `ResetPassword`, implements `ShouldQueue`
  - Role-based reset URL routing: `isAdmin()` check routes admin users to `/admin/reset-password/{token}` and client users to `/tai-khoan/dat-lai-mat-khau/{token}`
  - `User::sendPasswordResetNotification()` override to dispatch custom notification
  - `resources/views/emails/auth/reset_password.blade.php` -- Vietnamese branded email with configurable expiration time from `auth.passwords.users.expire`
  - `tests/Feature/Auth/PasswordResetTest.php` -- 15 Pest tests covering both admin and client reset flows (all passing)

## 0.5.1 (2026-05-09)

### Added
- **Dynamic Customer Service Pages**: Installation guide, catalog list, and PDF flipbook reader
  - `HuongDanThiCongController::index()` wired to `ThiCong` model -- queries all records ordered by newest, passes `$guides` to view
  - `installation-guide-content.blade.php` -- dynamic `@forelse` loop with alternating zigzag layout (`md:order-1`/`md:order-2`), image thumbnails, conditional YouTube link buttons, empty state fallback
  - `CatalogController::index()` -- featured catalog (first record) + grid (remaining) split via `slice(1)`, passes to view
  - `catalog-content.blade.php` -- dynamic featured section with `@if($featuredCatalog)`, 2x3 grid `@forelse($catalogs as $item)`, "Xem chi tiet" buttons with `route()` links to flipbook reader, disabled state for catalogs without PDF file, empty state fallback
  - `CatalogController::read($id)` -- `findOrFail` with 404 guard when `file` is null, returns standalone flipbook page
  - Route `GET /dich-vu/tai-catalog/doc/{id}` registered as `client.dich-vu.tai-catalog.read`
  - `flipbook.blade.php` -- standalone full-screen HTML page (no `@extends`), dark background, PDF.js v2.16.105 for PDF rendering (scale 1.5), StPageFlip v2.0.7 for 3D page-flip animation, loading spinner overlay, back button, error state for failed PDF loads, responsive: 2-page spread on desktop, single page on mobile (<768px), both CDN-loaded dependencies (no npm install)
- `ThiCong` and `Catalog` models documented in codebase summary

## 0.5.0 (2026-05-09)

### Added
- **Order Management and Email Notification System**: Full order lifecycle from checkout to admin fulfillment
  - `Orders` table with 22 columns, auto-generated order codes (`THC-YYYYMMDD-XXXX`), 6 statuses (pending_payment, processing, shipping, completed, canceled, returned), COD-only payment, status label helper
  - `OrderItems` polymorphic table (product_type, product_id, variant_id) with price, quantity, unit tracking
  - Admin order list (paginated, latest first), order detail view with items breakdown + customer info
  - Admin status update with `OrderStatusUpdatedMail` notification (ShouldQueue via database queue)
  - Client dynamic order status tracking page (`/trang-thai-don-hang`) with tab filtering by status, status counts per tab, inline badge coloring, auth-gated
  - `OrderCreatedMail` dispatched on checkout (ShouldQueue, markdown template)
  - 3 email templates: order confirmation, status update notification, combined layout

## 0.4.0 (2026-05-09)

### Added
- **Coupon/Discount Module**: Full discount code system for checkout flow
  - `coupons` database table with 18 columns: title, description, code (unique), discount_type (percent/fixed), discount_value (decimal 12,2), max_discount_amount (nullable), min_order_value, applicable_product_types (json nullable), usage_limit (nullable), used_count, start_date, end_date (nullable), banner_image (nullable), show_banner, is_active, is_delete, timestamps
  - `coupon_code` column added to `orders` table
  - `Coupon` model with fillables, casts (float, integer, array, boolean, datetime), `isValid()` method checking active/deleted/date range/usage limit
  - `CouponService` with full CRUD (`getAll`, `getDeleted`, `findById`, `store`, `update`, `destroy`, `restore`, `forceDelete`) plus `validateAndCalculate()` (product-type filtering, min order check, percent/fixed calculation with max discount cap), `incrementUsage()`, `decrementUsage()`, `getCartSubtotal()`
  - `CartService` extended with session-based coupon methods: `setCoupon()`, `getCouponCode()`, `removeCoupon()`, `getSubtotal()`, `getDiscountAmount()`; `getTotal()` now subtracts discount
  - `Admin/CouponController` with full CRUD + restore routes, `CouponRequest` with Vietnamese validation messages
  - 3 admin Blade views: index (with status badges, usage stats), create, edit
  - 2 client AJAX endpoints: `POST /thanh-toan/ap-dung-ma` (applyCoupon), `POST /thanh-toan/go-ma` (removeCoupon)
  - Server-side coupon re-validation during checkout `processCheckout()` with `DB::transaction` for atomic usage increment
  - `coupon-banner.blade.php` reusable component with configurable banner image and show/hide toggle
  - Admin sidebar link with ticket icon for coupon management
  - Vanilla JS `fetch()` AJAX on checkout page for apply/remove coupon with dynamic total update

## 0.3.1 (2026-05-07)

### Added
- Page configuration admin panels: Factory (5-tab Alpine.js with auto-resize textareas), Contact (form), FAQ (banner config + FAQ items CRUD with modal)
- Dynamic client pages: Factory tour, Contact, FAQ now pull content from DB
- 12 new Pest tests for page configuration controllers

### Changed
- Replaced TinyMCE with auto-resize textareas using Alpine.js for all content fields
- Admin UI/UX Pro Max refactor across admin panel

## 0.3.0 (2026-05-06)

### Added
- Admin CRUD complete for all 9 product categories (section pages, detail items, sub-resources)
- Client product data binding via service layer
- Global product code uniqueness via GlobalProductCodeService
- RBAC system with RoleMiddleware (superadmin, admin roles)
- Admin authentication system

## 0.2.0 (2026-05-01)

### Added
- Product detail migrations for all 9 product categories
- Eloquent models with relationships, custom PKs, casts
- Service layer with business logic
- File upload system

## 0.1.0 (2026-04-26)

### Added
- Initial Laravel 12 project setup
- Product type tables migration
