---
title: "Page Configuration Admin Panels (Factory, Contact, FAQ)"
description: "Build admin CRUD for 3 single pages: Factory (Xưởng Sản Xuất), Contact (Liên Hệ), FAQ (Câu Hỏi Thường Gặp) with JSON repeater fields, WYSIWYG editor, image preview, and client-side data binding."
status: pending
priority: P2
branch: "main"
tags: ["admin", "page-config", "factory", "contact", "faq", "json-fields", "file-upload"]
blockedBy: []
blocks: []
created: "2026-05-07T07:13:06.888Z"
createdBy: "ck:plan"
source: skill
---

# Page Configuration Admin Panels (Factory, Contact, FAQ)

## Overview

Add admin configuration panels for 3 static client pages currently serving hardcoded content: Factory (`/xuong-san-xuat`), Contact (`/lien-he`), and FAQ (`/cau-hoi-thuong-gap`). Each page gets its own DB table, Service, Controller, and Blade admin UI with JSON repeater fields for dynamic content (image sliders, FAQ items, material steps).

## Architecture

Follows existing single-record pattern (`DenGomSu`, `NgoiAmDuong`): one row per page config, index route shows edit form, update route persists all changes. JSON columns used for repeatable content (galleries, steps) instead of separate child tables for simplicity.

## Phases

| Phase | Name | Status | Effort |
|-------|------|--------|--------|
| 1 | [Database & Setup](./phase-01-database-setup.md) | Pending | 2h |
| 2 | [Factory Admin Panel](./phase-02-factory-admin-panel.md) | Pending | 6h |
| 3 | [Contact & FAQ Admin Panels](./phase-03-contact-faq-admin-panels.md) | Pending | 4h |
| 4 | [Client Binding & Testing](./phase-04-client-binding-testing.md) | Pending | 4h |

## Dependencies

- Phase 1 must complete before Phase 2, 3, 4
- Phase 2 and Phase 3 can run in parallel
- Phase 4 depends on Phase 2 and Phase 3
