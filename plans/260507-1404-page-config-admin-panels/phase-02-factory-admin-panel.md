---
phase: 2
title: "Factory Admin Panel"
status: pending
priority: P1
effort: "6h"
dependencies: ["phase-01-database-setup"]
---

# Phase 2: Factory Admin Panel

## Overview

Build the most complex admin page: Factory configuration with 6 logical sections (Hero, Intro, Gallery 1, Process, Material, Gallery 2), JSON repeater fields for multiple image sliders, dynamic material steps repeater, WYSIWYG for HTML descriptions, and image preview on all upload fields.

## Requirements

- Functional: Admin can edit all 14 fields, upload/reorder/delete images in 4 JSON galleries, CRUD material steps dynamically
- Non-functional: Image preview before upload, TinyMCE for `process_description` and `process_bottom_desc`, Alpine.js for dynamic repeaters (no page reload), Vietnamese labels and error messages

## Architecture

```
Route (GET/PUT /admin/pages/factory)
  → FactoryPageController (edit, update)
    → FactoryPageService (getFirstRecord, update)
      → PageFactory model
      → FileUploadHelper (single images)
      → Storage::disk('public') (gallery JSON management)

Admin Blade:
  resources/views/admin/pages/factory/edit.blade.php
  └── Section tabs/cards: Hero | Intro | Gallery 1 | Process | Material
```

Single-record pattern: only `edit` and `update` routes, no create/delete. `edit` loads the one row, `update` replaces all data.

### JSON Field Handling Strategy

- **`gallery_1`** (JSON array of image paths): Upload new → append to array. Delete → remove from array + delete file from disk.
- **`process_slider`** (JSON array): Same pattern as gallery_1.
- **`material_slider`** (JSON array): Same pattern as gallery_1.
- **`material_steps`** (JSON array of objects): Alpine.js repeater with add/remove/sort. Each step = `{title, description}`.
- **`hero_banner_desktop/mobile`**: Single file upload with `FileUploadHelper::replace()`.
- **`process_bottom_image`**: Single file upload with `FileUploadHelper::replace()`.

## Related Code Files

- Create: `app/Services/FactoryPageService.php`
- Create: `app/Http/Controllers/Admin/FactoryPageController.php`
- Create: `app/Http/Requests/FactoryPageRequest.php`
- Create: `resources/views/admin/pages/factory/edit.blade.php`
- Modify: `routes/web.php` (add routes)

## Implementation Steps

### Step 2.1: Register Routes

In `routes/web.php`, add under authenticated group:

```php
Route::prefix('pages')->name('pages.')->group(function () {
    Route::get('factory', [\App\Http\Controllers\Admin\FactoryPageController::class, 'edit'])
        ->name('factory.edit');
    Route::put('factory', [\App\Http\Controllers\Admin\FactoryPageController::class, 'update'])
        ->name('factory.update');
});
```

### Step 2.2: Create Form Request

`FactoryPageRequest` validates:
- Single image fields: `nullable|image|mimes:jpg,jpeg,png,webp|max:5120`
- Text fields: `nullable|string|max:500`
- Description fields: `nullable|string` (allow HTML)
- Gallery arrays (new images): `nullable|array`, each item `image|mimes:jpg,jpeg,png,webp|max:5120`
- Delete keys: `nullable|array` of indices to remove from JSON arrays
- Material steps: `nullable|array`, each step has `title` (string|max:500) and `description` (string)

### Step 2.3: Create Service

`FactoryPageService`:

```php
class FactoryPageService
{
    public function getFirstRecord(): PageFactory
    {
        return PageFactory::query()->firstOrFail();
    }

    public function update(array $data): PageFactory
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            // 1. Handle single image uploads (replace pattern)
            $singleImages = ['hero_banner_desktop', 'hero_banner_mobile', 'process_bottom_image'];
            foreach ($singleImages as $field) {
                if (isset($data[$field]) && $data[$field] instanceof UploadedFile) {
                    $data[$field] = FileUploadHelper::replace(
                        $data[$field], $model->{$field}, 'pages/factory'
                    );
                } else {
                    unset($data[$field]); // keep existing value
                }
            }

            // 2. Handle JSON gallery arrays (append new + remove deleted)
            $galleries = ['gallery_1', 'process_slider', 'material_slider'];
            foreach ($galleries as $gallery) {
                $existing = $model->{$gallery} ?? [];

                // Remove deleted indices
                if (!empty($data["delete_{$gallery}"])) {
                    foreach ($data["delete_{$gallery}"] as $index) {
                        if (isset($existing[$index])) {
                            FileUploadHelper::delete($existing[$index]);
                            unset($existing[$index]);
                        }
                    }
                    unset($data["delete_{$gallery}"]);
                }

                // Append new uploads
                if (!empty($data["new_{$gallery}"]) && is_array($data["new_{$gallery}"])) {
                    foreach ($data["new_{$gallery}"] as $file) {
                        if ($file instanceof UploadedFile) {
                            $existing[] = FileUploadHelper::upload($file, "pages/factory/{$gallery}");
                        }
                    }
                    unset($data["new_{$gallery}"]);
                }

                $data[$gallery] = array_values($existing); // re-index
            }

            // 3. Handle material_steps (full replacement from form)
            // $data['material_steps'] arrives as array from Alpine.js repeater

            // 4. Update fillable fields only
            $fillable = array_intersect_key($data, array_flip($model->getFillable()));
            $model->update($fillable);

            return $model->fresh();
        });
    }
}
```

### Step 2.4: Create Controller

`FactoryPageController`:

```php
class FactoryPageController extends Controller
{
    public function __construct(
        private readonly FactoryPageService $service
    ) {}

    public function edit(): View
    {
        return view('admin.pages.factory.edit', [
            'factory' => $this->service->getFirstRecord(),
        ]);
    }

    public function update(FactoryPageRequest $request): RedirectResponse
    {
        $this->service->update($request->validated());
        return back()->with('success', 'Cập nhật trang Xưởng sản xuất thành công.');
    }
}
```

### Step 2.5: Build Admin Blade UI

File: `resources/views/admin/pages/factory/edit.blade.php`

Layout uses `<x-admin.layout.app>` component (same as existing admin pages). Content organized as **tabs** (using Alpine.js `x-data` with `x-show`):

**Tab 1: Hero Section**
- Hero banner desktop: image upload with preview
- Hero banner mobile: image upload with preview

**Tab 2: Intro Section**
- `intro_title`: text input
- `intro_subtitle`: text input
- `intro_description`: textarea (TinyMCE optional, or plain textarea)

**Tab 3: Gallery 1**
- Alpine.js repeater: show existing images as thumbnails with delete button (X)
- "Add images" file input (multiple) for new uploads
- Preview grid for newly selected files before save

**Tab 4: Process Section**
- `process_title`: text input
- `process_description`: TinyMCE editor (CDN-loaded, minimal config)
- `process_slider`: Same Alpine.js gallery repeater as Tab 3
- `process_bottom_title`: text input
- `process_bottom_desc`: TinyMCE editor
- `process_bottom_image`: single image upload with preview

**Tab 5: Material Section**
- `material_slider`: Same Alpine.js gallery repeater
- `material_steps`: Alpine.js repeater — each step row has:
  - Step number label (auto-increment)
  - Title input
  - Description textarea
  - Remove button (X)
  - "Add Step" button at bottom

**Alpine.js repeater pattern for galleries:**
```html
<div x-data="{ images: {{ Js::from($factory->gallery_1 ?? []) }}, deletedIndices: [] }">
    <template x-for="(img, index) in images" :key="index">
        <div class="relative group">
            <img :src="'/storage/' + img" class="w-full h-full object-cover">
            <button @click="deletedIndices.push(index); images.splice(index, 1)"
                class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6">×</button>
        </div>
    </template>
    <input type="file" name="new_gallery_1[]" multiple @change="handleNewFiles($event)">
    <!-- Hidden inputs for deleted indices -->
    <template x-for="idx in deletedIndices" :key="idx">
        <input type="hidden" name="delete_gallery_1[]" :value="idx">
    </template>
</div>
```

**TinyMCE integration:**
```html
@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '.wysiwyg',
        height: 300,
        menubar: false,
        plugins: 'lists link',
        toolbar: 'bold italic | bullist numlist | link',
        language: 'vi',
        // Preserve Tailwind classes when editing existing content
        valid_elements: '*[*]',
        extended_valid_elements: 'p[class],span[class],ul[class],li[class],strong[class]',
    });
    // Before form submit, trigger tinymce.triggerSave() to sync content
    document.querySelector('form').addEventListener('submit', () => tinymce.triggerSave());
</script>
@endpush
```

**IMPORTANT — TinyMCE strips Tailwind classes**: TinyMCE by default removes class attributes from HTML. The `valid_elements: '*[*]'` and `extended_valid_elements` config above preserves them. However, if Admin types NEW content in TinyMCE, it will generate plain `<p>Text</p>` without Tailwind classes. To ensure consistent client rendering regardless:

**Phase 4 will add a CSS wrapper class** for rich text content (see Phase 4 Step 4.1), so even plain HTML from TinyMCE renders with correct typography.

## Success Criteria

- [ ] `GET /admin/pages/factory` renders edit form with current data from DB
- [ ] All 5 tabs work without page reload (Alpine.js x-show)
- [ ] Single image upload: preview shows, save persists, old file deleted from disk
- [ ] Gallery repeaters: add new images, preview them, delete existing images with X button
- [ ] Material steps: add/remove steps dynamically, all saved as JSON array
- [ ] TinyMCE editors sync content on form submit
- [ ] `PUT /admin/pages/factory` saves all data, returns with success flash
- [ ] Form re-populates with saved data after reload (no data loss)

## Risk Assessment

- **Risk**: TinyMCE CDN might not load if no internet → mitigation: provide fallback textarea if TinyMCE fails
- **Risk**: Alpine.js repeater index tracking for deleted items → mitigation: use `deletedIndices` array sent as hidden inputs
- **Risk**: Large number of image uploads hitting `post_max_size` → mitigation: validate individual file sizes (5MB), batch saves
