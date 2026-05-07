# Project Overview & Product Development Requirements (PDR)

## Project Identification

| Field | Value |
|-------|-------|
| Project Name | TH Ceramics Fullstack |
| Domain | th-ceramics.com (inferred) |
| Business | Thanh Hai Ceramics — traditional Vietnamese roof tile and decorative brick manufacturer |
| Tech Stack | Laravel 12, PHP 8.2+, MariaDB, Blade, Tailwind CSS, vanilla JS |
| Repository | github.com/Deocomate/th-ceramics-fullstack |

## Purpose

Build a bilingual (Vietnamese) corporate e-commerce website that showcases 9 product categories of traditional ceramic building materials. The site serves as both a product catalog and a content management system, with a full admin panel for staff to manage products, images, and site content.

## Target Users

### Admin Users
- **Superadmin**: Full system access including user management and all CRUD operations
- **Admin**: Content management for all product sections and detail items

### Public Users (End Customers)
- Architects and contractors researching ceramic building materials
- Homeowners looking for traditional roof tiles and decorative bricks
- General visitors browsing the product catalog

## Functional Requirements

### Admin Panel (`/admin/*`)
- **FR1**: Authentication system with email/password login, password reset via email
- **FR2**: Dashboard with summary overview
- **FR3**: User management (superadmin-only): create, edit, delete admin accounts
- **FR4**: CRUD for 9 product section pages + 3 page config panels (each has a single-record config)
- **FR5**: CRUD for product detail items within each category (42 tables across 9 migrations)
- **FR6**: Image upload with automatic file management (replace/delete via FileUploadHelper)
- **FR7**: Soft-delete with restore for product detail items (`is_delete` boolean)
- **FR8**: Global product code uniqueness validation across all detail tables
- **FR9**: Sub-resource management (colors, classifications, ratings, galleries, hallmarks per category)

### Public Site (Client Pages)
- **FR10**: Home page with featured content
- **FR11**: Product category pages with listing and detail views (9 categories)
- **FR12**: About us, factory tour, contact, FAQ pages (now dynamic from DB via page config admin panels)
- **FR13**: News section with listing and detail
- **FR14**: Projects section with listing and detail
- **FR15**: Customer service pages (policies, guides)
- **FR16**: FAQ page (dynamic: banner config + FAQ items CRUD via admin panel)
- **FR17**: Showroom page
- **FR18**: Cart and checkout pages (UI exists, backend pending)

### SEO & URL Strategy
- **FR19**: Vietnamese-language SEO URLs (`/san-pham/ngoi-am-duong/{id}`)
- **FR20**: 301 redirects from old English URLs to preserve search rankings
- **FR21**: Meta title/description support per page

## Non-Functional Requirements

### SEO
- **NFR1**: All public URLs use Vietnamese slugs for search engine relevance
- **NFR2**: 301 permanent redirects for all legacy English URLs
- **NFR3**: Meta description tags on key pages
- **NFR4**: Structured content hierarchy with breadcrumb navigation

### Performance
- **NFR5**: Database-driven caching (session, cache, queue all use `database` driver)
- **NFR6**: Lazy loading for images via Swiper.js and AOS animations
- **NFR7**: Eager loading for related models to prevent N+1 queries

### Security
- **NFR8**: RBAC with RoleMiddleware (`superadmin`, `admin` roles)
- **NFR9**: Form validation via dedicated Form Request classes
- **NFR10**: CSRF protection (Laravel default)
- **NFR11**: Password hashing via Laravel's `hashed` cast
- **NFR12**: Unauthenticated users redirected to admin login

### Data Integrity
- **NFR13**: Global product code uniqueness across 9 detail tables
- **NFR14**: Custom primary key naming convention (`{table_name}_id`)
- **NFR15**: JSON columns for arrays (images, descriptions, size descriptions)
- **NFR16**: Soft-delete via boolean `is_delete` flag (not Laravel SoftDeletes trait)

### Maintainability
- **NFR17**: Layered architecture: Controller -> Service -> Model
- **NFR18**: Constructor dependency injection with `private readonly` promoted properties
- **NFR19**: File upload helper for consistent image management
- **NFR20**: Business logic isolated in Service classes (no repository pattern)

## Constraints

- **C1**: No JavaScript framework — vanilla JS only with Swiper.js and AOS libraries
- **C2**: Tailwind CSS loaded via CDN (no build step for frontend assets)
- **C3**: Vite configured but unused; assets served from `public/assets/` directly
- **C4**: All session/cache/queue storage uses database driver (no Redis available in current setup)
- **C5**: No Livewire, no events/listeners, no observers, no repository pattern
- **C6**: PHP 8.2+ required for constructor property promotion syntax
- **C7**: MariaDB/MySQL required (not SQLite for production)

## Current Status (May 2026)

| Area | Status |
|------|--------|
| Database schema | Complete (42 tables, 9 migrations) |
| Admin CRUD | Complete (all 9 categories + 3 page config panels) |
| Product seeding | Complete (4 seeders: User, ProductType, ProductDetail) |
| Page configuration | Complete (Factory, Contact, FAQ admin panels with Alpine.js auto-resize textareas) |
| Client product pages | In progress (9 categories) |
| Cart/Checkout | UI stubs only, backend pending |
| News/Projects | Controller structure done, views pending |
| Customer Service | Controller + views exist |
| Tests | 7 Pest files (14 tests, 31 assertions, all passing) |

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 0.1.0 | 2026-04-26 | Initial migration setup (product type tables) |
| 0.2.0 | 2026-05-01 | Product detail migrations, model/service creation |
| 0.3.0 | 2026-05-06 | Admin CRUD complete, client product data binding |
| 0.3.1 | 2026-05-07 | Page configuration admin panels (Factory/Contact/FAQ), TinyMCE replaced with auto-resize textareas, dynamic client pages, 12 new Pest tests, admin UI/UX Pro Max refactor |
