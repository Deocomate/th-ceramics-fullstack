# Code Standards

## 1. Naming Conventions

| Element | Convention | Example |
|---------|-----------|---------|
| Classes (PHP) | English PascalCase for new classes | `YinYangRoofTileController`, `FileUploadHelper` |
| Models | PascalCase, singular | `Product`, `YinYangRoofTile` |
| Controllers | PascalCase + descriptive suffix | `YinYangRoofTileController`, `AuthController` |
| Services | PascalCase + `Service` suffix | `CartService`, `AuthService` |
| Methods/Functions | camelCase | `getFirstRecord()`, `isUnique()` |
| Variables | camelCase | `$ngoiAmDuong`, `$fillable` |
| DB Tables | snake_case, plural | `ngoi_am_duong`, `gach_hoa_thong_gio_ct` |
| DB Columns | snake_case | `ngoi_am_duong_id`, `thumbnail_main` |
| Primary Keys | `{table_name}_id` | `ngoi_am_duong_id` (not `id`) |
| File names | kebab-case | `ngoi-am-duong-ct`, `gia-tri-vuot-troi` |
| View directories | Domain-first, kebab-case | `admin/catalog/gach-hoa-thong-gio-ct/`, `clients/catalog/products/` |
| Route names | dot-notation, kebab-case segments | `admin.ngoi-am-duong.index` |
| Route prefixes | kebab-case | `/admin/gach-hoa-thong-gio` |
| Environment vars | UPPER_SNAKE_CASE | `DB_CONNECTION`, `APP_ENV` |
| Enums (if any) | TitleCase keys | Use descriptive PascalCase names |

## 2. PHP Coding Standards

### 2.1 Constructor Property Promotion

Use PHP 8 promoted properties in constructors:

```php
public function __construct(
    private readonly CoreValueService $service
) {}
```

Do not allow empty constructors with zero parameters (unless private).

### 2.2 Type Declarations

Always use explicit return type declarations:

```php
public function findById(int $id): Product
{
    return Product::query()->findOrFail($id);
}

public function update(int $id, array $data): Product
{
    // ...
}
```

### 2.3 PHPDoc Blocks

Use PHPDoc for methods with complex parameters or array shapes:

```php
/**
 * Upload a file to the specified directory under storage/app/public.
 *
 * @param UploadedFile $file       The uploaded file.
 * @param string       $directory  Sub-directory inside public disk.
 * @param string|null  $slug       Optional slug for naming the file.
 * @return string                  The stored path relative to public disk.
 */
public static function upload(UploadedFile $file, string $directory, ?string $slug = null): string
```

Do not use inline comments for simple logic. Reserve PHPDoc for complex or non-obvious code.

### 2.4 Control Structures

Always use curly braces, even for single-line bodies:

```php
if ($condition) {
    doSomething();
}
```

### 2.5 Laravel Pint

Run Pint before finalizing changes:

```bash
vendor/bin/pint --dirty
```

The project follows Laravel Pint defaults with no custom configuration file.

## 3. Database Conventions

### 3.1 Primary Keys

All tables use custom primary key names following the pattern `{table_name}_id`:

```php
// Migration
$table->id('ngoi_am_duong_id');

// Model
protected $primaryKey = 'ngoi_am_duong_id';
```

### 3.2 Soft Delete

Soft delete is implemented via a boolean `is_delete` column (not Laravel's built-in soft delete trait):

```php
$table->boolean('is_delete')->default(0)->comment('0: Active, 1: Deleted');
```

Queries must manually filter `WHERE is_delete = 0`. Controllers provide explicit `restore` routes/actions.

### 3.3 JSON Columns

Arrays stored as JSON columns:

```php
$table->json('images')->nullable();
$table->json('des')->nullable();
$table->json('size_des')->nullable();
```

### 3.4 Foreign Keys

Foreign keys use the custom primary key name explicitly:

```php
$table->foreignId('gach_hoa_thong_gio_id')
      ->constrained('gach_hoa_thong_gio', 'gach_hoa_thong_gio_id')
      ->cascadeOnDelete();
```

### 3.5 Eloquent Relationships

Always use proper relationship methods with return type hints:

```php
public function displayOptions(): HasMany
{
    return $this->hasMany(ProductDisplayOption::class)->orderBy('sort_order');
}
```

Use eager loading to prevent N+1 queries:

```php
Product::query()->with('displayOptions')->where('is_delete', false)->get();
```

### 3.7 Migrations

The initial schema was batched into grouped migration files for rapid setup (38 tables across 5 files). For any future changes:

- Use **one migration file per table** (or per feature modification).
- Do **not** modify the initial batched migration files if the app is already in production — create new migrations instead.

### 3.8 Global Product Code Uniqueness

Product codes are unique across 9 detail tables. Use `GlobalProductCodeService`:

```php
$service = app(GlobalProductCodeService::class);
if (!$service->isUnique($code, $table, $id)) {
    // Code already exists in another table
}
```

### 3.9 is_delete Filtering (Soft Delete)

All queries against soft-deletable tables must filter `WHERE is_delete = 0`:

```php
public function getAllActive(): Collection
{
    return $this->model::query()->where('is_delete', 0)->get();
}
```

For models where the filter is universal, consider a global scope in `booted()`:

```php
protected static function booted(): void
{
    static::addGlobalScope('active', fn (Builder $builder) => $builder->where('is_delete', 0));
}
```

Restore routes/actions bypass active filters to perform restoration.

## 4. Architecture Conventions

### 4.1 Context layers

```
Route -> Context Http -> Application use case / port -> Domain
                                      Infrastructure implements ports
```

- **Domain** contains framework-free business rules and cannot import another context.
- **Application** owns use cases and ports; it does not import HTTP or Infrastructure.
- **Infrastructure** contains Eloquent models and adapters for storage, mail, and cross-context access.
- **HTTP** handles requests and responses. Add a port when it represents a real boundary, not for every model.

### 4.2 Dependency Injection

Services are injected via constructor promoted properties:

```php
class CoreValueController extends Controller
{
    public function __construct(
        private readonly CoreValueService $service
    ) {}
}
```

### 4.3 Transaction Usage

Database writes use `DB::transaction()` in service methods:

```php
return DB::transaction(function () use ($model, $data) {
    // multiple writes
});
```

### 4.4 File Uploads

Use `FileUploadHelper` for all image handling:

```php
use App\Helpers\FileUploadHelper;

// Upload new file
$path = FileUploadHelper::upload($file, 'directory/path', $slug);

// Replace existing file
$path = FileUploadHelper::replace($file, $oldPath, 'directory/path', $slug);

// Delete file
FileUploadHelper::delete($path);
```

## 5. Routing Conventions

### 5.1 Admin Routes (`routes/web.php`)

- Prefix: `/admin`
- Named: `admin.{resource}.{action}`
- Guest routes: login, forgot/reset password
- Auth routes: dashboard, CRUD for all resources
- Superadmin-only routes: user management (wrapped in `role:superadmin` middleware)

### 5.2 Client Routes (`routes/client.php`)

- Named: `client.{page}`
- Product URLs: `/san-pham/{category-slug}/{id}`
- Static pages: `/ve-chung-toi`, `/lien-he`, `/xuong-san-xuat`
- 301 redirects from old English URLs to new Vietnamese URLs

### 5.3 Middleware

Registered in `bootstrap/app.php`:

```php
$middleware->alias([
    'role' => \App\Domains\Identity\Http\Middleware\RoleMiddleware::class,
]);
// Role-aware guest & auth redirects (admin.* -> admin.auth.login / admin.dashboard; client -> client.auth.login / client.home)
```

## 6. Authentication & RBAC

### 6.1 User Model

Roles: `superadmin`, `admin`, `customer`

Helper methods on User model:
- `isSuperAdmin()`: role === 'superadmin'
- `isAdmin()`: role in ['superadmin', 'admin']
- `isRegularAdmin()`: role === 'admin'

> **Design decision**: RBAC is implemented strictly via an `enum`-style string `role` column in the `users` table. We do not use database-driven permissions (e.g., Spatie Permission) to keep the system simple and performant. Role checks are enforced through `RoleMiddleware` at the route level.

### 6.2 RoleMiddleware

```php
Route::middleware('role:superadmin')->group(function () {
    // Superadmin-only routes
});
```

Multiple roles: `->middleware('role:superadmin,admin')`

Unauthorized access returns 403 with Vietnamese message.

## 7. Frontend Conventions

### 7.1 Blade Templates & View Architecture

Views are organized by domain context under `resources/views/`:
- `admin/{catalog,content,commerce,identity,archive}/`: Back-office pages by domain.
- `clients/{catalog/products,content,commerce,identity}/`: Public-facing storefront pages.
- `components/`: Reusable Blade components:
  - `admin/{layouts,shared,catalog}/`: Admin layout shell and shared inputs/modals.
  - `client/{layouts,shared,catalog,content,commerce,identity}/`: Client layouts, section partials, and domain components.
  - `emails/{identity,commerce,content}/`: Markdown email templates organized by domain.
  - `vendor/mail/`: Vendor overrides and CSS mail theme.

#### View Contracts & Props Guidelines
- **Granular Props**: Components declare required parameters explicitly using `@props` with sensible defaults:
  ```blade
  @props([
      'products' => collect(),
      'routeName' => 'client.products.ngoi-am-duong.detail',
      'productType' => 'ngoi_am_duong_ct',
  ])
  ```
- **No Queries or Business Calculations in Blade**: Blade files must not execute Eloquent queries (`Model::query()`, `::where()`, `::all()`), database facades (`DB::`), or service container resolutions (`@inject`).
- **Data Preparation Boundary**: All required view data is prepared upstream by Controllers, Presenters, ViewComposers (`ContentViewComposer`, header composer), or class-backed ViewComponents (`ProductCard`, `ProductDetailContainer`, `Recommendations`, `Works`, `WorksSimple`, `OutstandingValue`).
- **PHP Fallbacks**: When ViewComponents or Composers support fallbacks (e.g. awards or related products), query execution is request-scoped and guarded against re-executing when an empty collection is explicitly provided.
- **Component Tags & Backward-Compatibility**: New components use domain tags (e.g. `<x-client.catalog.shared.product-card />`), while `AppServiceProvider` maintains component aliases and anonymous component paths so legacy references remain fully functional.

### 7.2 JavaScript & Assets

- **Asset Path Resolution**: All asset URLs must be resolved through `\App\Support\AssetPath::url($path, $fallback)` to ensure consistent fallback handling.
- **Modular JavaScript**: UI behaviors are separated into dedicated scripts under `public/assets/js/`:
  - `admin-form-ui.js`: Admin image optimization, gallery uploads, auto-resize textareas, dynamic repeaters.
  - `cart-ui.js`, `consultation-modal.js`: Commerce modals and mini-cart handlers.
  - `product-calculators.js`, `product-detail.js`, `weight-calculator-sticky-bar.js`: Product estimation tools and tab controls.
- **DOM Data Attributes**: JS hooks communicate via standard data attributes (`data-product-id`, `data-quantity-calculator`, `data-product-detail-container`), without inline `<script>` tags in leaf components.
- **Third-Party Libraries**: Alpine.js (admin), Swiper.js, AOS, PDF.js, StPageFlip, and GLightbox loaded via CDN or local assets.

#### 7.2.1 Alpine.js Auto-Resize Textarea Pattern

All admin HTML-capable textareas use Alpine.js for automatic height adjustment:

```html
<textarea name="description"
    x-data
    x-init="$el.style.height = $el.scrollHeight + 'px'"
    @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
    class="... resize-none overflow-hidden min-h-[120px] leading-relaxed">{{ old('description', $model->description) }}</textarea>
```

Key classes: `resize-none overflow-hidden min-h-[120px]` — prevents manual resize handles, hides scrollbar overflow, and sets a minimum height so the field never collapses below a usable size.

### 7.3 CSS & Build Architecture

- Tailwind CSS via CDN (`cdn.tailwindcss.com`) with custom layout configuration scripts.
- Additional styling in `public/assets/css/main.css`.
- **No NPM Build Step**: The repository does not include `package.json` or run `npm run build`. All frontend assets are served statically from `public/assets/` and CDN endpoints.

## 8. Testing Standards

- Use Pest 3 for testing (25 test files covering admin pages, auth flows, client product pages)
- Feature tests preferred over unit tests
- Use model factories for test data where available
- Test coverage areas:
  - Admin: page configuration CRUD (Factory, Contact, FAQ), coupon management, order preview, product color fields, customer management
  - Auth: admin login/reset, client authentication/registration, password reset (role-based routing), Google OAuth
  - Client: product detail views (Ngoi Am Duong, Ngoi Hai, Gach Trang Tri, Gach Co Bat Trang, Den Gom Su, Linh Vat Phong Thuy, Phu Kien Ngoi), project pages, news pages, contact form submission, global search
- Run with: `php artisan test --compact`
- Filter specific test: `php artisan test --filter=test_name`

## 9. Single-Record Pattern

Product section configuration tables use a single record. `HomePageConfigService::getFirstRecord()` creates the initial record when absent; the admin controller edits and updates that record.

These configuration records have no create/destroy routes; the admin form reads the record and submits an update.
