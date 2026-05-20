# TH Ceramics Fullstack

A Laravel 12 e-commerce website for Thanh Hai Ceramics, a traditional Vietnamese roof tile and decorative brick manufacturer. Features a full admin panel for product management and a public-facing site with SEO-optimized Vietnamese URLs.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, PHP 8.2+ |
| Database | MariaDB (`th_ceramics_fullstack`) |
| Frontend | Blade templates, Tailwind CSS (CDN), Alpine.js, vanilla JS |
| Libraries | Swiper.js, AOS (Animate on Scroll), PDF.js, StPageFlip |
| Testing | Pest 3 |
| Cache/Session/Queue | Database driver |
| Code Style | Laravel Pint |

## Requirements

- PHP 8.2+
- Composer
- MariaDB / MySQL
- Node.js (for Vite asset builds, required for production deployment)

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

# 5. Run migrations (creates 44 tables)
php artisan migrate

# 6. Seed initial data (admin user + product data)
php artisan db:seed

# 7. Build frontend assets (required for production)
npm install && npm run build

# 8. (Optional) Create storage symlink for file uploads
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
├── Http/Controllers/Admin/   # 51 admin CRUD controllers
├── Http/Controllers/Client/  # 29 public page controllers
├── Http/Middleware/           # RoleMiddleware (RBAC)
├── Http/Requests/             # 31 form request classes
├── Models/                    # 55 Eloquent models
├── Services/                  # 53 service classes (business logic)
├── Helpers/                   # FileUploadHelper
├── Mail/                      # 2 ShouldQueue mailables (order + status)
├── Notifications/             # 1 ResetPasswordNotification (ShouldQueue)
├── Providers/                 # AppServiceProvider
database/
├── migrations/                # 15 migration files
├── seeders/                   # 26 seeders
resources/views/
├── admin/                     # 86 admin view files
├── clients/                   # 142 client view files
├── components/                # 32 shared Blade components
├── emails/                    # 4 email templates
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
- **Single-record sections**: Product section tables hold exactly one row per category
- **Global product code uniqueness**: Enforced across 9 detail tables via GlobalProductCodeService
- **JSON-LD structured data**: Product schema markup on detail pages for search engines
- **Dynamic pages**: Home, About, Factory, Contact, FAQ, Projects, Customer Service all pull from DB
- **Session-based cart**: AJAX controls, COD checkout, coupon/discount system
- **Order management**: Admin order lifecycle + client order tracking with email notifications
- **Client authentication**: Login, register, forgot/reset password, Google OAuth (Socialite)
- **Database queue**: Email dispatch (ShouldQueue mailables) via `jobs` table, no Redis needed

## Testing

```bash
# Run all tests
php artisan test

# Run with filter
php artisan test --filter=test_name
```
