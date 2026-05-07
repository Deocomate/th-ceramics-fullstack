---
phase: 1
title: "Database & Setup"
status: pending
priority: P1
effort: "2h"
dependencies: []
---

# Phase 1: Database & Setup

## Overview

Create 4 migration files, 4 Eloquent models, a seeder for default data, and update the admin sidebar with a new "Cấu Hình Trang Đơn" section.

## Requirements

- Functional: 4 tables (`page_factory`, `page_contact`, `page_faq`, `faqs`) with correct columns
- Non-functional: Follow existing `{table}_id` PK convention, `is_delete` soft delete, JSON casting

## Architecture

All 4 tables are independent (no FK relationships). `page_factory`, `page_contact`, `page_faq` follow single-record pattern (1 row only). `faqs` is multi-row for CRUD FAQ items.

### Table Schemas

**`page_factory`** — Factory page (Xưởng Sản Xuất)
| Column | Type | Notes |
|--------|------|-------|
| `page_factory_id` | bigint PK | Auto-increment |
| `hero_banner_desktop` | string(500) | Desktop banner |
| `hero_banner_mobile` | string(500) | Mobile banner |
| `intro_title` | string(500) | Intro section title |
| `intro_subtitle` | string(500) | Intro section subtitle |
| `intro_description` | text | Intro description |
| `gallery_1` | json | Array of image paths |
| `process_title` | string(500) | Process section title |
| `process_description` | text | Process description (HTML) |
| `process_slider` | json | Array of image paths |
| `process_bottom_title` | string(500) | Combined power title |
| `process_bottom_desc` | text | Combined power description (HTML) |
| `process_bottom_image` | string(500) | Bottom static image |
| `material_slider` | json | Array of image paths |
| `material_steps` | json | `[{title, description}]` |
| `is_delete` | boolean | Default 0 |
| timestamps | | created_at, updated_at |

**`page_contact`** — Contact page (Liên Hệ)
| Column | Type | Notes |
|--------|------|-------|
| `page_contact_id` | bigint PK | Auto-increment |
| `map_image` | string(500) | Map/background image |
| `hotline` | string(50) | Hotline number |
| `zalo_link` | string(500) | Zalo link URL |
| `zalo_image` | string(500) | Zalo icon image |
| `form_title` | string(500) | "Hãy nói với chúng tôi..." |
| `is_delete` | boolean | Default 0 |
| timestamps | | |

**`page_faq`** — FAQ page banner
| Column | Type | Notes |
|--------|------|-------|
| `page_faq_id` | bigint PK | Auto-increment |
| `banner_image` | string(500) | FAQ banner |
| `is_delete` | boolean | Default 0 |
| timestamps | | |

**`faqs`** — FAQ items (multi-row CRUD)
| Column | Type | Notes |
|--------|------|-------|
| `faq_id` | bigint PK | Auto-increment |
| `category` | string(50) | sản-phẩm, báo-giá, vận-chuyển, lắp-đặt, đổi-trả |
| `question` | string(1000) | FAQ question |
| `answer` | text | FAQ answer |
| `sort_order` | int | Default 0 |
| `is_active` | boolean | Default 1 |
| `is_delete` | boolean | Default 0 |
| timestamps | | |

## Related Code Files

- Create: `database/migrations/xxxx_xx_xx_create_page_factory_table.php`
- Create: `database/migrations/xxxx_xx_xx_create_page_contact_table.php`
- Create: `database/migrations/xxxx_xx_xx_create_page_faq_table.php`
- Create: `database/migrations/xxxx_xx_xx_create_faqs_table.php`
- Create: `app/Models/PageFactory.php`
- Create: `app/Models/PageContact.php`
- Create: `app/Models/PageFaq.php`
- Create: `app/Models/Faq.php`
- Create: `database/seeders/PageConfigSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`
- Modify: `resources/views/components/admin/layout/sidebar.blade.php`

## Implementation Steps

### Step 1.1: Create Migrations

Create 4 migration files using `php artisan make:migration` with `--no-interaction`. Use custom PK naming (`page_factory_id`, etc.), JSON columns for arrays, boolean `is_delete` for soft delete.

### Step 1.2: Create Models

```php
// PageFactory.php — key parts
class PageFactory extends Model
{
    protected $primaryKey = 'page_factory_id';
    protected $fillable = [
        'hero_banner_desktop', 'hero_banner_mobile',
        'intro_title', 'intro_subtitle', 'intro_description',
        'gallery_1', 'process_title', 'process_description',
        'process_slider', 'process_bottom_title', 'process_bottom_desc',
        'process_bottom_image', 'material_slider', 'material_steps',
    ];

    protected function casts(): array
    {
        return [
            'gallery_1' => 'array',
            'process_slider' => 'array',
            'material_slider' => 'array',
            'material_steps' => 'array',
        ];
    }
}
```

Similar pattern for `PageContact`, `PageFaq`, `Faq` with appropriate `$fillable`, `$casts`, and `$primaryKey`.

### Step 1.3: Create Seeder (CRITICAL — Zero Breaking Change Guarantee)

**`PageConfigSeeder` MUST copy 100% exact content from current static Blade files — NOT placeholder/dummy data.** This ensures UI matches pixel-for-pixel after switching to dynamic data.

**Content mapping — copy from these source files into DB:**

| DB Column | Copy exact text from |
|-----------|---------------------|
| `hero_banner_desktop` | `hero.blade.php` desktop `<img src>` |
| `hero_banner_mobile` | `hero.blade.php` mobile `<img src>` |
| `intro_title` | `intro.blade.php` `<h2>` text |
| `intro_subtitle` | `intro.blade.php` `<h3>` text |
| `intro_description` | `intro.blade.php` content (2 `<p>` + 1 quote `<p>`) |
| `gallery_1` | `gallery-1.blade.php` all slide images (2 items) |
| `process_title` | `process.blade.php` `<h3>` text |
| `process_description` | `process.blade.php` description text + numbered list `<ul>` |
| `process_slider` | `process.blade.php` swiper slides (2 items) |
| `process_bottom_title` | `process.blade.php` bottom `<h3>` text |
| `process_bottom_desc` | `process.blade.php` bottom `<p>` content |
| `process_bottom_image` | `process.blade.php` bottom `<img>` |
| `material_slider` | `material.blade.php` swiper slides (3 items) |
| `material_steps` | `material.blade.php` JS `section4Steps` array (3 objects) |

**FAQ items**: Copy exact 20 hardcoded FAQ accordion items from `faq/index.blade.php` into `faqs` table, with:
- `category` matching the section they appear in
- `question` and `answer` copied verbatim
- `sort_order` preserving the order they appear in the Blade

**Image paths in seeder**: Use the same paths as current hardcoded Blade (e.g. `assets/images/factory-banner.png`) — NOT `storage/` paths. The client Blade will handle `asset()` prefixing.

Register `PageConfigSeeder::class` in `DatabaseSeeder::run()`.

### Step 1.4: Update Sidebar

Add a new collapsible section "CẤU HÌNH TRANG ĐƠN" (new section header between "CẤU HÌNH SECTION CHUNG" and "Superadmin section") with 3 links:
- Trang Xưởng Sản Xuất → `admin.pages.factory.edit`
- Trang Liên Hệ → `admin.pages.contact.edit`
- Trang FAQ → `admin.pages.faq.edit`

Follow existing sidebar pattern: collapsible button with chevron icon, nested link list with active state highlighting using `request()->routeIs()`.

## Success Criteria

- [ ] 4 migrations run successfully (`php artisan migrate` no errors)
- [ ] 4 models exist with correct `$primaryKey`, `$fillable`, `$casts`
- [ ] `php artisan db:seed --class=PageConfigSeeder` inserts correct rows
- [ ] Sidebar shows new "CẤU HÌNH TRANG ĐƠN" section with 3 links
- [ ] `php artisan test` passes (no regression)

## Risk Assessment

- **Risk**: JSON column compatibility — MariaDB supports JSON natively (10.2+), no issue expected
- **Risk**: Sidebar JS — existing `toggleSubmenu()` function is reusable, just add new button + wrapper div
