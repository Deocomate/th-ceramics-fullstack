---
phase: 1
title: "System & Configuration"
status: completed
priority: P1
effort: "1h"
dependencies: []
---

# Phase 1: System & Configuration

## Overview

Chuẩn hóa `UserSeeder`, `DinhMucSeeder`, `PageConfigSeeder`. UserSeeder giữ nguyên. DinhMucSeeder verify lại số liệu. PageConfigSeeder thay ảnh thật thay vì placeholder.

## Requirements

- Functional:
  - UserSeeder: tạo superadmin `admin@gmail.com` / `Admin@123` (giữ nguyên)
  - DinhMucSeeder: verify số liệu khớp với `docs/cong-thuc-tinh-va-dinh-muc/`
  - PageConfigSeeder: thay toàn bộ ảnh placeholder bằng ảnh thật từ `assets/images/`
  - FAQ: verify 15 câu hỏi, văn phong chuẩn thương hiệu
- Non-functional: Dùng Eloquent `firstOrCreate`, idempotent, không truncate

## Architecture

```
DatabaseSeeder::run()
  ├── UserSeeder          → users (1 row, superadmin)
  ├── DinhMucSeeder       → 6 dinh_muc tables
  └── PageConfigSeeder    → page_factory, page_contact, page_faq, faqs
```

## Related Code Files

- Modify: `database/seeders/UserSeeder.php` (verify, minor)
- Modify: `database/seeders/DinhMucSeeder.php` (verify số liệu)
- Modify: `database/seeders/PageConfigSeeder.php` (thay ảnh + content)
- Modify: `database/seeders/DatabaseSeeder.php` (đảm bảo order đúng)
- Read: `docs/cong-thuc-tinh-va-dinh-muc/` (reference cho DinhMuc)
- Read: `app/Models/PageFactory.php`, `PageContact.php`, `PageFaq.php`, `Faq.php`

## Implementation Steps

### Step 1: Verify UserSeeder (5 min)

File hiện tại đã chuẩn — dùng `firstOrCreate`, tạo superadmin. Chỉ verify lại, không cần sửa.

### Step 2: Verify & Enhance DinhMucSeeder (20 min)

Đối chiếu với `docs/cong-thuc-tinh-va-dinh-muc/`:

- `dinh_muc_ngoi_am_duong` (8 rows): Số liệu hiện tại khớp CSV. Giữ nguyên.
- `dinh_muc_ngoi_hai_co` (1 row): Mai gỗ=125, Mai bê tông=75. Khớp CSV. OK.
- `dinh_muc_ngoi_hai_van_mieu` (1 row): Mai gỗ=125, Mai bê tông=88. Khớp CSV. OK.
- `dinh_muc_gach_trang_tri` (3 rows): Thay thành `firstOrCreate` pattern, thêm nhiều size hơn:
  ```php
  $sizes = [
      ['10x10 cm', 100],
      ['10x20 cm', 50],
      ['15x15 cm', 44],
      ['20x20 cm', 25],
      ['30x30 cm', 11],
  ];
  ```
- `dinh_muc_gach_hoa_thong_gio` (3 rows): Thêm size:
  ```php
  $sizes = [
      ['15x15x6 cm', 44],
      ['20x20x6 cm', 25],
      ['20x30x10 cm', 16],
      ['30x30x10 cm', 11],
  ];
  ```
- `dinh_muc_gach_co_bat_trang` (3 rows): Thêm size:
  ```php
  $sizes = [
      ['5x20x2.5 cm (ốp tường)', 100],
      ['10x20x5 cm (xây tường)', 50],
      ['20x20 cm (lát nền)', 25],
      ['10x10x2 cm (ốp vách)', 100],
  ];
  ```

### Step 3: Rewrite PageConfigSeeder (30 min)

**`page_factory`:**
```php
PageFactory::firstOrCreate(
    ['page_factory_id' => 1],
    [
        'hero_banner'       => 'assets/images/factory-01.jpg',
        'hero_banner_mobile' => 'assets/images/factory-01.jpg',
        'intro_banner'      => 'assets/images/factory-banner.png',
        'intro_text'        => 'Hành trình kiến tạo không gian Việt...',
        'gallery_1'         => [
            ['image' => 'assets/images/factory-02.png', 'alt' => 'Xưởng sản xuất TH Ceramics'],
            ['image' => 'assets/images/factory-03.png', 'alt' => 'Dây chuyền nung gốm'],
            ['image' => 'assets/images/factory-04.jpg', 'alt' => 'Khu trưng bày sản phẩm'],
        ],
        // ... giữ các field còn lại
    ]
);
```

**`page_contact`:**
```php
PageContact::firstOrCreate(
    ['page_contact_id' => 1],
    [
        'map_image'  => 'assets/images/contact-map.png',
        'hotline'    => '0966 55 8808',
        'zalo_link'  => 'https://zalo.me/0966558808',
        'form_title' => 'Gửi yêu cầu tư vấn',
    ]
);
```

**`page_faq`:**
```php
PageFaq::firstOrCreate(
    ['page_faq_id' => 1],
    ['banner_image' => 'assets/images/faq-banner.png']
);
```

**`faqs` (15 câu hỏi):** Giữ nguyên cấu trúc hiện tại (3 categories: sản phẩm, báo giá, vận chuyển). Chỉ verify văn phong, sửa lỗi chính tả nếu có. Thêm `firstOrCreate` pattern với composite key `[question, category]`.

## Success Criteria

- [ ] `php artisan db:seed --class=UserSeeder` chạy không lỗi, tạo superadmin
- [ ] `php artisan db:seed --class=DinhMucSeeder` tạo đủ 6 bảng định mức, số liệu khớp CSV docs
- [ ] `php artisan db:seed --class=PageConfigSeeder` dùng ảnh thật, không còn placeholder
- [ ] Chạy lại seeder nhiều lần không sinh duplicate (idempotent)
- [ ] FAQ có 15 câu, văn phong tiếng Việt chuẩn, không lỗi chính tả

## Risk Assessment

- **DinhMucSeeder đang dùng truncate:** Chuyển sang `firstOrCreate` để idempotent. Nếu cần giữ truncate, phải disable FK checks.
- **PageConfigSeeder hiện dùng Eloquent:** Giữ pattern này, chỉ thay data. Ít rủi ro.
