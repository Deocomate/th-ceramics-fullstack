---
phase: 3
title: "Contact & FAQ Admin Panels"
status: completed
priority: P2
effort: "4h"
dependencies: ["phase-01-database-setup"]
---

# Phase 3: Contact & FAQ Admin Panels

## Overview

Build admin panels for Contact page (simple 5-field form) and FAQ page (banner + CRUD for FAQ items with category grouping). Both are simpler than Factory but require full service+controller+blade stacks.

## Requirements

- Functional:
  - Contact: Edit 5 fields (map_image, hotline, zalo_link, zalo_image, form_title)
  - FAQ: Edit banner_image on page_faq table, full CRUD for faqs table (create, read, update, delete, toggle active, reorder)
- Non-functional: Same patterns as existing admin pages, Vietnamese labels

## Architecture

### Routes
```php
// Contact - single record edit
Route::get('contact', [ContactPageController::class, 'edit'])->name('contact.edit');
Route::put('contact', [ContactPageController::class, 'update'])->name('contact.update');

// FAQ page - banner edit
Route::get('faq', [FaqPageController::class, 'edit'])->name('faq.edit');
Route::put('faq', [FaqPageController::class, 'update'])->name('faq.update');

// FAQ items - full CRUD (exclude show)
Route::resource('faqs', FaqController::class)->except(['show']);
```

### Contact Page
Simple single-record edit — same pattern as `DenGomSuController` but without galleries:
- `Service::update()` handles single image uploads via `FileUploadHelper::replace()`
- Text fields updated directly
- Blade: single form with image preview fields + text inputs

### FAQ Page + FAQ Items
Two parts in one admin page:
1. **Banner section** (top): Single image upload form for `page_faq.banner_image` — simple PUT
2. **FAQ Items CRUD** (bottom): Table with modal-based add/edit, inline toggle active, sort order drag or manual input

## Related Code Files

- Create: `app/Services/ContactPageService.php`
- Create: `app/Services/FaqPageService.php`
- Create: `app/Services/FaqService.php`
- Create: `app/Http/Controllers/Admin/ContactPageController.php`
- Create: `app/Http/Controllers/Admin/FaqPageController.php`
- Create: `app/Http/Controllers/Admin/FaqController.php`
- Create: `app/Http/Requests/ContactPageRequest.php`
- Create: `app/Http/Requests/FaqPageRequest.php`
- Create: `app/Http/Requests/FaqRequest.php`
- Create: `resources/views/admin/pages/contact/edit.blade.php`
- Create: `resources/views/admin/pages/faq/edit.blade.php`
- Create: `resources/views/admin/pages/faq/partials/faq-table.blade.php` (optional, if > 200 lines)
- Modify: `routes/web.php`

## Implementation Steps

### Step 3.1: Register Routes

Add Contact, FAQ page, and FAQ items resource routes inside the `/admin/pages` prefix group (alongside Factory routes from Phase 2).

### Step 3.2: Contact - Service, Controller, Request, Blade

**ContactPageService**: Standard single-record service:
- `getFirstRecord()`: returns `PageContact::query()->firstOrFail()`
- `update(array $data)`: handles `map_image`, `zalo_image` as `FileUploadHelper::replace()`, text fields directly

**ContactPageRequest**: Validates image uploads + string fields

**ContactPageController**: `edit()` returns view, `update()` delegates to service

**Blade** (`admin/pages/contact/edit.blade.php`):
- Single form (no tabs needed — only 5 fields)
- Map image: image upload with preview
- Hotline: text input (placeholder: "0966 55 8808")
- Zalo link: text input (URL)
- Zalo image: image upload with preview
- Form title: text input (placeholder: "Hãy nói với chúng tôi những mong muốn của bạn")

### Step 3.3: FAQ Page Banner - Service, Controller, Request

**FaqPageService**: Minimal — only handles `banner_image`:
- `getFirstRecord()`: returns `PageFaq::query()->firstOrFail()`
- `update(array $data)`: `FileUploadHelper::replace()` for banner

**FaqPageController**: `edit()` returns combined view (banner + FAQ items list), `update()` saves banner

### Step 3.4: FAQ Items CRUD - Service, Controller, Request, Blade

**FaqService**:
- `getAll()`: `Faq::query()->where('is_delete', 0)->orderBy('category')->orderBy('sort_order')->get()`
- `getGroupedByCategory()`: `getAll()->groupBy('category')` — for client view binding
- `store(array $data)`: creates new Faq with validated data
- `update(Faq $faq, array $data)`: updates question/answer/category/sort_order/is_active
- `destroy(Faq $faq)`: sets `is_delete = 1` (soft delete pattern)
- `toggleActive(Faq $faq)`: toggles `is_active`

**FaqRequest**: Validates `category` (required|in:sản-phẩm,báo-giá,vận-chuyển,lắp-đặt,đổi-trả), `question` (required|max:1000), `answer` (required), `sort_order` (integer), `is_active` (boolean)

**FaqController**:
- `index()` → redirect to `pages.faq.edit` (FAQ items managed inline on FAQ page)
- `store(Request)`: creates item, returns `back()->with('success')`
- `update(Faq, Request)`: updates item, returns `back()->with('success')`
- `destroy(Faq)`: soft deletes, returns `back()->with('success')`

**Blade** (`admin/pages/faq/edit.blade.php`):
- Top: Banner image form (simple, uses `FaqPageController@update`)
- Bottom: FAQ Items management table:
  - "Thêm câu hỏi mới" button → opens modal with form
  - Table columns: Category | Question (truncated) | Sort Order | Active | Actions (Edit, Delete)
  - Edit button → opens same modal pre-filled with data
  - Delete button → confirmation dialog → soft delete
  - Active toggle: checkbox or toggle switch
  - Category filter dropdown at top

**Modal form fields**: Category dropdown, Question input, Answer textarea, Sort order number, Active checkbox

## Success Criteria

- [ ] Contact page: all 5 fields editable, images upload with preview, save persists
- [ ] FAQ banner: image upload/replace works
- [ ] FAQ items: create new FAQ via modal, appears in table
- [ ] FAQ items: edit existing FAQ via modal, changes persist
- [ ] FAQ items: delete FAQ (soft delete), disappears from table
- [ ] FAQ items: toggle active/inactive
- [ ] FAQ items: filtered/sorted by category

## Risk Assessment

- **Risk**: Modal form validation errors losing state → mitigation: use `old()` helper in modal, or validate via AJAX before close
- **Risk**: FAQ category values mismatch between DB, admin, and client view → mitigation: define categories with labels as a constant in Faq model (see below), reference from admin dropdown, validation, and client Blade

### Faq Model — CATEGORIES Constant (Single Source of Truth)

Define this in the `Faq` model so admin dropdown, validation rules, and client view all reference the same data:

```php
// app/Models/Faq.php
class Faq extends Model
{
    protected $primaryKey = 'faq_id';

    public const CATEGORIES = [
        'sản-phẩm'   => 'Sản phẩm',
        'báo-giá'    => 'Giá cả & Đặt hàng',
        'vận-chuyển' => 'Vận chuyển & Lắp đặt',
        'lắp-đặt'    => 'Lắp đặt & Bảo trì',
        'đổi-trả'    => 'Đổi trả',
    ];

    // ...
}
```

**Usage in FaqRequest validation:**
```php
'category' => ['required', 'string', Rule::in(array_keys(Faq::CATEGORIES))],
```

**Usage in Admin Blade (category dropdown):**
```blade
<select name="category">
    @foreach(\App\Models\Faq::CATEGORIES as $value => $label)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>
```

**Usage in Client Blade (Phase 4):** Instead of hardcoding `$categoryLabels` array in the view, reference `Faq::CATEGORIES[$category]`.
