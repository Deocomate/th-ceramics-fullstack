---
title: "Rewrite Entire Seeder System - Real Data"
description: "Loại bỏ dữ liệu lorem ipsum/placeholder, thay bằng dữ liệu 100% thực tế chuẩn SEO, ảnh thật từ public/assets/images/"
status: completed
priority: P1
branch: "main"
tags: [seeders, data-quality, seo, content, images]
blockedBy: []
blocks: []
created: "2026-05-10T06:31:09.378Z"
createdBy: "ck:plan"
source: skill
---

# Rewrite Entire Seeder System - Real Data

## Overview

Viết lại toàn bộ 6 file seeder: thay dữ liệu lorem ipsum/placeholder bằng dữ liệu thực tế, chuẩn SEO, văn phong thương hiệu cao cấp. Ảnh chỉ là string path (`assets/images/filename.ext`) — không copy file, seeder idempotent, chạy nhanh <1s.

## Key Design Decisions

- **Image path strategy:** Lưu string `assets/images/filename.ext` vào DB. Frontend tự nối `asset('storage/' . $path)`. Không dùng `File::copy()`, `Storage::put()` trong seeder.
- **Idempotent:** Dùng `firstOrCreate` / `updateOrCreate` thay vì `truncate` + insert. Trừ Phase 4 (ProductDetailSeeder) cần `truncate` vì FK cascade.
- **Eloquent over raw SQL:** Tất cả seeder dùng Eloquent models. Hiện tại `HomeAndAboutUsSeeder` đang dùng `DB::unprepared()` — sẽ chuyển hết.
- **SKU rules:** NAD-XXX (Ngói Âm Dương), GTG-XXX (Gạch Thông Gió), GTT-XXX (Gạch Trang Trí), GCB-XXX (Gạch Cổ Bát Tràng), NLP-XXX (Ngói Hài), LVP-XXX (Linh Vật), BNC-XXX (Bộ Nóc Chữ Văn), NBN-XXX (Ngói Bộ Nóc), DGS-XXX (Đèn Gốm Sứ).
- **Content:** 100% Tiếng Việt có dấu. Keywords: đất sét Bát Tràng, nung 1200°C, men hỏa biến, nghệ nhân, di sản, trường tồn, chống rêu mốc.

## Phases

| Phase | Name | Status | Priority | Effort |
|-------|------|--------|----------|--------|
| 1 | [System & Configuration](./phase-01-system-configuration.md) | Completed | P1 | 1h |
| 2 | [Brand & Home](./phase-02-brand-home.md) | Completed | P1 | 2h |
| 3 | [Category Parents](./phase-03-category-parents.md) | Completed | P1 | 1.5h |
| 4 | [Product Details & Variants](./phase-04-product-details-variants.md) | Completed | P1 | 4h |

## Dependencies

- Phase 1 phải chạy trước (tạo user, page configs, FAQ, định mức)
- Phase 2, 3, 4 có thể chạy song song sau Phase 1
- Phase 4 cần Phase 3 hoàn thành trước (FK references)
- `DatabaseSeeder.php` gọi theo thứ tự: 1 → 2 → 3 → 4
