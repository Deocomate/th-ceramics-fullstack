# Design Guidelines

## Client Frontend

### Tailwind CSS Configuration

Tailwind is loaded via CDN with inline configuration in the client layout (`resources/views/components/client/layouts/main.blade.php`).

#### Color Palette (Client)

```
Primary (textPrimary)   #2E2F2A  dark olive/gray — main text, headings
Secondary               #C76E00  amber/orange — accent, CTAs, highlights
Neutral 1               #EFE4DE  warm beige — backgrounds, sections
Neutral 2               #F5EDE7  lighter beige — alternate backgrounds
Background Primary      #FFF     white — main page background
Background Secondary    #EFE4DE  warm beige — section backgrounds
```

#### Typography (Client)

| Font | Usage | Fallback |
|------|-------|----------|
| Archivo | Body text, general UI | sans-serif |
| Italianno | Decorative headings | cursive |
| Arima | System UI | system-ui |
| Arbutus Slab | Accent headings | serif |
| Charm | Decorative text | cursive |
| Lavishly Yours | Very decorative text | cursive |
| Ephesis | Decorative text | cursive |
| Carattere | Decorative text | cursive |
| Advent Pro | Headlines | sans-serif |

Fonts are loaded from Google Fonts. The base font is `font-archivo` applied to `<body>`.

### Client Layout Structure

```
components/client/layouts/main.blade.php
├── <head>
│   ├── Tailwind CDN (cdn.tailwindcss.com)
│   ├── Swiper.js CSS (cdn.jsdelivr.net)
│   ├── AOS CSS (unpkg.com)
│   └── Custom CSS (assets/css/main.css)
├── <body class="min-h-screen flex flex-col font-archivo text-primary">
│   ├── <x-header />           # Navigation + branding
│   ├── <main>                 # Page content ($slot)
│   │   └── @stack('styles')   # Page-specific CSS
│   ├── <x-footer />           # Footer with newsletter option
│   ├── JS: app.js (module)
│   ├── Swiper.js JS
│   ├── AOS JS (initialized with duration:800, once:true, offset:100)
│   └── @stack('scripts')      # Page-specific JS
```

### Admin Frontend

#### Color Palette (Admin)

```
Brand Red      #A31D1D  — primary brand color, headers
Brand Dark Red #8A1818  — hover states, active elements
Body Background #F3F6F9 — light gray page background
```

#### Typography (Admin)

| Font | Usage |
|------|-------|
| Inter | All UI text (weights: 400, 500, 600, 700) |

Loaded from Google Fonts with `display=swap`.

### Admin Layout Structure

```
components/admin/layout/app.blade.php
├── <head>
│   ├── Tailwind CDN
│   ├── Google Fonts: Inter
│   └── Custom scrollbar styles
├── <body class="h-full" style="background:#F3F6F9">
│   ├── Flex container (h-full min-h-screen)
│   │   ├── <x-admin.layout.sidebar />     # Navigation sidebar
│   │   └── Main content (ml-64, flex-1)
│   │       ├── Header (sticky, white bg)   # Title + breadcrumb + date
│   │       ├── Flash messages (success/error)
│   │       └── <main class="flex-1 p-6">   # Page content
│   └── @stack('scripts')
```

### Responsive Design Approach

- **Client**: Uses Tailwind responsive prefixes (`sm:`, `md:`, `lg:`, `xl:`)
- **Design**: Mobile-first with breakpoints at standard Tailwind widths
- **Admin**: Fixed sidebar (16rem / 64 width) on desktop, main content fills remaining space
- **Product cards**: Separate `mobile-product-card` and `desktop-product-card` components for different screen sizes
- **Swiper.js**: Used for responsive image sliders/carousels across product pages

### Animation Patterns

- **AOS (Animate on Scroll)**: Fade-in animations triggered on scroll with `duration: 800ms`, `once: true`, `offset: 100px`
- **CSS Marquee**: Infinite horizontal scroll animation for image tickers (`animation: marquee 15s linear infinite`)

### Component Patterns

#### Shared Product Components (resources/views/components/products/)

| Component | Purpose |
|-----------|---------|
| `product-card.blade.php` | Reusable product listing card |
| `mobile-product-card.blade.php` | Mobile-specific product card |
| `desktop-product-card.blade.php` | Desktop-specific product card |
| `product-grid.blade.php` | Grid layout for product listings |
| `product-detail-container.blade.php` | Detail page wrapper |
| `product-image-swiper.blade.php` | Image carousel with Swiper |
| `breadcrumb.blade.php` | Breadcrumb navigation |
| `color-palette.blade.php` | Color selection UI |
| `quantity-calculator.blade.php` | Quantity estimation tool |
| `weight-calculator.blade.php` | Weight estimation tool |
| `product-filter.blade.php` | Product filtering controls |
| `recommendations.blade.php` | Related products section |
| `applications.blade.php` | Product application info |
| `fabrication-process.blade.php` | Manufacturing process |
| `installation-guide.blade.php` | Installation instructions |
| `faq-content.blade.php` | FAQ accordion |
| `faq2.blade.php` | Alternate FAQ layout |
| `works.blade.php` | How it works section |
| `works-simple.blade.php` | Simplified works section |
| `outstanding-value.blade.php` | Value proposition section |
| `journey-video.blade.php` | Brand journey video |
| `hai-vm-calculator.blade.php` | Ngoi Hai Van Mieu calculator |
| `trang-tri-process.blade.php` | Gach Trang Tri process |

#### Shared Site Components (resources/views/components/)

| Component | Purpose |
|-----------|---------|
| `header.blade.php` | Site navigation header |
| `footer.blade.php` | Site footer with newsletter |
| `newsletter.blade.php` | Email signup form |
| `catalog-button.blade.php` | Catalog download CTA |
| `home-awards.blade.php` | Awards/trust badges |
| `faq-faq-contact.blade.php` | FAQ contact section |

### Product Page Structure (Client)

Each product category follows this view structure:

```
clients/products/{category}/
├── index.blade.php          # Product listing page
├── detail.blade.php         # Product detail page
└── partials/
    ├── banner.blade.php     # Hero/banner section
    ├── hero.blade.php       # Alternative hero
    └── ...                  # Category-specific sections
```

### JavaScript Libraries

| Library | Version | Purpose |
|---------|---------|---------|
| Alpine.js | 3.x | Admin interactive UI: tab navigation, auto-resize textareas, image galleries, repeater fields |
| Swiper | 12.x | Image carousels, product galleries (client) |
| AOS | 2.3.1 | Scroll-triggered animations (client) |
| PDF.js | 2.16.105 | PDF rendering in catalog flipbook reader |
| StPageFlip | 2.0.7 | 3D page-flip animation for catalog reader |
| GLightbox | Latest | Image lightbox gallery (project detail pages) |
| Tailwind CSS | Latest (CDN) | Utility CSS framework |

All loaded via CDN. No bundler or build step for production assets (main client layout uses Tailwind CDN; Vite used for auth layout only).

### Styling Approach

1. **Tailwind utility classes** in Blade templates for 95% of styling
2. **Custom CSS** in `public/assets/css/main.css` for complex animations and overrides
3. **Inline Tailwind config** in layout files for theme customization (no tailwind.config.js file)
4. **No CSS preprocessors** (SASS/LESS) — Tailwind CDN provides all needed utilities

### Accessibility (a11y) Guidelines

- **Semantic HTML**: Use `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>` for document structure
- **Image alt text**: All `<img>` elements must have `alt` attributes (descriptive Vietnamese text)
- **Focus states**: Interactive elements use Tailwind `focus:ring` or `focus:outline` for keyboard navigation
- **Form labels**: Every form input has a corresponding `<label>` with `for` attribute
- **Color contrast**: Text on background follows primary (#2E2F2A on #FFF) for sufficient contrast
- **Skip navigation**: Not yet implemented (future enhancement)
- **ARIA labels**: Used sparingly on non-semantic interactive elements (e.g., `aria-label` on icon buttons)
- **Screen reader testing**: Not yet conducted (future enhancement)
