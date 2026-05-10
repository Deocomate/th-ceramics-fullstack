---
phase: 4
title: "Product Details & Variants"
status: completed
priority: P1
effort: "4h"
dependencies: []
---

# Phase 4: Product Details & Variants

## Overview

Viết lại `ProductDetailSeeder`. Đây là file quan trọng nhất. Dùng `truncate` + `FOREIGN_KEY_CHECKS=0` để dọn data cũ. Seed 100+ sản phẩm với dữ liệu thực tế, ảnh thật, SKU chuẩn, giá hợp lý, mô tả chuẩn SEO.

## Requirements

- Functional:
  - 9 danh mục sản phẩm, mỗi danh mục 5-8 sản phẩm
  - SKU theo quy tắc: NAD-XXX, GTG-XXX, GTT-XXX, etc.
  - Ảnh map đúng ngữ cảnh sản phẩm
  - Giá hợp lý (gạch/ngói: 15k-80k/viên, linh vật/đèn: 500k-3.5M)
  - Mô tả 3-5 bullet points, văn phong thương hiệu
  - `des` và `images` dạng mảng JSON chuẩn
  - Bảng có FK (mau_sac, phan_loai): seed cha trước con
- Non-functional: Dùng Eloquent, pattern private method per category, truncate + FK checks

## Architecture

```
ProductDetailSeeder::run()
  ├── DB::statement('SET FOREIGN_KEY_CHECKS=0')
  ├── Truncate ALL 12 child tables (+ 4 mau_sac/phan_loai tables)
  ├── DB::statement('SET FOREIGN_KEY_CHECKS=1')
  ├── seedNgoiAmDuongCt()       → 8 products
  ├── seedNgoiHaiCoCt()         → 5 products + mau_sac (2-3 each)
  ├── seedNgoiHaiVanMieuCt()    → 5 products + mau_sac (2-3 each)
  ├── seedGachHoaThongGioCt()   → 8 products
  ├── seedGachTrangTriCt()      → 6 products
  ├── seedGachCoBatTrangCt()    → 6 products
  ├── seedLinhVatPhongThuyCt()  → 6 products
  ├── seedNgoiBoNocCt()         → 6 products + phan_loai (2 each)
  └── seedBoNocChuVanCt()       → 6 products + phan_loai (2 each)
```

## Related Code Files

- Rewrite: `database/seeders/ProductDetailSeeder.php`
- Read: All 9 detail models (`NgoiAmDuongCt.php`, `GachHoaThongGioCt.php`, etc.) + 4 child models

## Implementation Steps

### Step 1: Base Structure & Truncate (15 min)

```php
public function run(): void
{
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    // 9 detail tables
    NgoiAmDuongCt::truncate();
    GachHoaThongGioCt::truncate();
    GachTrangTriCt::truncate();
    GachCoBatTrangCt::truncate();
    LinhVatPhongThuyCt::truncate();
    NgoiHaiCoCt::truncate();
    NgoiHaiVanMieuCt::truncate();
    NgoiBoNocCt::truncate();
    BoNocChuVanCt::truncate();

    // 4 child tables
    MauSacNgoiHaiCoCt::truncate();
    MauSacNgoiHaiVanMieuCt::truncate();
    PhanLoaiNgoiBoNocCt::truncate();
    PhanLoaiBoNocChuVanCt::truncate();

    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    $this->seedNgoiAmDuongCt();
    $this->seedNgoiHaiCoCt();
    $this->seedNgoiHaiVanMieuCt();
    $this->seedGachHoaThongGioCt();
    $this->seedGachTrangTriCt();
    $this->seedGachCoBatTrangCt();
    $this->seedLinhVatPhongThuyCt();
    $this->seedNgoiBoNocCt();
    $this->seedBoNocChuVanCt();
}
```

### Step 2: Ngoi Am Duong Ct (20 min)

```php
private function seedNgoiAmDuongCt(): void
{
    $products = [
        [
            'code' => 'NAD-001', 'name' => 'Ngói Âm Dương Tráng Men Lục',
            'images' => ['assets/images/am-duong-detail-01.png', 'assets/images/am-duong-detail-02.png', 'assets/images/am-duong-detail-03.png'],
            'price' => 25000,
            'des' => [
                'Men lục truyền thống lấy cảm hứng từ mái đình, chùa Việt cổ, tạo nên vẻ đẹp uy nghi, trầm mặc.',
                'Đất sét Bát Tràng nguyên chất, nung ở 1200°C trong lò tuynel liên tục 72 giờ.',
                'Khả năng chống thấm tuyệt đối, chống rêu mốc vĩnh viễn nhờ lớp men phủ kín bề mặt.',
                'Phù hợp cho công trình tâm linh, biệt thự sân vườn, nhà hàng tiệc cưới phong cách Indochine.',
            ],
            'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
        ],
        [
            'code' => 'NAD-002', 'name' => 'Ngói Âm Dương Tráng Men Vàng',
            'images' => ['assets/images/am-duong-detail-02.png', 'assets/images/am-duong-detail-01.png'],
            'price' => 28000,
            'des' => [
                'Men vàng hoàng gia - biểu tượng của sự phú quý, thịnh vượng trong kiến trúc Á Đông.',
                'Đất sét Bát Tràng nguyên chất, nung ở 1200°C tạo độ cứng vượt trội.',
            ],
            'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
        ],
        [
            'code' => 'NAD-003', 'name' => 'Ngói Âm Dương Đất Nung Tự Nhiên',
            'images' => ['assets/images/am-duong-detail-03.png', 'assets/images/am-duong-detail.png'],
            'price' => 18000,
            'des' => [
                'Giữ nguyên màu đất nung tự nhiên, phù hợp công trình phong cách Rustic, mộc mạc.',
                'Không tráng men, độ bền đến từ chất lượng đất sét và nhiệt độ nung 1200°C.',
            ],
            'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
        ],
        [
            'code' => 'NAD-004', 'name' => 'Ngói Âm Dương Men Xanh Lục Bảo',
            'images' => ['assets/images/am-duong-detail-01.png', 'assets/images/ngoi-am-duong-01.jpg'],
            'price' => 35000,
            'des' => [
                'Sắc xanh lục bảo đặc trưng của dòng men hỏa biến, mỗi viên ngói là một tác phẩm độc bản.',
                'Đất sét Bát Tràng nguyên chất, nung ở 1200°C.',
            ],
            'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
        ],
        [
            'code' => 'NAD-005', 'name' => 'Ngói Âm Dương Men Đỏ Nâu Cổ Điển',
            'images' => ['assets/images/ngoi-am-duong-02.png', 'assets/images/am-duong-detail-02.png'],
            'price' => 22000,
            'des' => [
                'Tông màu đỏ nâu ấm áp, phù hợp với kiến trúc chùa chiền miền Bắc Việt Nam.',
                'Chất liệu đất sét nung 1200°C, đảm bảo độ bền trên 50 năm.',
            ],
            'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
        ],
    ];

    foreach ($products as $p) {
        NgoiAmDuongCt::create([
            'code' => $p['code'], 'name' => $p['name'],
            'images' => $p['images'], 'price' => $p['price'],
            'des' => $p['des'], 'size' => $p['size'],
            'size_image' => $p['size_image'], 'is_delete' => 0,
        ]);
    }
}
```

### Step 3: Gach Hoa Thong Gio Ct (25 min)

```php
private function seedGachHoaThongGioCt(): void
{
    $products = [
        [
            'code' => 'GTG-001', 'name' => 'Gạch Hoa Thông Gió Họa Tiết Chữ Thọ',
            'images' => ['assets/images/gach-hoa-01.jpg', 'assets/images/gach-hoa-02.jpg', 'assets/images/gach-hoa-03.jpg'],
            'price' => 25000,
            'des' => [
                'Họa tiết Chữ Thọ mang ý nghĩa trường tồn, phước lành — biểu tượng truyền thống trong kiến trúc Việt.',
                'Chất liệu đất sét Bát Tràng nung ở 1200°C đảm bảo độ cứng vượt trội, không nứt vỡ.',
                'Thông gió tự nhiên, lấy sáng hiệu quả — giải pháp xanh cho công trình hiện đại.',
                'Thích hợp xây vách ngăn lấy sáng, mặt tiền nhà phố, biệt thự nghỉ dưỡng.',
            ],
            'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
        ],
        [
            'code' => 'GTG-002', 'name' => 'Gạch Hoa Thông Gió Họa Tiết Đồng Tiền',
            'images' => ['assets/images/gach-hoa-03.jpg', 'assets/images/gach-hoa-04.jpg'],
            'price' => 25000,
            'des' => [
                'Họa tiết Đồng Tiền mang ý nghĩa tài lộc, phú quý, được ưa chuộng trong phong thủy.',
                'Đất sét Bát Tràng nung 1200°C, chống thấm, chống rêu mốc vĩnh viễn.',
            ],
            'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
        ],
        [
            'code' => 'GTG-003', 'name' => 'Gạch Hoa Thông Gió Họa Tiết Hoa Sen',
            'images' => ['assets/images/gach-hoa-05.jpg', 'assets/images/gach-hoa-06.jpg'],
            'price' => 28000,
            'des' => [
                'Hoa Sen — quốc hoa Việt Nam, biểu tượng của sự thanh cao, tinh khiết vượt lên bùn lầy.',
                'Họa tiết tinh xảo được chạm khắc thủ công bởi nghệ nhân Bát Tràng.',
            ],
            'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
        ],
        [
            'code' => 'GTG-004', 'name' => 'Gạch Hoa Thông Gió Họa Tiết Hoa Mai',
            'images' => ['assets/images/gach-hoa-07.jpg', 'assets/images/gach-hoa-08.jpg'],
            'price' => 25000,
            'des' => [
                'Họa tiết Hoa Mai tinh tế — mang sắc xuân vào không gian sống quanh năm.',
                'Đất sét Bát Tràng nung 1200°C, phù hợp khí hậu nhiệt đới ẩm Việt Nam.',
            ],
            'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
        ],
        [
            'code' => 'GTG-005', 'name' => 'Gạch Hoa Thông Gió Mẫu Kỷ Hà',
            'images' => ['assets/images/gach-hoa-01.png', 'assets/images/gach-hoa-02.png'],
            'price' => 30000,
            'des' => [
                'Thiết kế Kỷ Hà độc đáo — lấy cảm hứng từ sóng nước sông Hồng bao quanh làng gốm Bát Tràng.',
                'Men hỏa biến tạo hiệu ứng màu sắc độc nhất cho từng viên gạch.',
            ],
            'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
        ],
    ];

    foreach ($products as $p) {
        GachHoaThongGioCt::create([
            'code' => $p['code'], 'name' => $p['name'],
            'images' => $p['images'], 'price' => $p['price'],
            'des' => $p['des'], 'size' => $p['size'],
            'size_image' => $p['size_image'], 'is_delete' => 0,
        ]);
    }
}
```

### Step 4: Gach Trang Tri Ct (20 min)

```php
private function seedGachTrangTriCt(): void
{
    $products = [
        [
            'code' => 'GTT-001', 'name' => 'Gạch Trang Trí Men Lục Cổ Điển',
            'images' => ['assets/images/trang-tri-01.png', 'assets/images/trang-tri-slide-01.jpg'],
            'price' => 32000,
            'des' => [
                'Men lục cổ điển — màu sắc đặc trưng của gốm Việt truyền thống, đậm chất hoài cổ.',
                'Đất sét Bát Tràng nguyên chất, nung 1200°C — chịu được điều kiện thời tiết khắc nghiệt.',
                'Ứng dụng ốp vách, trang trí mặt tiền, quầy bar, lễ tân khách sạn.',
            ],
            'size' => '10 x 20 cm', 'size_image' => 'assets/images/gach-detail.png',
        ],
        [
            'code' => 'GTT-002', 'name' => 'Gạch Trang Trí Men Vàng Hoàng Gia',
            'images' => ['assets/images/trang-tri-02.png', 'assets/images/trang-tri-slide-02.jpg'],
            'price' => 38000,
            'des' => [
                'Sắc vàng ấm áp, sang trọng — nâng tầm không gian kiến trúc cao cấp.',
                'Men hỏa biến tạo vân màu tự nhiên, mỗi viên gạch là một tác phẩm nghệ thuật.',
            ],
            'size' => '10 x 20 cm', 'size_image' => 'assets/images/gach-detail.png',
        ],
        [
            'code' => 'GTT-003', 'name' => 'Gạch Trang Trí Men Xanh Ngọc',
            'images' => ['assets/images/trang-tri-slide-03.jpg', 'assets/images/trang-tri-slide-04.jpg'],
            'price' => 35000,
            'des' => [
                'Sắc xanh ngọc mát mắt, tạo điểm nhấn cho công trình hiện đại và tân cổ điển.',
                'Chất liệu đất sét Bát Tràng, nung 1200°C — chống thấm, chống phai màu.',
            ],
            'size' => '10 x 20 cm', 'size_image' => 'assets/images/gach-detail.png',
        ],
    ];

    foreach ($products as $p) {
        GachTrangTriCt::create([
            'code' => $p['code'], 'name' => $p['name'],
            'images' => $p['images'], 'price' => $p['price'],
            'des' => $p['des'], 'size' => $p['size'],
            'size_image' => $p['size_image'], 'is_delete' => 0,
        ]);
    }
}
```

### Step 5: Gach Co Bat Trang Ct (20 min)

```php
private function seedGachCoBatTrangCt(): void
{
    $products = [
        [
            'code' => 'GCB-001', 'name' => 'Gạch Cổ Bát Tràng Xây Tường',
            'images' => ['assets/images/gach-bat-01.jpg', 'assets/images/gach-bat-detail-1.png'],
            'price' => 15000,
            'des' => [
                'Gạch cổ tái hiện vẻ đẹp làng nghề Bát Tràng — gam màu đỏ nâu trầm ấm.',
                'Đất sét nguyên chất, nung 1200°C đảm bảo cường độ chịu lực cao.',
                'Phù hợp xây tường rào, tường bao, công trình phong cách Indochine và Rustic.',
            ],
            'size' => '10 x 20 x 5 cm', 'size_image' => 'assets/images/gach-bat-size-1.png',
        ],
        [
            'code' => 'GCB-002', 'name' => 'Gạch Cổ Bát Tràng Ốp Vách',
            'images' => ['assets/images/gach-bat-02.jpg', 'assets/images/gach-bat-detail-2.png'],
            'price' => 18000,
            'des' => [
                'Gạch cổ ốp vách — giải pháp trang trí tường nội ngoại thất đậm chất hoài cổ.',
                'Bề mặt mộc tự nhiên, không tráng men, giữ trọn vẹn hồn đất Bát Tràng.',
            ],
            'size' => '5 x 20 x 2.5 cm', 'size_image' => 'assets/images/gach-bat-size-1.png',
        ],
    ];

    foreach ($products as $p) {
        GachCoBatTrangCt::create([
            'code' => $p['code'], 'name' => $p['name'],
            'images' => $p['images'], 'price' => $p['price'],
            'des' => $p['des'], 'size' => $p['size'],
            'size_image' => $p['size_image'], 'is_delete' => 0,
        ]);
    }
}
```

### Step 6: Linh Vat Phong Thuy Ct (20 min)

```php
private function seedLinhVatPhongThuyCt(): void
{
    $products = [
        [
            'code' => 'LVP-001', 'name' => 'Đầu Rồng Bờ Nóc - Men Xanh Lục',
            'images' => ['assets/images/dau-rong.png'],
            'price' => 1500000,
            'des' => [
                'Linh vật Rồng — biểu tượng quyền lực tối cao, bảo vệ công trình khỏi tà khí.',
                'Đất sét Bát Tràng, chế tác thủ công bởi nghệ nhân 40 năm kinh nghiệm.',
                'Kích thước lớn, phù hợp đình chùa, nhà thờ họ, biệt phủ.',
            ],
            'size' => 'Cao 60cm x Rộng 35cm', 'size_des' => [
                'Sản phẩm đặt làm theo yêu cầu — thời gian chế tác 15-30 ngày.',
                'Có sẵn 3 màu men: xanh lục, vàng, đỏ nâu.',
            ],
            'size_image' => 'assets/images/linh-vat-banner.png',
        ],
        [
            'code' => 'LVP-002', 'name' => 'Tượng Nghê Chầu - Gốm Men Xanh',
            'images' => ['assets/images/nghe.png'],
            'price' => 1200000,
            'des' => [
                'Nghê — linh vật canh giữ cổng đình, chùa, biểu tượng lòng trung thành.',
                'Chế tác thủ công, mỗi chi tiết vân lông đều được khắc tỉ mỉ.',
            ],
            'size' => 'Cao 45cm x Rộng 25cm', 'size_des' => ['Có sẵn men xanh lục, men vàng, men đỏ.'],
            'size_image' => 'assets/images/linh-vat-banner.png',
        ],
        [
            'code' => 'LVP-003', 'name' => 'Tượng Phượng Hoàng - Gốm Men Đỏ',
            'images' => ['assets/images/phuong.png'],
            'price' => 1800000,
            'des' => [
                'Phượng Hoàng — biểu tượng tái sinh, sức sống mãnh liệt, vẻ đẹp cao quý.',
                'Men đỏ hỏa biến tạo hiệu ứng cánh phượng rực rỡ, sống động.',
            ],
            'size' => 'Cao 50cm x Rộng 30cm', 'size_des' => ['Đặt hàng theo yêu cầu kích thước.'],
            'size_image' => 'assets/images/linh-vat-banner.png',
        ],
    ];

    foreach ($products as $p) {
        LinhVatPhongThuyCt::create([
            'code' => $p['code'], 'name' => $p['name'],
            'images' => $p['images'], 'price' => $p['price'],
            'des' => $p['des'], 'size' => $p['size'],
            'size_des' => $p['size_des'] ?? [],
            'size_image' => $p['size_image'] ?? null, 'is_delete' => 0,
        ]);
    }
}
```

### Step 7: Ngoi Hai Co Ct + Mau Sac (25 min)

```php
private function seedNgoiHaiCoCt(): void
{
    $tiles = [
        [
            'name' => 'Ngói Hài Cổ - Men Xanh Lục',
            'images' => ['assets/images/ngoi-hai-01.png', 'assets/images/ngoi-hai-02.png'],
            'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png',
        ],
        [
            'name' => 'Ngói Hài Cổ - Men Vàng',
            'images' => ['assets/images/ngoi-hai-02.png', 'assets/images/ngoi-hai-03.png'],
            'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png',
        ],
        [
            'name' => 'Ngói Hài Cổ - Men Đỏ Nâu',
            'images' => ['assets/images/ngoi-hai-03.png', 'assets/images/ngoi-hai-detail.png'],
            'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png',
        ],
    ];

    $colorVariants = [
        // [code, name, image, price]
        ['NHC-XL', 'Men Xanh Lục', 'assets/images/ngoi-01.jpg', 22000],
        ['NHC-XN', 'Men Xanh Ngọc', 'assets/images/ngoi-02.jpg', 22000],
        ['NHC-VG', 'Men Vàng', 'assets/images/ngoi-03.jpg', 25000],
        ['NHC-DN', 'Men Đỏ Nâu', 'assets/images/ngoi-04.jpg', 20000],
        ['NHC-DT', 'Đất Nung Tự Nhiên', 'assets/images/ngoi-05.jpg', 16000],
        ['NHC-XD', 'Men Xanh Dương', 'assets/images/ngoi-06.jpg', 24000],
        ['NHC-TR', 'Men Trắng Sứ', 'assets/images/ngoi-07.jpg', 26000],
    ];

    foreach ($tiles as $i => $tile) {
        $product = NgoiHaiCoCt::create([
            'name' => $tile['name'], 'images' => $tile['images'],
            'size' => $tile['size'], 'size_image' => $tile['size_image'],
            'is_delete' => 0,
        ]);

        // Each tile gets 2-3 color variants
        $selectedColors = array_slice($colorVariants, $i * 2, 3);
        foreach ($selectedColors as $color) {
            MauSacNgoiHaiCoCt::create([
                'code' => $color[0], 'name' => $color[1],
                'image' => $color[2], 'price' => $color[3],
                'ngoi_hai_co_ct_id' => $product->ngoi_hai_co_ct_id,
                'is_delete' => 0,
            ]);
        }
    }
}
```

### Step 8: Ngoi Bo Noc Ct + Phan Loai (20 min)

```php
private function seedNgoiBoNocCt(): void
{
    $products = [
        [
            'code' => 'NBN-001', 'name' => 'Ngói Bò Nóc - Tráng Men Lục',
            'images' => ['assets/images/bo-noc.png'],
            'price' => 55000,
            'des' => ['Ngói bò nóc tráng men lục — điểm nhấn hoàn hảo cho đường nóc mái.'],
            'size' => 'Tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
            'size_des' => ['Sản phẩm chuyên dụng cho nóc mái ngói âm dương.'],
        ],
        [
            'code' => 'NBN-002', 'name' => 'Ngói Bò Nóc - Men Vàng',
            'images' => ['assets/images/chu-van-1.png'],
            'price' => 58000,
            'des' => ['Ngói bò nóc men vàng — tôn vinh vẻ đẹp quyền quý của công trình.'],
            'size' => 'Tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
            'size_des' => ['Sản phẩm chuyên dụng cho nóc mái ngói âm dương.'],
        ],
    ];

    foreach ($products as $p) {
        $product = NgoiBoNocCt::create([
            'code' => $p['code'], 'name' => $p['name'],
            'images' => $p['images'], 'price' => $p['price'],
            'des' => $p['des'], 'size' => $p['size'],
            'size_image' => $p['size_image'], 'size_des' => $p['size_des'],
            'is_delete' => 0,
        ]);

        PhanLoaiNgoiBoNocCt::create([
            'name' => 'Loại tiêu chuẩn',
            'code' => $p['code'] . '-STD',
            'price' => $p['price'],
            'ngoi_bo_noc_ct_id' => $product->ngoi_bo_noc_ct_id,
            'is_delete' => 0,
        ]);
        PhanLoaiNgoiBoNocCt::create([
            'name' => 'Loại cao cấp (men hỏa biến)',
            'code' => $p['code'] . '-PRE',
            'price' => $p['price'] + 20000,
            'ngoi_bo_noc_ct_id' => $product->ngoi_bo_noc_ct_id,
            'is_delete' => 0,
        ]);
    }
}
```

### Step 9: Bo Noc Chu Van Ct + Phan Loai (15 min)

Tương tự Step 8, map vào model `BoNocChuVanCt` và `PhanLoaiBoNocChuVanCt`.

## Success Criteria

- [ ] `php artisan db:seed --class=ProductDetailSeeder` chạy không lỗi
- [ ] 9 danh mục, ~60+ sản phẩm chi tiết được tạo
- [ ] SKU theo đúng quy tắc (NAD-, GTG-, GTT-, GCB-, LVP-, NHC-, NBN-, BNC-)
- [ ] Tất cả ảnh dùng files thực tế, không placeholder
- [ ] `des`, `size_des`, `images` dạng mảng JSON chuẩn
- [ ] Giá hợp lý (gạch/ngói: 15k-80k, linh vật: 500k-3.5M)
- [ ] FK con (mau_sac, phan_loai) được tạo đúng sau cha
- [ ] Chạy lại seeder → truncate + tạo mới (idempotent qua truncate)
- [ ] Nội dung 100% tiếng Việt có dấu, văn phong thương hiệu

## Risk Assessment

- **FK cascade:** Phải `SET FOREIGN_KEY_CHECKS=0` trước khi truncate. Order truncate không quan trọng nhưng order create phải đúng (cha trước con).
- **Số lượng sản phẩm lớn:** File sẽ dài (400+ lines). Tách mỗi category thành private method để maintainable.
- **NgoiHaiCoCt không có cột `code` và `price`:** Cần đọc model để confirm — code và price nằm ở bảng `mau_sac_*`. Code trên đã xử lý đúng.
- **Ảnh không tồn tại:** Verify các file ảnh được reference đều có trong `public/assets/images/`. Nếu thiếu, fallback về ảnh tương tự.
