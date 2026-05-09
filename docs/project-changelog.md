# Project Changelog

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
