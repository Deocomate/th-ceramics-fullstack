# TH Ceramics Fullstack

A Laravel 12 e-commerce website for Thanh Hai Ceramics, a traditional Vietnamese roof tile and decorative brick manufacturer. Features a full admin panel for product management and a public-facing site with SEO-optimized Vietnamese URLs.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, PHP 8.2+ |
| Database | MariaDB (`th_ceramics_fullstack`) |
| Frontend | Blade templates, Tailwind CSS (CDN), vanilla JS |
| Libraries | Swiper.js, AOS (Animate on Scroll) |
| Testing | Pest 3 |
| Cache/Session/Queue | Database driver |
| Code Style | Laravel Pint |

## Requirements

- PHP 8.2+
- Composer
- MariaDB / MySQL
- Node.js (for Vite, currently unused for asset builds)

## Setup

```bash
# 1. Install PHP dependencies
composer install

# 2. Create environment file
cp .env.example .env

# 3. Configure .env — set DB credentials, APP_URL, etc.
#    DB_CONNECTION=mariadb
#    DB_DATABASE=th_ceramics_fullstack

# 4. Generate application key
php artisan key:generate

# 5. Run migrations (creates 38 tables)
php artisan migrate

# 6. Seed initial data (admin user + product data)
php artisan db:seed

# 7. (Optional) Create storage symlink for file uploads
php artisan storage:link
```

## Development

```bash
# Start the dev server
php artisan serve

# Listen to the queue (required for database queue driver)
php artisan queue:listen --tries=1

# Run tests
php artisan test
```

## Default Admin Access

After seeding:
- URL: `http://localhost/admin`
- Email: set in `database/seeders/UserSeeder.php`
- Password: set in `database/seeders/UserSeeder.php`
- Roles: `superadmin` (full access), `admin` (content management only)

## Project Structure

```
app/
├── Http/Controllers/Admin/   # 39 admin CRUD controllers
├── Http/Controllers/Client/  # 12 public page controllers
├── Http/Middleware/           # RoleMiddleware (RBAC)
├── Http/Requests/             # 10 form request classes
├── Models/                    # 38 Eloquent models
├── Services/                  # 33 service classes (business logic)
├── Helpers/                   # FileUploadHelper
├── Providers/                 # AppServiceProvider
database/
├── migrations/                # 5 migration files → 38 tables
├── seeders/                   # 3 seeders (User, ProductType)
resources/views/
├── admin/                     # 58 admin view files
├── clients/                   # 134 client view files
├── components/                # 28 shared Blade components
routes/
├── web.php                    # Admin routes (/admin/*)
├── client.php                 # Public routes (Vietnamese URLs)
└── console.php                # Console commands
```

## Key Features

- **9 product categories**: Ngoi Am Duong, Ngoi Hai Van Mieu, Gach Hoa Thong Gio, Gach Trang Tri, Lan Can Gom Su, Gach Co Bat Trang, Linh Vat Phong Thuy, Den Gom Su, Phu Kien Ngoi
- **Admin CRUD**: Full content management per category with image upload, soft-delete, and restore
- **SEO URLs**: Vietnamese-language public URLs with 301 redirects from old English paths
- **RBAC**: `superadmin` and `admin` roles via `RoleMiddleware`
- **Single-record sections**: Several product section tables hold exactly one row per category
- **Global product code uniqueness**: Product codes are enforced unique across 9 detail tables

## Testing

```bash
# Run all tests
php artisan test

# Run with filter
php artisan test --filter=test_name
```
