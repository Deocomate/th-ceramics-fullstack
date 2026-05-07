# Code Standards

## 1. Naming Conventions

| Element | Convention | Example |
|---------|-----------|---------|
| Classes (PHP) | PascalCase | `NgoiAmDuongController`, `FileUploadHelper` |
| Models | PascalCase, singular | `NgoiAmDuong`, `GachHoaThongGioCt` |
| Controllers | PascalCase + descriptive suffix | `NgoiAmDuongController`, `AuthController` |
| Services | PascalCase + `Service` suffix | `NgoiAmDuongService`, `AuthService` |
| Methods/Functions | camelCase | `getFirstRecord()`, `isUnique()` |
| Variables | camelCase | `$ngoiAmDuong`, `$fillable` |
| DB Tables | snake_case, plural | `ngoi_am_duong`, `gach_hoa_thong_gio_ct` |
| DB Columns | snake_case | `ngoi_am_duong_id`, `thumbnail_main` |
| Primary Keys | `{table_name}_id` | `ngoi_am_duong_id` (not `id`) |
| File names | kebab-case | `ngoi-am-duong-ct`, `gia-tri-vuot-troi` |
| View directories | kebab-case | `gach-hoa-thong-gio-ct/`, `mau-sac-ngoi-am-duong-ct/` |
| Route names | dot-notation, kebab-case segments | `admin.ngoi-am-duong.index` |
| Route prefixes | kebab-case | `/admin/gach-hoa-thong-gio` |
| Environment vars | UPPER_SNAKE_CASE | `DB_CONNECTION`, `APP_ENV` |
| Enums (if any) | TitleCase keys | Use descriptive PascalCase names |

## 2. PHP Coding Standards

### 2.1 Constructor Property Promotion

Use PHP 8 promoted properties in constructors:

```php
public function __construct(
    private readonly NgoiAmDuongService $service
) {}
```

Do not allow empty constructors with zero parameters (unless private).

### 2.2 Type Declarations

Always use explicit return type declarations:

```php
public function getFirstRecord(): NgoiAmDuong
{
    return NgoiAmDuong::query()->firstOrFail();
}

public function update(array $data): NgoiAmDuong
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
public function colors(): HasMany
{
    return $this->hasMany(MauSacNgoiAmDuongCt::class, 'ngoi_am_duong_ct_id');
}
```

Use eager loading to prevent N+1 queries:

```php
NgoiAmDuongCt::query()->with('colors')->where('is_delete', 0)->get();
```

### 3.7 Migrations

The initial schema was batched into grouped migration files for rapid setup (38 tables across 5 files). For any future changes:

- Use **one migration file per table** (or per feature modification).
- Do **not** modify the initial batched migration files if the app is already in production — create new migrations instead.

### 3.6 Global Product Code Uniqueness

Product codes are unique across 9 detail tables. Use `GlobalProductCodeService`:

```php
$service = app(GlobalProductCodeService::class);
if (!$service->isUnique($code, $table, $id)) {
    // Code already exists in another table
}
```

## 4. Architecture Conventions

### 4.1 Layered Architecture

```
Route -> Controller -> Service -> Model
```

- **Controllers**: Thin, handle HTTP concerns (request/response)
- **Services**: Business logic, database operations, file uploads
- **Models**: Eloquent definitions, relationships, scopes
- No repository pattern

### 4.2 Dependency Injection

Services are injected via constructor promoted properties:

```php
class NgoiAmDuongController extends Controller
{
    public function __construct(
        private readonly NgoiAmDuongService $service
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
    'role' => \App\Http\Middleware\RoleMiddleware::class,
]);
$middleware->redirectGuestsTo(fn() => route('admin.auth.login'));
$middleware->redirectUsersTo(fn() => route('admin.dashboard'));
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

### 7.1 Blade Templates

- Use Blade components for reusable UI: `<x-product-card />`
- Use `@props` directive for component parameters
- Use `@stack('scripts')` and `@stack('styles')` for page-specific assets
- Client layout in `components/layouts/client.blade.php`
- Admin layout in `components/admin/layout/app.blade.php`

### 7.2 JavaScript

- **Alpine.js** (CDN) for admin interactive UI: tab navigation, auto-resize textareas, dynamic image galleries, repeater fields
- **Swiper.js** for client image carousels
- **AOS** for client scroll animations
- Module script loaded from `public/assets/js/app.js`

#### 7.2.1 Alpine.js Auto-Resize Textarea Pattern

All admin HTML-capable textareas use Alpine.js for automatic height adjustment. This replaces the former TinyMCE rich-text editor with a lightweight, native approach.

```html
<textarea name="description"
    x-data
    x-init="$el.style.height = $el.scrollHeight + 'px'"
    @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
    class="... resize-none overflow-hidden min-h-[120px] leading-relaxed">{{ old('description', $model->description) }}</textarea>
```

Key classes: `resize-none overflow-hidden min-h-[120px]` — prevents manual resize handles, hides scrollbar overflow, and sets a minimum height so the field never collapses below a usable size.

### 7.3 CSS

- Tailwind CSS via CDN (`cdn.tailwindcss.com`)
- Custom config in layout `<script>` block (client and admin have separate configs)
- Additional styles in `public/assets/css/main.css`

> **Note on Vite & NPM**: The project currently uses Tailwind via CDN with no build step. A `vite.config.js` and `composer.json` setup script referencing `npm install && npm run build` exist only for future scalability (see Roadmap Phase 5) but are strictly ignored in the current phase. Frontend assets are served directly from `public/assets/`.

## 8. Testing Standards

- Use Pest 3 for testing
- Feature tests preferred over unit tests
- Use model factories for test data
- Run with: `php artisan test --compact`

## 9. Single-Record Pattern

Product section tables are designed for single-row storage:

```php
// Service
public function getFirstRecord(): NgoiAmDuong
{
    return NgoiAmDuong::query()->firstOrFail();
}

// Controller
public function update(Request $request): RedirectResponse
{
    $data = $request->validate([...]);
    $this->service->update($data);
    return back()->with('success', 'Updated successfully.');
}
```

These tables have no create/destroy routes — only index (show form) and update.
