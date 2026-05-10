---
phase: 3
title: "Category Parents"
status: completed
priority: P1
effort: "1.5h"
dependencies: []
---

# Phase 3: Category Parents

## Overview

Viết lại `ProductTypeSeeder`. Thay toàn bộ `defaults/placeholder.png` trong 9 bảng danh mục cha bằng ảnh thật từ `assets/images/`. Chuyển từ `DB::table()->insertOrIgnore()` sang Eloquent với `firstOrCreate`.

## Requirements

- Functional:
  - 9 bảng danh mục cha: mỗi bảng 1 row cấu hình banner + ảnh giới thiệu
  - Ảnh map đúng: `ngoi-am-duong-banner.jpg`, `ngoi-hai-van-mieu-banner.jpg`, `gach-hoa-01.jpg`, etc.
  - Content: không Lorem ipsum, tiêu đề chuẩn tiếng Việt
- Non-functional: Eloquent `firstOrCreate`, idempotent, giữ nguyên tên cột

## Architecture

```
ProductTypeSeeder::run()
  ├── seedNgoiAmDuong()       → ngoi_am_duong
  ├── seedNgoiHaiVanMieu()   → ngoi_hai_van_mieu
  ├── seedGachHoaThongGio()  → gach_hoa_thong_gio
  ├── seedPhuKienNgoi()      → phu_kien_ngoi
  ├── seedGachTrangTri()     → gach_trang_tri
  ├── seedLanCanGomXu()      → lan_can_gom_xu
  ├── seedGachCoBatTrang()   → gach_co_bat_trang
  ├── seedLinhVatPhongThuy() → linh_vat_phong_thuy
  └── seedDenGomSu()         → den_gom_su
```

## Related Code Files

- Rewrite: `database/seeders/ProductTypeSeeder.php`
- Read: 9 parent models (`NgoiAmDuong.php`, `NgoiHaiVanMieu.php`, `GachHoaThongGio.php`, etc.)

## Implementation Steps

### Step 1: Ngoi Am Duong

```php
private function seedNgoiAmDuong(): void
{
    NgoiAmDuong::firstOrCreate(
        ['ngoi_am_duong_id' => 1],
        [
            'thumbnail_main'  => 'assets/images/ngoi-am-duong-banner.jpg',
            'thumbnail1'      => 'assets/images/ngoi-am-duong-01.jpg',
            'thumbnail2'      => 'assets/images/ngoi-am-duong-02.png',
            'video'           => 'assets/images/video-placeholder.jpg',
        ]
    );
}
```

### Step 2: Ngoi Hai Van Mieu

```php
private function seedNgoiHaiVanMieu(): void
{
    NgoiHaiVanMieu::firstOrCreate(
        ['ngoi_hai_van_mieu_id' => 1],
        [
            'thumbnail_main' => 'assets/images/ngoi-hai-van-mieu-banner.jpg',
            'title1'         => 'Tinh hoa kiến trúc Việt',
            'thumbnail1'     => 'assets/images/ngoi-hai-01.png',
            'title2'         => 'Ngói Hài - Nét cong di sản',
            'thumbnail2'     => 'assets/images/ngoi-hai-02.png',
            'title3'         => 'Ngói Văn Miếu - Uy nghi cổ kính',
            'thumbnail3'     => 'assets/images/ngoi-hai-03.png',
            'video'          => 'assets/images/video-placeholder.jpg',
            'images'         => [
                'assets/images/ngoi-hai-01.png',
                'assets/images/ngoi-hai-02.png',
                'assets/images/ngoi-hai-03.png',
            ],
        ]
    );
}
```

### Step 3: Gach Hoa Thong Gio

```php
private function seedGachHoaThongGio(): void
{
    $parent = GachHoaThongGio::firstOrCreate(
        ['gach_hoa_thong_gio_id' => 1],
        [
            'image' => 'assets/images/gach-hoa-01.jpg',
            'video' => 'assets/images/video-placeholder.jpg',
        ]
    );

    // Sub-resources
    $anhs = [
        ['image' => 'assets/images/gach-hoa-thong-gio-anh-01.jpg'],
        ['image' => 'assets/images/gach-hoa-thong-gio-anh-02.jpg'],
    ];
    foreach ($anhs as $anh) {
        GachHoaThongGioAnh::firstOrCreate(
            ['image' => $anh['image']],
            ['gach_hoa_thong_gio_id' => $parent->gach_hoa_thong_gio_id]
        );
    }

    $giaTris = [
        ['title' => 'Thông gió tự nhiên', 'image' => 'assets/images/gia-tri-01.png'],
        ['title' => 'Chống nóng hiệu quả', 'image' => 'assets/images/gia-tri-02.png'],
    ];
    foreach ($giaTris as $gt) {
        GiaTriGachHoaThongGio::firstOrCreate(
            ['title' => $gt['title']],
            ['image' => $gt['image'], 'gach_hoa_thong_gio_id' => $parent->gach_hoa_thong_gio_id]
        );
    }
}
```

### Step 4: Phu Kien Ngoi

```php
private function seedPhuKienNgoi(): void
{
    PhuKienNgoi::firstOrCreate(
        ['phu_kien_ngoi_id' => 1],
        [
            'thumbnail_main' => 'assets/images/pk-banner.png',
            'images'         => [],
        ]
    );
}
```

### Step 5: Gach Trang Tri

```php
private function seedGachTrangTri(): void
{
    $parent = GachTrangTri::firstOrCreate(
        ['gach_trang_tri_id' => 1],
        [
            'thumbnail_main' => 'assets/images/gach-trang-tri-banner.png',
            'video'          => 'assets/images/video-placeholder.jpg',
        ]
    );

    // Sub-resources
    $dauAns = [
        ['title' => 'Đậm nét Á Đông', 'image' => 'assets/images/dau-an-01.png'],
        ['title' => 'Bền bỉ với thời gian', 'image' => 'assets/images/dau-an-02.png'],
    ];
    foreach ($dauAns as $da) {
        DauAnGachTrangTri::firstOrCreate(
            ['title' => $da['title']],
            ['image' => $da['image'], 'gach_trang_tri_id' => $parent->gach_trang_tri_id]
        );
    }
}
```

### Step 6: Lan Can Gom Xu

```php
private function seedLanCanGomXu(): void
{
    LanCanGomXu::firstOrCreate(
        ['lan_can_gom_xu_id' => 1],
        [
            'thumbnail_main' => 'assets/images/lan-can-01.jpg',
            'video'          => 'assets/images/video-placeholder.jpg',
        ]
    );
}
```

### Step 7: Gach Co Bat Trang

```php
private function seedGachCoBatTrang(): void
{
    $parent = GachCoBatTrang::firstOrCreate(
        ['gach_co_bat_trang_id' => 1],
        [
            'thumbnail_main' => 'assets/images/gach-co-banner.png',
            'video'          => 'assets/images/video-placeholder.jpg',
        ]
    );

    // Sub-resources
    $anhs = [
        ['image' => 'assets/images/gach-co-anh-01.jpg'],
        ['image' => 'assets/images/gach-co-anh-02.jpg'],
    ];
    foreach ($anhs as $anh) {
        GachCoBatTrangAnh::firstOrCreate(
            ['image' => $anh['image']],
            ['gach_co_bat_trang_id' => $parent->gach_co_bat_trang_id]
        );
    }
}
```

### Step 8: Linh Vat Phong Thuy

```php
private function seedLinhVatPhongThuy(): void
{
    $parent = LinhVatPhongThuy::firstOrCreate(
        ['linh_vat_phong_thuy_id' => 1],
        [
            'thumbnail_main' => 'assets/images/linh-vat-banner.png',
            'video'          => 'assets/images/video-placeholder.jpg',
        ]
    );

    // Sub-resources
    $linhVats = [
        ['title' => 'Long', 'image' => 'assets/images/linh-vat-long.png'],
        ['title' => 'Lân', 'image' => 'assets/images/linh-vat-lan.png'],
    ];
    foreach ($linhVats as $lv) {
        LinhVat::firstOrCreate(
            ['title' => $lv['title']],
            ['image' => $lv['image'], 'linh_vat_phong_thuy_id' => $parent->linh_vat_phong_thuy_id]
        );
    }

    $anhs = [
        ['image' => 'assets/images/linh-vat-anh-01.jpg'],
        ['image' => 'assets/images/linh-vat-anh-02.jpg'],
    ];
    foreach ($anhs as $anh) {
        LinhVatPhongThuyAnh::firstOrCreate(
            ['image' => $anh['image']],
            ['linh_vat_phong_thuy_id' => $parent->linh_vat_phong_thuy_id]
        );
    }
}
```

### Step 9: Den Gom Su

```php
private function seedDenGomSu(): void
{
    $parent = DenGomSu::firstOrCreate(
        ['den_gom_su_id' => 1],
        [
            'thumbnail_main' => 'assets/images/den-gom-banner.png',
            'video'          => 'assets/images/video-placeholder.jpg',
            'title2'         => 'Đèn gốm sứ cao cấp',
            'title3'         => 'Sản phẩm tiêu biểu',
            'image1'         => 'assets/images/den-gom-01.png',
            'image2'         => 'assets/images/den-gom-02.png',
            'image3'         => 'assets/images/den-gom-01.png',
            'image4'         => 'assets/images/den-gom-02.png',
        ]
    );

    // Sub-resources
    $anhs = [
        ['image' => 'assets/images/den-gom-anh-01.jpg'],
        ['image' => 'assets/images/den-gom-anh-02.jpg'],
    ];
    foreach ($anhs as $anh) {
        DenGomSuAnh::firstOrCreate(
            ['image' => $anh['image']],
            ['den_gom_su_id' => $parent->den_gom_su_id]
        );
    }
}
```

## Success Criteria

- [ ] `php artisan db:seed --class=ProductTypeSeeder` chạy không lỗi
- [ ] 9 bảng danh mục cha, mỗi bảng 1 row
- [ ] Không còn `defaults/placeholder.png` ở bất kỳ đâu
- [ ] Tất cả ảnh dùng files thực tế từ `assets/images/`
- [ ] Chạy lại seeder không duplicate (idempotent)
- [ ] Eloquent thay vì `DB::table()->insertOrIgnore()`

## Risk Assessment

- **Eloquent Relations:** Phải đảm bảo các models con (như `GachHoaThongGioAnh`, `DauAnGachTrangTri`, v.v.) có quan hệ và migrations đúng. Order seed cha trước con.
- **Double JSON Encoding:** Cẩn thận không dùng `json_encode` ở các mảng JSON do `$casts` đã tự handle.
