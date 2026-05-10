---
phase: 2
title: "Brand & Home"
status: completed
priority: P1
effort: "2h"
dependencies: []
---

# Phase 2: Brand & Home

## Overview

Viết lại `HomeAndAboutUsSeeder` từ raw SQL (`DB::unprepared()`) sang Eloquent, thay toàn bộ ảnh placeholder và content lorem ipsum bằng dữ liệu thực tế, văn phong thương hiệu.

## Requirements

- Functional:
  - Chuyển `HomeAndAboutUsSeeder` từ raw SQL → Eloquent models
  - `trang_chu`: Banner `home-hero-01.png`, partners `partner-01~05.png`, showroom `showroom-01~03.jpg`
  - `ve_chung_toi`: Banner `about-banner.jpg`, nội dung kể chuyện thương hiệu, ảnh `about-01.png`, `about-02.jpg`
  - `giai_thuong_thanh_tuu`: 5 awards dùng `award-01~05.jpg`
  - `gia_tri_vuot_troi`: 4 giá trị dùng `gia-tri-vuot-troi-01~04.jpg` (bảng này hiện chưa được seed!)
- Non-functional: Idempotent (`firstOrCreate`), dùng Eloquent, JSON_UNESCAPED_UNICODE

## Architecture

```
HomeAndAboutUsSeeder::run()
  ├── seedTrangChu()           → trang_chu table (1 row)
  ├── seedVeChungToi()         → ve_chung_toi table (1 row)
  ├── seedGiaiThuongThanhTuu() → giai_thuong_thanh_tuu (5 rows)
  └── seedGiaTriVuotTroi()     → gia_tri_vuot_troi (4 rows) [NEW]
```

## Related Code Files

- Rewrite: `database/seeders/HomeAndAboutUsSeeder.php`
- Read: `app/Models/TrangChu.php`, `VeChungToi.php`, `GiaiThuongThanhTuu.php`, `GiaTriVuotTroi.php`

## Implementation Steps

### Step 1: Rewrite TrangChu Seeding (45 min)

```php
private function seedTrangChu(): void
{
    TrangChu::firstOrCreate(
        ['trang_chu_id' => 1],
        [
            'banner' => [
                ['image' => 'assets/images/home-hero-01.png', 'alt' => 'TH Ceramics - Gốm Việt Trường Tồn'],
            ],
            'khach_hang_doi_tac' => [
                ['image' => 'assets/images/partner-01.png', 'alt' => 'Đối tác 1'],
                ['image' => 'assets/images/partner-02.png', 'alt' => 'Đối tác 2'],
                ['image' => 'assets/images/partner-03.png', 'alt' => 'Đối tác 3'],
                ['image' => 'assets/images/partner-04.png', 'alt' => 'Đối tác 4'],
                ['image' => 'assets/images/partner-05.png', 'alt' => 'Đối tác 5'],
            ],
            'loi_tri_an' => [
                'title' => 'Lời tri ân',
                'content' => 'TH Ceramics trân trọng gửi lời cảm ơn sâu sắc đến Quý khách hàng, đối tác đã đồng hành cùng chúng tôi...',
            ],
            've_chung_toi_logo' => [
                ['image' => 'assets/images/logo.png', 'alt' => 'TH Ceramics Logo'],
            ],
            'video' => 'assets/images/video-placeholder.jpg',
            'nhung_con_so' => [
                ['number' => '40+', 'label' => 'Năm kinh nghiệm'],
                ['number' => '500+', 'label' => 'Dự án hoàn thành'],
                ['number' => '200+', 'label' => 'Mẫu sản phẩm'],
                ['number' => '63', 'label' => 'Tỉnh thành hiện diện'],
            ],
            'showroom_images' => [
                ['image' => 'assets/images/showroom-01.jpg', 'des' => 'Showroom Hà Nội - 286 Nguyễn Xiển'],
                ['image' => 'assets/images/showroom-02.jpg', 'des' => 'Không gian trưng bày sản phẩm ngói'],
                ['image' => 'assets/images/showroom-03.png', 'des' => 'Gạch trang trí cao cấp'],
            ],
        ]
    );
}
```

### Step 2: Rewrite VeChungToi Seeding (30 min)

```php
private function seedVeChungToi(): void
{
    VeChungToi::firstOrCreate(
        ['ve_chung_toi_id' => 1],
        [
            'banner' => 'assets/images/about-banner.jpg',
            'gs_head' => [
                'title' => 'TH Ceramics - Tinh hoa gốm Việt',
                'subtitle' => 'Hành trình hơn 40 năm gìn giữ và phát triển nghệ thuật gốm truyền thống',
            ],
            'gs_gia_tri' => [
                ['title' => 'Tầm nhìn', 'content' => 'Trở thành thương hiệu gốm sứ hàng đầu Việt Nam...'],
                ['title' => 'Sứ mệnh', 'content' => 'Mang vẻ đẹp gốm Việt đến mọi công trình...'],
                ['title' => 'Giá trị cốt lõi', 'content' => 'Chất lượng - Sáng tạo - Bền vững - Tận tâm'],
            ],
            'gs_hanh_trinh' => [
                ['year' => '1985', 'event' => 'Khởi nguồn từ lò gốm gia đình Vũ Gia tại Bát Tràng'],
                ['year' => '1995', 'event' => 'Xây dựng xưởng sản xuất quy mô công nghiệp đầu tiên'],
                ['year' => '2005', 'event' => 'Ra mắt thương hiệu TH Ceramics, mở rộng thị trường toàn quốc'],
                ['year' => '2015', 'event' => 'Đầu tư công nghệ nung 1200°C, đạt chứng nhận chất lượng ISO'],
                ['year' => '2024', 'event' => 'Hơn 500 dự án lớn nhỏ trên khắp 63 tỉnh thành'],
            ],
            'gs_giai_thuong' => 'assets/images/award-01.jpg',
            // ... giữ các field còn lại như nt_ngon_ngu, nt_che_tac_anh, v.v.
        ]
    );
}
```

### Step 3: Rewrite GiaiThuongThanhTuu (15 min)

```php
private function seedGiaiThuongThanhTuu(): void
{
    $awards = [
        ['image' => 'assets/images/award-01.jpg', 'des' => 'Nghệ nhân Hà Nội 2024 - Ông Vũ Mạnh Hải'],
        ['image' => 'assets/images/award-02.jpg', 'des' => 'Sản phẩm công nghiệp nông thôn tiêu biểu 2023'],
        ['image' => 'assets/images/award-03.jpg', 'des' => 'Top 10 thương hiệu gốm sứ uy tín 2024'],
        ['image' => 'assets/images/award-05.jpg', 'des' => 'Giải thưởng kiến trúc xanh - Vật liệu bền vững 2024'],
    ];

    foreach ($awards as $award) {
        GiaiThuongThanhTuu::firstOrCreate(
            ['des' => $award['des']],
            ['image' => $award['image']]
        );
    }
}
```

### Step 4: Seed GiaTriVuotTroi (NEW — 20 min)

Bảng này có trong migration nhưng chưa được seed:

```php
private function seedGiaTriVuotTroi(): void
{
    $values = [
        [
            'title' => 'Đất sét Bát Tràng nguyên chất',
            'desscription' => 'Sử dụng 100% đất sét trắng Bát Tràng, khai thác tại địa phương, đảm bảo độ dẻo và độ kết dính tự nhiên.',
            'image' => 'assets/images/gia-tri-vuot-troi-01.jpg',
        ],
        [
            'title' => 'Nung ở 1200°C - Độ cứng vượt trội',
            'desscription' => 'Công nghệ lò nung tuynel đạt 1200°C giúp sản phẩm đạt độ cứng tối ưu, chống thấm nước, chống rêu mốc vĩnh viễn.',
            'image' => 'assets/images/gia-tri-vuot-troi-02.jpg',
        ],
        [
            'title' => 'Men hỏa biến độc quyền',
            'desscription' => 'Kỹ thuật tráng men thủ công kết hợp biến nhiệt tạo hiệu ứng hoả biến độc nhất, mỗi viên ngói là một tác phẩm nghệ thuật.',
            'image' => 'assets/images/gia-tri-vuot-troi-03.jpg',
        ],
        [
            'title' => 'Bảo hành 20 năm - Trường tồn cùng thời gian',
            'desscription' => 'Cam kết bảo hành chống phai màu, chống thấm nước 20 năm. Sản phẩm đã kiểm chứng qua hơn 500 công trình trên toàn quốc.',
            'image' => 'assets/images/gia-tri-vuot-troi-04.jpg',
        ],
    ];

    foreach ($values as $v) {
        GiaTriVuotTroi::firstOrCreate(
            ['title' => $v['title']],
            ['desscription' => $v['desscription'], 'image' => $v['image']]
        );
    }
}
```

## Success Criteria

- [ ] `php artisan db:seed --class=HomeAndAboutUsSeeder` chạy không lỗi
- [ ] `trang_chu`: 1 row, tất cả ảnh map đúng `assets/images/`
- [ ] `ve_chung_toi`: 1 row, nội dung tiếng Việt có dấu
- [ ] `giai_thuong_thanh_tuu`: 4-5 rows, ảnh thật
- [ ] `gia_tri_vuot_troi`: 4 rows (bảng mới được seed)
- [ ] Chạy lại nhiều lần không duplicate
- [ ] Không còn raw SQL `DB::unprepared()` — dùng Eloquent

## Risk Assessment

- **Raw SQL migration phức tạp:** File hiện tại dùng `DB::unprepared()` với chuỗi SQL dài. Cần đọc kỹ từng cột để map chính xác sang Eloquent.
- **Double JSON Encoding:** Không dùng `json_encode` vì các field JSON đã có `$casts = ['field' => 'array']` trong Model. Eloquent sẽ tự handle.
- **VeChungToi có nhiều sub-sections:** nt_ngon_ngu, nt_che_tac_anh, nt_luyen_dat_item, nt_dun_lo_anh — giữ nguyên cấu trúc JSON hiện tại, chỉ thay content.
