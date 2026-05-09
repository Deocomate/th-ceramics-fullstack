# System Architecture

## Layered Architecture Overview

```
┌──────────────────────────────────────────────────┐
│                   HTTP Request                     │
└────────────────────┬─────────────────────────────┘
                     │
┌────────────────────▼─────────────────────────────┐
│              Laravel Router                        │
│  ┌─────────────┐  ┌──────────────┐               │
│  │ web.php      │  │ client.php   │               │
│  │ (Admin /admin)│  │ (Public)     │               │
│  └─────────────┘  └──────────────┘               │
└────────────────────┬─────────────────────────────┘
                     │
┌────────────────────▼─────────────────────────────┐
│              Middleware Stack                      │
│  ┌───────────┐ ┌──────────────┐ ┌──────────────┐ │
│  │ web group │ │ auth/guest   │ │ role:superadmin│ │
│  │ (session) │ │ (redirect)   │ │ (RBAC check)  │ │
│  └───────────┘ └──────────────┘ └──────────────┘ │
└────────────────────┬─────────────────────────────┘
                     │
┌────────────────────▼─────────────────────────────┐
│              Controller Layer                     │
│  ┌─────────────────────┐ ┌──────────────────────┐ │
│  │ Admin/Controller     │ │ Client/Controller    │ │
│  │ (thin: validate,     │ │ (thin: query,        │ │
│  │  delegate to service,│ │  return view)        │ │
│  │  return redirect)    │ │                      │ │
│  └─────────────────────┘ └──────────────────────┘ │
└────────────────────┬─────────────────────────────┘
                     │
┌────────────────────▼─────────────────────────────┐
│              Service Layer                        │
│  ┌─────────────────────────────────────────────┐ │
│  │ Business logic, DB operations, file uploads  │ │
│  │ DB::transaction() for atomic writes          │ │
│  │ FileUploadHelper for image management        │ │
│  └─────────────────────────────────────────────┘ │
└────────────────────┬─────────────────────────────┘
                     │
┌────────────────────▼─────────────────────────────┐
│              Model / ORM Layer                    │
│  ┌─────────────────────────────────────────────┐ │
│  │ Eloquent Models (42): relationships, scopes, │ │
│  │ custom PKs, casts, fillable/hidden attrs     │ │
│  └─────────────────────────────────────────────┘ │
└────────────────────┬─────────────────────────────┘
                     │
┌────────────────────▼─────────────────────────────┐
│              Database Layer                       │
│  ┌─────────────────────────────────────────────┐ │
│  │ MariaDB (th_ceramics_fullstack)              │ │
│  │ 42 tables across 9 migrations               │ │
│  │ Session, Cache, Queue: database driver      │ │
│  └─────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────┘
```

## Layer Descriptions

### 1. Router Layer
Two route files loaded under a single `web` middleware group in `bootstrap/app.php`:
- **`routes/web.php`**: All admin routes under `/admin` prefix, named `admin.*`
- **`routes/client.php`**: All public routes with Vietnamese SEO URLs, named `client.*`
- **`routes/console.php`**: Artisan commands

### 2. Middleware Stack
- **Default web middleware**: CSRF, sessions, etc. (Laravel defaults)
- **`auth` middleware**: Guest routes (login) redirect authenticated users to dashboard; protected routes redirect unauthenticated users to admin login
- **`role:superadmin`**: Restricts user management routes to superadmin only
- Guest/Auth redirects configured in `bootstrap/app.php`

### 3. Controller Layer
- **Admin controllers** (48 files): Thin controllers that validate requests, delegate to services, and return redirect responses with flash messages. OrderController handles list/detail/status-update with email notification.
- **Client controllers** (27 total): 10 page controllers + 8 DichVuKhachHang controllers + 9 product page controllers. Query services, return view responses with data. HuongDanThiCongController and CatalogController provide dynamic customer service pages. TrangThaiDonHangController provides dynamic order status tracking with tab filtering.
- All use constructor DI with `private readonly` service properties

### 4. Service Layer
- **36 service classes**: One per major model
- Contains business logic including file upload handling, transaction management, and code uniqueness checks
- No repository pattern — services interact directly with Eloquent models
- `GlobalProductCodeService`: Cross-table uniqueness validation for product codes

### 5. Model Layer
- **55 Eloquent models**: Map to database tables with custom primary keys
- Define relationships, query scopes, attribute casting, and fillable/hidden attributes
- Single-record pattern for product section models (no create/delete)
- Order model includes `statusLabel()` static helper and `generateOrderCode()` method
- ThiCong and Catalog models serve dynamic customer service content (installation guides, catalog PDFs)

### 6. Database Layer
- **MariaDB** via `DB_CONNECTION=mariadb` in `.env`
- **44 tables** from 7 migration files
- All session/cache/queue storage uses `database` driver (queue actively used for email dispatch)
- Soft delete via boolean `is_delete` column

## Request Flow Example (Admin Update)

```
1. Browser POST /admin/ngoi-am-duong
2. web.php: Route matches -> NgoiAmDuongController@update
3. Middleware: auth checked, session started
4. Controller::update():
   a. Validate request data
   b. Call NgoiAmDuongService::update($data)
5. Service::update():
   a. Fetch single record via getFirstRecord()
   b. DB::transaction():
      - Check if thumbnail files uploaded
      - FileUploadHelper::replace() for new files
      - $model->fill($fillable)->save()
   c. Return fresh model
6. Controller: back()->with('success', '...')
7. Browser redirected back with flash message
```

## Request Flow Example (Client Product Page)

```
1. Browser GET /san-pham/ngoi-am-duong
2. client.php: Route matches -> NgoiAmDuongController@index
3. Controller::index():
   a. Call NgoiAmDuongService::getFirstRecord()
   b. Call NgoiAmDuongCtService::getAllActive()
   c. Return view('clients.products.ngoi-am-duong.index', compact(...))
4. Blade template renders:
   - Layout: components/layouts/client.blade.php
   - Product components: product-card, breadcrumb, etc.
   - Swiper.js for image galleries
   - AOS for scroll animations
5. HTML sent to browser
```

## Database ER Overview

```
Product Categories (10 single-row "section" tables)
│
├── NgoiAmDuong ──────────────┬── NgoiAmDuongCt (children)
│                             ├── MauSacNgoiAmDuongCt (child colors)
│                             └── DinhMucNgoiAmDuong (ratings)
│
├── NgoiHaiVanMieu ───────────┬── NgoiHaiCoCt
│                             ├── NgoiHaiVanMieuCt
│                             ├── MauSacNgoiHaiCoCt
│                             ├── MauSacNgoiHaiVanMieuCt
│                             ├── DinhMucNgoiHaiCo
│                             └── DinhMucNgoiHaiVanMieu
│
├── GachHoaThongGio ──────────┬── GachHoaThongGioCt
│                             ├── GachHoaThongGioAnh (gallery)
│                             ├── GiaTriGachHoaThongGio (values)
│                             └── DinhMucGachHoaThongGio
│
├── GachTrangTri ─────────────┬── GachTrangTriCt
│                             ├── DauAnGachTrangTri (hallmarks)
│                             └── DinhMucGachTrangTri
│
├── GachCoBatTrang ───────────┬── GachCoBatTrangCt
│                             ├── GachCoBatTrangAnh (gallery)
│                             └── DinhMucGachCoBatTrang
│
├── LinhVatPhongThuy ─────────┬── LinhVatPhongThuyCt
│                             ├── LinhVat (items)
│                             ├── LinhVatPhongThuyAnh (gallery)
│
├── DenGomSu ─────────────────┬── DenGomSuAnh (gallery)
│
├── PhuKienNgoi
│
├── LanCanGomXu
│
└── GiaTriVuotTroi (values, shared section)

Sub-items with hierarchy:
   NgoiBoNocCt ─── PhanLoaiNgoiBoNocCt (classifications)
   BoNocChuVanCt ─ PhanLoaiBoNocChuVanCt (classifications)
```

Page Configuration Tables (static pages, single-record sections)
│
├── PageFactory (factory tour page, 14+ fields)
├── PageContact (contact page, 5 fields)
└── PageFaq -- Faqs (FAQ items, 5 fields)

Users table (separate, for admin auth)

Customer Service Tables
│
├── ThiCong (installation guide: tieu_de, anh, link_youtube)
└── Catalog (product catalogs: tieu_de, anh_dai_dien, file PDF)

Commerce Tables
│
├── Orders (order_code THLC-YYYYMMDD-XXXX, status, payment_method: cod)
├── OrderItems (polymorphic: product_type, product_id, variant_id)
└── Coupons (discount codes with percent/fixed types)

Mail & Queue
│
├── jobs (database queue driver, processes ShouldQueue mailables)
└── failed_jobs (failed queue job tracking)

## Routing Strategy

```
/admin                    → Dashboard (authenticated)
/admin/login              → Login page (guest)
/admin/users              → User management (superadmin only)
/admin/{category}         → Section CRUD (e.g., ngoi-am-duong)
/admin/{category}-ct      → Detail items CRUD (e.g., ngoi-am-duong-ct)
/admin/mau-sac-{ct}       → Colors CRUD (e.g., mau-sac-ngoi-am-duong-ct)
/admin/dinh-muc-{ct}      → Ratings CRUD (e.g., dinh-muc-ngoi-am-duong)
/admin/pages/factory       → Factory page config (single-record edit)
/admin/pages/contact       → Contact page config (single-record edit)
/admin/pages/faq           → FAQ page config + FAQ items CRUD

/                          → Home page
/xuong-san-xuat             → Factory tour (dynamic from DB)
/lien-he                    → Contact page (dynamic from DB)
/cau-hoi-thuong-gap          → FAQ page (dynamic from DB)
/ve-chung-toi              → About
/san-pham/{category}       → Product listing
/san-pham/{category}/{id}  → Product detail
/tin-tuc                   → News listing
/du-an                     → Projects listing
/gio-hang                  → Cart
/thanh-toan                → Checkout
/thanh-toan/ap-dung-ma     → Apply coupon (AJAX POST)
/thanh-toan/go-ma          → Remove coupon (AJAX POST)
/admin/orders              → Order list (paginated, latest first)
/admin/orders/{order}      → Order detail with items + status update form
/admin/coupons             → Coupon management (CRUD + restore)

/dich-vu/huong-dan-thi-cong   → Installation guide (dynamic from ThiCong model)
/dich-vu/tai-catalog          → Catalog list (dynamic from Catalog model, featured + grid)
/dich-vu/tai-catalog/doc/{id} → PDF flipbook reader (standalone, PDF.js + StPageFlip)
/trang-thai-don-hang       → Client order status tracking (auth required, tab filters)
```

## Email Notification Flow

```
1. Order placed → CartController::processCheckout():
   - DB::transaction() creates Order + OrderItems
   - Mail::to($order->email)->send(new OrderCreatedMail($order))
   - OrderCreatedMail implements ShouldQueue → queued to jobs table

2. Admin updates status → OrderController::update():
   - Validates new status from [pending_payment, processing, shipping, completed, canceled, returned]
   - Updates order status
   - If status changed AND order has email:
     Mail::to($order->email)->send(new OrderStatusUpdatedMail($order))
   - OrderStatusUpdatedMail implements ShouldQueue → queued to jobs table

3. Queue processing:
   - php artisan queue:work processes jobs from database queue
   - Both mailables use markdown templates in resources/views/emails/orders/
   - Status updated email shows Vietnamese label via Order::statusLabel()
```

## Coupon/Discount Flow

```
1. Admin creates coupon in /admin/coupons:
   - Sets title, code (unique), discount_type (percent/fixed), discount_value
   - Optional: max_discount_amount, min_order_value, applicable_product_types (JSON array)
   - Optional: usage_limit, start_date, end_date, banner_image, show_banner

2. Customer applies coupon on checkout page /thanh-toan:
   - JS fetch() POST to /thanh-toan/ap-dung-ma with code
   - CartController::applyCoupon():
     a. CouponService::validateAndCalculate(code, cart items)
        - Check coupon exists, is_valid() (active, not deleted, within date range, not exceeded usage)
        - Check min_order_value against CartService::getSubtotal()
        - If applicable_product_types set: filter cart, skip non-matching items
        - Calculate: percent = subtotal * discount_value / 100, capped at max_discount_amount
                   OR fixed = min(discount_value, subtotal)
     b. CartService::setCoupon(code) → stored in session th_cart_coupon
     c. Return JSON { success, discount_amount, new_total, message }

3. During checkout processCheckout():
   - CartService::getTotal() calls getSubtotal() - getDiscountAmount()
   - Coupon re-validated server-side before order creation
   - DB::transaction(): save discount + coupon_code to Order, increment coupon used_count

4. Customer removes coupon:
   - JS fetch() POST to /thanh-toan/go-ma
   - CartService::removeCoupon() → clears session
   - Return JSON { success, subtotal, total }
```

## Authentication Flow

```
Guest accesses /admin/login
  → POST login with email + password
  → AuthController validates credentials
  → Session created, redirect to /admin/dashboard

Authenticated user visits /admin/login
  → redirectUsersTo middleware → /admin/dashboard

Unauthenticated user visits /admin/*
  → redirectGuestsTo middleware → /admin/login

Role check on superadmin routes:
  → RoleMiddleware checks user->role === 'superadmin'
  → 403 if unauthorized

> **RBAC design**: The system uses a simple string `role` column on the `users` table (`superadmin`, `admin`, `customer`) rather than a database-driven permission package (e.g., Spatie). This keeps the auth layer lightweight and performant. RoleMiddleware accepts variadic role names (e.g., `role:superadmin,admin`) for flexible route protection.
```

## Cache / Session / Queue Architecture

All three use the `database` driver:
- **Session**: Stored in `sessions` table (120-minute lifetime)
- **Cache**: Stored in `cache` table
- **Queue**: Stored in `jobs` table, processed via `php artisan queue:work`. Used for email dispatch (OrderCreatedMail and OrderStatusUpdatedMail implement `ShouldQueue`), ensuring non-blocking checkout and status update operations.

This means no Redis or Memcached dependency, but performance may be limited under high load.
