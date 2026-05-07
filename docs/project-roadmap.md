# Project Roadmap

## Phase 1: Core Setup & Admin CRUD (COMPLETE)

| Status | Task | Details |
|--------|------|---------|
| 100% | Laravel 12 project initialization | Composer setup, environment config |
| 100% | Database schema design | 5 migrations creating 38 tables across 9 product categories |
| 100% | Eloquent models | 38 models with relationships, custom PKs, casts |
| 100% | Service layer | 33 service classes with business logic |
| 100% | Admin authentication | Login, logout, forgot/reset password |
| 100% | Admin dashboard | Overview page |
| 100% | Admin CRUD: section pages | 13 single-record section controllers (index + update) — includes 3 page config panels (Factory/Contact/FAQ) |
| 100% | Admin CRUD: detail items | 14 detail controllers (full CRUD + restore) |
| 100% | Admin CRUD: sub-resources | Colors, classifications, ratings, galleries, hallmarks |
| 100% | Image upload system | FileUploadHelper with replace/delete |
| 100% | RBAC system | RoleMiddleware with superadmin/admin roles |
| 100% | User management | CRUD for admin accounts (superadmin-only) |
| 100% | Global code uniqueness | GlobalProductCodeService |

**Deliverables**: Full admin panel for all 9 product categories with image management, soft-delete/restore, RBAC.

---

## Phase 2: Product Seeding (COMPLETE)

| Status | Task | Details |
|--------|------|---------|
| 100% | User seeder | Default superadmin + admin accounts |
| 100% | Product type seeder | Sample data for all 9 categories with images, descriptions, prices |
| 100% | Product detail seeder | 105 records across 9 `_ct` tables + 4 child tables with file-copy image seeding |
| 100% | DatabaseSeeder orchestration | Calls UserSeeder, ProductTypeSeeder, ProductDetailSeeder in order |

**Deliverables**: Pre-populated database with sample products for development and testing.

---

## Phase 3: Client Product Pages (CURRENT)

| Status | Task | Details |
|--------|------|---------|
| 100% | Client routing | Vietnamese SEO URLs with 301 redirects from English paths |
| 100% | Client layout | Tailwind CDN, Swiper.js, AOS, Google Fonts, responsive design |
| 100% | Home page | Featured products, services, brand highlights |
| 100% | About / Factory / Contact / FAQ | Dynamic content pages bound to DB via page config admin panels |
| 100% | Product listing pages | 9 category index views |
| 100% | Product detail pages | 9 category detail views with images, specs, pricing |
| ~80% | Data binding | Client controllers wired to services; some views pending |
| 100% | Page config admin panels | Factory (5-tab Alpine.js, auto-resize textareas), Contact (form), FAQ (banner + FAQ items CRUD with modal) |
| 0% | SEO meta tags | Per-page title/description via `@props` |
| 0% | News pages | Controller + routes exist, views need implementation |
| 0% | Projects pages | Controller + routes exist, views need implementation |
| 0% | Customer service pages | Controller + views exist, content may need refinement |

**Deliverables**: Fully functional public website displaying product data from the database.

---

## Phase 4: Cart & Checkout (PLANNED)

| Status | Task | Details |
|--------|------|---------|
| 0% | Cart functionality | Add/remove items, quantity management, session-based cart |
| 0% | Checkout flow | Address form, order summary, order placement |
| 0% | Order management | Admin order list, status updates |
| 0% | Cart/checkout views | UI exists as stubs, backend integration needed |

**Deliverables**: E-commerce functionality allowing customers to place orders online.

---

## Phase 5: Advanced Features (PLANNED)

| Status | Task | Details |
|--------|------|---------|
| 0% | News CMS | Admin CRUD for news articles, client display |
| 0% | Projects CMS | Admin CRUD for projects, client display |
| 0% | Customer request form | Contact form with email notification |
| 0% | Product search | Full-text search across product catalog |
| 0% | Product filtering | Filter by category, price, size, color |
| 0% | Multi-language | English version alongside Vietnamese |
| 0% | Performance optimization | Cache warming, query optimization, image optimization |
| 0% | Comprehensive testing | Pest feature tests for admin and client flows |
| 0% | Vite integration | Configure proper asset building pipeline |

**Deliverables**: Complete e-commerce platform with content management, search, and multi-language support.

---

## Progress Summary

```
Phase 1: Core Setup & Admin CRUD     ████████████████████ 100%
Phase 2: Product Seeding              ████████████████████ 100%
Phase 3: Client Product Pages         ██████████░░░░░░░░░░  50%
Phase 4: Cart & Checkout              ░░░░░░░░░░░░░░░░░░░░   0%
Phase 5: Advanced Features            ░░░░░░░░░░░░░░░░░░░░   0%
                                    ─────────────────────
Total:                                █████░░░░░░░░░░░░░░░  33%
```

## Key Milestones

| Date | Milestone |
|------|-----------|
| 2026-04-26 | First migration (product type tables) |
| 2026-05-01 | Detail migrations + model/service creation |
| 2026-05-06 | Admin CRUD complete, client data binding started |
| 2026-05-07 | Page configuration admin panels complete; Factory/Contact/FAQ pages dynamic |
| TBD | Client product pages complete |
| TBD | Cart/checkout MVP |
| TBD | Public launch |

## Dependencies

- **Phase 4 depends on**: Phase 3 completion (product data must be displayed before cart can reference products)
- **Phase 5 depends on**: Phase 4 completion (cart/checkout is prerequisite for orders management)
