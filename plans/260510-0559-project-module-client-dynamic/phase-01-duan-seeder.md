---
phase: 1
title: "DuAn Seeder"
status: completed
priority: P1
effort: "1.5h"
dependencies: []
---

# Phase 1: DuAn Seeder

## Overview

Tạo `DuAnSeeder` sinh 5 danh mục dự án + 20 dự án thực tế (TH Ceramics), đăng ký vào `DatabaseSeeder`.
Admin module hiện đã hoàn chỉnh — chỉ verify lại edit view hiển thị ảnh OK.

## Requirements

- Functional:
  - Seed 5 danh mục: *Ngói Âm Dương, Ngói Hài - Văn Miếu, Gạch Thông Gió, Gạch Trang Trí, Sản phẩm khác*
  - Seed 20 dự án phân bổ đều vào 5 danh mục
  - Mỗi dự án có `images` là JSON array chứa 3-5 path ảnh dạng `assets/images/filename.jpg`
  - Slug unique, auto-generate từ tên dự án
  - Dữ liệu thực tế: Chùa Bái Đính, Thiền Viện Trúc Lâm, Đền Trần, Nhà thờ Đá Phát Diệm...
- Non-functional: Chạy `php artisan db:seed` không lỗi, dữ liệu render được trên view client

## Architecture

```
DuAnSeeder
├── define categories (array) → DanhMucDuAn::create()
├── define 20 projects (array) → DuAn::create()
│   ├── ten_du_an, dia_diem, san_pham, nam
│   ├── danh_muc_du_an_id (FK)
│   ├── slug = Str::slug(ten_du_an)
│   └── images = ["assets/images/file1.jpg", "assets/images/file2.jpg", ...]
└── registered in DatabaseSeeder::run()
```

**Image path convention:** `assets/images/filename.jpg` → view dùng `asset('storage/' . $path)` sẽ resolve qua `public/storage` symlink. Files gốc nằm ở `public/assets/images/`, cần được copy/symlink vào `storage/app/public/assets/images/` để storage disk serve được. 
*Lưu ý: Trong hàm `run()` của `DuAnSeeder`, hãy sử dụng `\Illuminate\Support\Facades\File::copyDirectory(public_path('assets/images'), storage_path('app/public/assets/images'))` để đảm bảo các file ảnh tĩnh có sẵn trong storage disk trước khi seed data.*

## Related Code Files

- Create: `database/seeders/DuAnSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php` (thêm `DuAnSeeder::class` vào call)
- Read for context: `app/Models/DuAn.php`, `app/Models/DanhMucDuAn.php`, `resources/views/admin/du-an/edit.blade.php`

## Implementation Steps

1. **Create `DuAnSeeder.php`** — dùng `php artisan make:seeder DuAnSeeder`
2. **Copy ảnh vào storage** — đầu hàm `run()`, gọi `\Illuminate\Support\Facades\File::copyDirectory(public_path('assets/images'), storage_path('app/public/assets/images'));`
3. **Seed danh mục** — 5 categories, kiểm tra `firstOrCreate` để tránh duplicate:
   ```
   Ngói Âm Dương | Ngói Hài - Văn Miếu | Gạch Thông Gió | Gạch Trang Trí | Sản phẩm khác
   ```
3. **Seed 20 dự án** — mỗi dự án map vào 1 danh mục:
   - Ngói Âm Dương: Chùa Bái Đính (Ninh Bình), Chùa Tam Chúc (Hà Nam), Đền Trần (Nam Định), Nhà thờ Đá Phát Diệm (Ninh Bình)
   - Ngói Hài - VM: Thiền Viện Trúc Lâm (Đà Lạt), Chùa Bà Đanh (Hà Nam), Chùa Một Cột (Hà Nội), Đình Bảng (Bắc Ninh)
   - Gạch Thông Gió: Chung cư EcoLife (Hà Nội), Biệt thự Vinhomes Riverside (Hà Nội), Resort Furama (Đà Nẵng), Khách sạn Mường Thanh (Nghệ An)
   - Gạch Trang Trí: Nhà hàng ẩm thực Huế (Huế), Quán cà phê The Workshop (TP.HCM), Khu nghỉ dưỡng Flamingo (Vĩnh Phúc), Nhà cổ Bình Thủy (Cần Thơ)
   - Sản phẩm khác: Lăng Chủ tịch Hồ Chí Minh (Hà Nội), Chùa Cầu (Hội An), Tháp Po Nagar (Nha Trang), Chùa Tây Phương (Hà Nội)

4. **Generate images array** — mỗi dự án 3-5 ảnh, lấy từ danh sách file có sẵn:
   - `trang-tri-slide-01.jpg`, `gach-co-work-1.jpg`, `factory-01.jpg`, `factory-03.png`, `factory-04.jpg`, `gallery-01.jpg` đến `gallery-06.jpg`
   
5. **Generate slug** — `Str::slug($ten_du_an)`, đảm bảo unique bằng `firstOrCreate` với slug check

6. **Register in DatabaseSeeder** — thêm `DuAnSeeder::class` vào `$this->call([])`

7. **Verify admin edit view** — đọc `resources/views/admin/du-an/edit.blade.php`, confirm đang dùng `asset('storage/' . $path)` cho existing images (đã OK)

## Success Criteria

- [x] `php artisan db:seed --class=DuAnSeeder` chạy không lỗi
- [x] 5 danh mục được tạo trong bảng `danh_muc_du_an`
- [x] 20 dự án được tạo trong bảng `du_an`
- [x] Mỗi dự án có slug unique, `images` là JSON array 3-5 phần tử
- [x] DatabaseSeeder gọi được DuAnSeeder
- [x] Admin edit view hiển thị đúng ảnh từ mảng images

## Risk Assessment

- **Duplicate slug:** Dùng `firstOrCreate` với slug check, append counter nếu trùng
- **Ảnh không render được:** Files gốc ở `public/assets/images/`, cần đảm bảo `storage/app/public/assets/images/` tồn tại hoặc symlink đúng. Nếu không, ảnh sẽ hiển thị broken image.
- **FK constraint:** Đảm bảo seed danh mục trước dự án, dùng `DanhMucDuAn::firstOrCreate` để lấy ID
