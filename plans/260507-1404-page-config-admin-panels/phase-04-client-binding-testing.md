---
phase: 4
title: "Client Binding & Testing"
status: completed
priority: P2
effort: "4h"
dependencies: ["phase-02-factory-admin-panel", "phase-03-contact-faq-admin-panels"]
---

# Phase 4: Client Binding & Testing

## Overview

Replace hardcoded content in 3 client pages with dynamic data from the new config tables. Update client controllers to fetch data from services, update Blade views to render dynamic content. Write Pest tests for admin controllers and services. Run lint + tests.

## Requirements

- Functional: 3 client pages render content from DB instead of hardcoded strings/images
- Non-functional:
  - **Zero breaking changes to client-facing UI** — data source changes only, pixel-identical output
  - All existing admin tests pass (no regression)
  - New tests cover admin page config CRUD
  - `vendor/bin/pint --dirty` passes

## 🚨 Three Critical Pitfalls to Prevent UI Breakage

These MUST be handled during implementation:

### Pitfall 1: TinyMCE Strips Tailwind CSS Classes
When Admin edits `process_description` or `process_bottom_desc` via TinyMCE and types NEW content, TinyMCE produces plain `<p>Text</p>` without Tailwind classes. The existing hardcoded HTML has inline Tailwind classes like `<p class="text-[15px]/[1.6] md:text-base/9 text-primary space-y-6">`.

**Solution**: Add a CSS wrapper class `.rich-text-content` in `public/assets/css/main.css` that applies typography styles to plain HTML output, so even unstyled HTML renders consistently:

```css
/* Rich Text Content — ensures TinyMCE output renders with correct typography */
.rich-text-content p {
    font-size: 15px;
    line-height: 1.6;
    color: var(--color-primary, #1a1a1a);
    margin-bottom: 1rem;
    text-align: justify;
}
.rich-text-content ul {
    list-style: decimal;
    padding-left: 1.25rem;
    margin-bottom: 1rem;
}
.rich-text-content li {
    margin-bottom: 0.25rem;
}
.rich-text-content strong {
    font-weight: 700;
    color: var(--color-primary, #1a1a1a);
}
@media (min-width: 768px) {
    .rich-text-content p {
        font-size: 16px;
        line-height: 1.7;
    }
}
```

Wrap all rich text output in this class:
```blade
<div class="rich-text-content">
    {!! $factory->process_description !!}
</div>
```

### Pitfall 2: Broken Images When Data is Null
If Admin hasn't uploaded an image yet (column is `null`), `{{ asset('storage/' . $contact->map_image) }}` produces `<img src="http://domain.com/storage/">` — browser shows broken image icon, breaking layout.

**`onerror` is insufficient** — the empty `<img>` still occupies space in flex/grid layouts.

**Solution**: Always guard with `@if(!empty(...))`:
```blade
@if(!empty($factory->hero_banner_desktop))
    <img src="{{ asset('storage/' . $factory->hero_banner_desktop) }}" class="hidden md:block w-full h-auto object-cover">
@endif
```

### Pitfall 3: Material Steps JSON Key Mismatch
The JS in `material.blade.php` reads `step.number`, `step.title`, `step.description` from `section4Steps`. The Alpine.js repeater in Admin must produce objects with these **exact same keys**. If Admin form uses different keys (e.g. `step_title` vs `title`), Swiper renders `undefined`.

**Solution**: In Admin Blade (Phase 2), the material_steps repeater MUST use keys `number`, `title`, `description` matching the client JS. Document this contract explicitly. The JS in Phase 4 reads the same keys:

```js
const section4Steps = {{ Js::from($factory->material_steps ?? []) }};
// Each step: { number: "1", title: "TITLE", description: "Description text..." }
```

## Related Code Files

- Modify: `app/Http/Controllers/Client/FactoryController.php`
- Modify: `app/Http/Controllers/Client/ContactController.php`
- Modify: `app/Http/Controllers/Client/FaqController.php`
- Modify: `resources/views/clients/factory/partials/hero.blade.php`
- Modify: `resources/views/clients/factory/partials/intro.blade.php`
- Modify: `resources/views/clients/factory/partials/gallery-1.blade.php`
- Modify: `resources/views/clients/factory/partials/process.blade.php`
- Modify: `resources/views/clients/factory/partials/material.blade.php`
- Modify: `resources/views/clients/factory/partials/gallery-2.blade.php`
- Modify: `resources/views/clients/contact/index.blade.php`
- Modify: `resources/views/clients/faq/index.blade.php`
- Modify: `public/assets/css/main.css` (add .rich-text-content styles)
- Create: `tests/Feature/Admin/FactoryPageTest.php`
- Create: `tests/Feature/Admin/ContactPageTest.php`
- Create: `tests/Feature/Admin/FaqPageTest.php`

## Implementation Steps

### Step 4.1: Bind Factory Client Page

**Client Controller** (`FactoryController`):
```php
use App\Services\FactoryPageService;

public function index(FactoryPageService $service)
{
    return view('clients.factory.index', [
        'factory' => $service->getFirstRecord(),
    ]);
}
```

**Blade Partials** — replace hardcoded content with null-safe dynamic data:

**`hero.blade.php`** — Null-safe image guards:
```blade
<section class="w-full">
  @if(!empty($factory->hero_banner_desktop))
    <img src="{{ asset('storage/' . $factory->hero_banner_desktop) }}" alt="Xưởng sản xuất Thanh Hải"
      class="w-full h-auto object-cover hidden md:block" />
  @endif
  @if(!empty($factory->hero_banner_mobile))
    <img src="{{ asset('storage/' . $factory->hero_banner_mobile) }}" alt="Xưởng sản xuất Thanh Hải"
      class="w-full h-auto block object-cover md:hidden" />
  @endif
</section>
```

**`intro.blade.php`** — Null-safe text fields:
```blade
<h2 class="..." data-aos="fade-up">{{ $factory->intro_title ?? 'Nhà xưởng' }}</h2>
<h3 class="..." data-aos="fade-up">{{ $factory->intro_subtitle ?? 'QUY MÔ ẤN TƯỢNG...' }}</h3>
<p class="..." data-aos="fade-up" data-aos-delay="100">{{ $factory->intro_description ?? '' }}</p>
```

**`gallery-1.blade.php`** — Dynamic swiper slides with `@if` array check:
```blade
@if(!empty($factory->gallery_1))
    @foreach($factory->gallery_1 as $image)
        <div class="swiper-slide w-full md:w-[70%] lg:w-[80%]">
            <div class="aspect-[2/1] overflow-hidden shadow-lg">
                <img src="{{ asset('storage/' . $image) }}" alt="Gallery image"
                    class="w-full h-full object-cover transform transition-transform duration-700 hover:scale-105" />
            </div>
        </div>
    @endforeach
@endif
```

**`process.blade.php`** — Rich text with CSS wrapper + null-safe slider:
```blade
<h3 class="..." data-aos="fade-up">{{ $factory->process_title ?? 'QUY TRÌNH...' }}</h3>

{{-- Rich text content wrapped in .rich-text-content for consistent typography --}}
<div class="rich-text-content text-[15px]/[1.6] md:text-base/9 text-primary space-y-6 md:space-y-2 text-justify">
    {!! $factory->process_description !!}
</div>

{{-- process_slider swiper --}}
@if(!empty($factory->process_slider))
    @foreach($factory->process_slider as $image)
        <div class="swiper-slide ..."><img src="{{ asset('storage/' . $image) }}"></div>
    @endforeach
@endif

{{-- Bottom section --}}
<h3 class="...">{{ $factory->process_bottom_title ?? 'SỨC MẠNH CỦA SỰ KẾT HỢP...' }}</h3>
<div class="rich-text-content ...">
    {!! $factory->process_bottom_desc !!}
</div>
@if(!empty($factory->process_bottom_image))
    <img src="{{ asset('storage/' . $factory->process_bottom_image) }}" alt="..." class="w-full h-full object-cover">
@endif
```

**`material.blade.php`** — Replace hardcoded sliders + JS steps array:
```blade
{{-- material_slider images --}}
@if(!empty($factory->material_slider))
    @foreach($factory->material_slider as $image)
        <div class="swiper-slide w-full md:w-[70%] lg:w-[80%]">
            <div class="aspect-[12/5] overflow-hidden shadow-lg bg-neutral-1">
                <img src="{{ asset('storage/' . $image) }}" alt="Phân xưởng" class="...">
            </div>
        </div>
    @endforeach
@endif
```

**IMPORTANT — JS data binding**: The material steps JS MUST use the same key names as Admin input fields. The existing JS reads `{ number, title, description }`. Admin side (Phase 2) must use the Alpine.js repeater with input names like `material_steps[0][number]`, `material_steps[0][title]`, `material_steps[0][description]`:

```blade
@push('scripts')
<script>
    // Replace hardcoded section4Steps with dynamic data from DB
    const section4Steps = {{ Js::from($factory->material_steps ?? []) }};
    // Each step: { number: "1", title: "LỰA CHỌN...", description: "Đây là bước..." }
    // MUST match the keys produced by Admin Alpine.js repeater
</script>
@endpush
```

**`gallery-2.blade.php`** — Same pattern as gallery-1 with null-safe loop.

### Step 4.2: Bind Contact Client Page

**Client Controller** (`ContactController`):
```php
use App\Services\ContactPageService;

public function index(ContactPageService $service)
{
    return view('clients.contact.index', [
        'contact' => $service->getFirstRecord(),
    ]);
}
```

**Blade** (`clients/contact/index.blade.php`) — All fields null-safe:

```blade
{{-- Map image — guarded with @if --}}
@if(!empty($contact->map_image))
    <img src="{{ asset('storage/' . $contact->map_image) }}" alt="Contact Map" class="w-full h-full object-cover object-center">
@endif

{{-- Hotline — with fallback --}}
<span class="...">Hotline: {{ $contact->hotline ?? '0966 55 8808' }}</span>

{{-- Zalo link — with fallback --}}
<a href="{{ $contact->zalo_link ?? '#' }}" target="_blank">
    @if(!empty($contact->zalo_image))
        <img src="{{ asset('storage/' . $contact->zalo_image) }}" alt="Zalo" class="w-12 h-12 rounded-full object-cover">
    @else
        <img src="{{ asset('assets/images/zalo2.png') }}" alt="Zalo" class="w-12 h-12 rounded-full object-cover">
    @endif
</a>

{{-- Form title — with fallback --}}
<h1 class="...">{{ $contact->form_title ?? "Hãy nói với chúng tôi những mong muốn của bạn" }}</h1>
```

### Step 4.3: Bind FAQ Client Page

**Client Controller** (`FaqController`):
```php
use App\Services\FaqPageService;
use App\Services\FaqService;

public function index(FaqPageService $pageService, FaqService $faqService)
{
    return view('clients.faq.index', [
        'faqPage' => $pageService->getFirstRecord(),
        'faqsGrouped' => $faqService->getGroupedByCategory(),
    ]);
}
```

**Blade** (`clients/faq/index.blade.php`):

Banner — null-safe:
```blade
@if(!empty($faqPage->banner_image))
    <img src="{{ asset('storage/' . $faqPage->banner_image) }}" alt="FAQ Banner" class="w-full h-full object-cover">
@else
    <img src="{{ asset('assets/images/faq-banner.png') }}" alt="FAQ Banner" class="w-full h-full object-cover">
@endif
```

Accordion content — dynamic loop using `Faq::CATEGORIES` constant:
```blade
@foreach($faqsGrouped as $category => $faqs)
    @php $categoryTitle = \App\Models\Faq::CATEGORIES[$category] ?? $category; @endphp
    <div id="{{ $category }}" class="mt-12 md:mt-20 pt-4 scroll-mt-24">
        <div class="flex items-center gap-5 mb-8 pt-10">
            <div class="w-[2px] h-8 bg-secondary"></div>
            <h2 class="text-2xl md:text-[32px] font-semibold text-primary font-arima">{{ $categoryTitle }}</h2>
        </div>
        <div class="space-y-4 border-t border-black/5 pt-6">
            @foreach($faqs as $faq)
                <div class="accordion-item border-b border-black/5 py-6">
                    <button class="accordion-button w-full flex justify-between items-center text-left gap-4 group focus:outline-none">
                        <span class="text-base md:text-lg font-semibold text-primary/80 group-hover:text-secondary transition-colors font-arima">
                            {{ $faq->question }}
                        </span>
                        <span class="accordion-icon flex-shrink-0 text-secondary transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </span>
                    </button>
                    <div class="accordion-content overflow-hidden transition-all duration-300 max-h-0">
                        <p class="text-sm/7 md:text-base/8 text-primary text-justify pt-4">
                            {{ $faq->answer }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach
```

**Sidebar navigation**: Generate dynamically from `$faqsGrouped` keys + `Faq::CATEGORIES`:
```blade
<nav class="flex flex-col gap-5">
    @foreach(array_keys($faqsGrouped->toArray()) as $category)
        <a href="#{{ $category }}"
            class="text-xs md:text-sm font-medium text-primary uppercase hover:text-secondary transition-colors">
            {{ \App\Models\Faq::CATEGORIES[$category] ?? $category }}
        </a>
    @endforeach
</nav>
```

### Step 4.4: Add Rich Text CSS

Add to `public/assets/css/main.css`:
```css
/* Rich Text Content — ensures TinyMCE output renders with consistent typography */
.rich-text-content p { font-size: 15px; line-height: 1.6; color: #1a1a1a; margin-bottom: 1rem; text-align: justify; }
.rich-text-content ul { list-style: decimal; padding-left: 1.25rem; margin-bottom: 1rem; }
.rich-text-content ol { list-style: decimal; padding-left: 1.25rem; margin-bottom: 1rem; }
.rich-text-content li { margin-bottom: 0.25rem; }
.rich-text-content strong { font-weight: 700; color: #1a1a1a; }
@media (min-width: 768px) {
    .rich-text-content p { font-size: 16px; line-height: 1.7; }
}
```

### Step 4.5: Write Tests

Create Pest feature tests for each admin page config:

**`FactoryPageTest`**:
```php
use App\Models\PageFactory;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

beforeEach(function () {
    $this->admin = \App\Models\User::where('role', 'superadmin')->first()
        ?? \App\Models\User::factory()->create(['role' => 'superadmin']);
});

test('factory edit page renders', function () {
    actingAs($this->admin)->get(route('admin.pages.factory.edit'))->assertOk();
});

test('factory page updates text fields', function () {
    actingAs($this->admin)
        ->put(route('admin.pages.factory.update'), ['intro_title' => 'Test Title'])
        ->assertRedirect()
        ->assertSessionHas('success');
    expect(PageFactory::first()->intro_title)->toBe('Test Title');
});
```

**`ContactPageTest`**: Similar pattern — test edit renders, update persists.

**`FaqPageTest`**: Test FAQ CRUD:
```php
use App\Models\Faq;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function Pest\Laravel\delete;

test('faq item can be created', function () {
    actingAs($this->admin)
        ->post(route('admin.pages.faqs.store'), [
            'category' => 'sản-phẩm', 'question' => 'Test Q?', 'answer' => 'Test A.',
            'sort_order' => 1, 'is_active' => true,
        ])
        ->assertRedirect()->assertSessionHas('success');
    expect(Faq::where('question', 'Test Q?')->where('is_delete', 0)->exists())->toBeTrue();
});

test('faq item can be updated', function () { /* ... */ });
test('faq item can be soft deleted', function () { /* ... */ });
```

### Step 4.6: Optional — Add Cache Layer

Since page config rarely changes but gets queried on every client page load, consider caching:

```php
// In client controllers
use Illuminate\Support\Facades\Cache;

$factory = Cache::remember('page_factory', 3600, fn() => $service->getFirstRecord());
```

**Invalidate cache** in Admin controllers' `update()` method:
```php
Cache::forget('page_factory');
```

Skip if performance is not a current concern — this is an optimization, not a requirement.

### Step 4.7: Final Checks

1. Run `vendor/bin/pint --dirty`
2. Run `php artisan test --compact` — all tests pass
3. Run `php artisan migrate:fresh --seed` — no errors
4. Manual visual check: compare each client page against production before merge

## Success Criteria

- [ ] `/xuong-san-xuat` renders dynamic data from `page_factory` table, identical to current static version
- [ ] `/lien-he` renders dynamic data from `page_contact` table, identical to current static version
- [ ] `/cau-hoi-thuong-gap` renders from `faqs` + `page_faq` tables, identical to current static version
- [ ] Rich text content renders with correct typography via `.rich-text-content` CSS
- [ ] No broken image icons on any page (all `@if(!empty(...))` guards in place)
- [ ] All new Pest tests pass: `php artisan test --compact`
- [ ] No regression: all existing tests pass
- [ ] `vendor/bin/pint --dirty` passes clean
- [ ] Empty DB state: pages still render without errors (fallback values in `??` operators)

## Null-Safety Checklist (Review Before Merge)

Every dynamic `{{ }}` output MUST have one of these:

| Type | Guard |
|------|-------|
| String field | `{{ $var ?? 'Fallback text' }}` |
| HTML content | `<div class="rich-text-content">{!! $var !!}</div>` |
| Image src | `@if(!empty($var)) <img src="{{ asset('storage/' . $var) }}"> @endif` |
| Array loop | `@if(!empty($var)) @foreach($var as $item) ... @endforeach @endif` |
| JSON in JS | `{{ Js::from($var ?? []) }}` |
