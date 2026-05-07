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
│  │ Eloquent Models (38): relationships, scopes, │ │
│  │ custom PKs, casts, fillable/hidden attrs     │ │
│  └─────────────────────────────────────────────┘ │
└────────────────────┬─────────────────────────────┘
                     │
┌────────────────────▼─────────────────────────────┐
│              Database Layer                       │
│  ┌─────────────────────────────────────────────┐ │
│  │ MariaDB (th_ceramics_fullstack)              │ │
│  │ 38 tables across 5 migrations               │ │
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
- **Admin controllers** (32 files): Thin controllers that validate requests, delegate to services, and return redirect responses with flash messages
- **Client controllers** (10 + 9 product pages): Query services, return view responses with data
- All use constructor DI with `private readonly` service properties

### 4. Service Layer
- **33 service classes**: One per major model
- Contains business logic including file upload handling, transaction management, and code uniqueness checks
- No repository pattern — services interact directly with Eloquent models
- `GlobalProductCodeService`: Cross-table uniqueness validation for product codes

### 5. Model Layer
- **38 Eloquent models**: Map to database tables with custom primary keys
- Define relationships, query scopes, attribute casting, and fillable/hidden attributes
- Single-record pattern for product section models (no create/delete)

### 6. Database Layer
- **MariaDB** via `DB_CONNECTION=mariadb` in `.env`
- **38 tables** from 5 migration files
- All session/cache/queue storage uses `database` driver
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

Users table (separate, for admin auth)

## Routing Strategy

```
/admin                    → Dashboard (authenticated)
/admin/login              → Login page (guest)
/admin/users              → User management (superadmin only)
/admin/{category}         → Section CRUD (e.g., ngoi-am-duong)
/admin/{category}-ct      → Detail items CRUD (e.g., ngoi-am-duong-ct)
/admin/mau-sac-{ct}       → Colors CRUD (e.g., mau-sac-ngoi-am-duong-ct)
/admin/dinh-muc-{ct}      → Ratings CRUD (e.g., dinh-muc-ngoi-am-duong)

/                          → Home page
/ve-chung-toi              → About
/san-pham/{category}       → Product listing
/san-pham/{category}/{id}  → Product detail
/tin-tuc                   → News listing
/du-an                     → Projects listing
/gio-hang                  → Cart
/thanh-toan                → Checkout
/dich-vu/{page}            → Customer service pages
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
- **Queue**: Stored in `jobs` table, processed via `php artisan queue:listen`

This means no Redis or Memcached dependency, but performance may be limited under high load.
