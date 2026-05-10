---
title: "Dynamic hóa Module Dự án (Projects) - Seeder, Client Index & Detail"
description: "Seeder dữ liệu thực tế + Dynamic hóa trang Danh sách & Chi tiết Dự án cho client"
status: pending
priority: P1
branch: "main"
tags: [projects, client, seeder, gallery, filter, pagination]
blockedBy: []
blocks: []
created: "2026-05-10T06:04:48.715Z"
createdBy: "ck:plan"
source: skill
---

# Dynamic hóa Module Dự án (Projects)

## Overview

3 phase độc lập có thể chạy song song:
1. **DuAn Seeder** — sinh 5 danh mục + 20 dự án mẫu thực tế (Chùa Bái Đính, Đền Trần...)
2. **Client Index** — dynamic hóa `/du-an` với filter mobile+desktop, grid, pagination
3. **Client Detail** — dynamic hóa `/du-an/{slug}` với hero, meta, gallery GLightbox

## Key Design Decisions

**Image path strategy:**
- Admin upload: `FileUploadHelper` lưu vào `du_an/images/` → render `asset('storage/du_an/images/...')`
- Seeder: lưu `assets/images/...` → render `asset('storage/assets/images/...')` qua symlink
- **Blade helper `project_img($path)`** dùng chung cho cả 2 luồng: `asset('storage/' . $path)`

**Filter:** Query string `?category=slug`, tương thích pagination Laravel, SEO-friendly.

**Constraints:** Giữ nguyên 100% class Tailwind, AOS animation, cấu trúc HTML hiện tại.
**Important:** Đảm bảo sử dụng `use Illuminate\Support\Str;` trong Controller hoặc viết đầy đủ namespace `\Illuminate\Support\Str::` trong file Blade để tránh lỗi `Class 'Str' not found`.

## Phases

| Phase | Name | Status | Priority | Effort |
|-------|------|--------|----------|--------|
| 1 | [DuAn Seeder](./phase-01-duan-seeder.md) | Pending | P1 | 1.5h |
| 2 | [Client Index Page](./phase-02-client-index-page.md) | Pending | P1 | 2h |
| 3 | [Client Detail Page](./phase-03-client-detail-page.md) | Pending | P1 | 2h |

## Dependencies

Không có. 3 phase hoàn toàn độc lập, có thể chạy parallel.

Các phase chia sẻ: `DuAn` model, `DanhMucDuAn` model, `routes/client.php` route names.
Không phase nào sửa file của phase khác.
