<?php

namespace Database\Seeders;

use App\Models\BoNocChuVanCt;
use App\Models\GachCoBatTrangCt;
use App\Models\GachHoaThongGioCt;
use App\Models\GachTrangTriCt;
use App\Models\LinhVatPhongThuyCt;
use App\Models\MauSacNgoiHaiCoCt;
use App\Models\MauSacNgoiHaiVanMieuCt;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiBoNocCt;
use App\Models\NgoiHaiCoCt;
use App\Models\NgoiHaiVanMieuCt;
use App\Models\PhanLoaiBoNocChuVanCt;
use App\Models\PhanLoaiNgoiBoNocCt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductDetailSeeder extends Seeder
{
    private function seedImage(string $sourceFileName, string $destinationFolder): ?string
    {
        $sourcePath = public_path('images-seed/'.$sourceFileName);

        if (! File::exists($sourcePath)) {
            return null;
        }

        $extension = File::extension($sourcePath);
        $filename = Str::random(16).'_'.time().'.'.$extension;
        $destPath = $destinationFolder.'/'.$filename;

        Storage::disk('public')->put($destPath, File::get($sourcePath));

        return $destPath;
    }

    private function pickRandom(array $items, int $min = 2, int $max = 3): array
    {
        $count = min(rand($min, $max), count($items));
        $keys = (array) array_rand($items, $count);

        return array_map(fn ($k) => $items[$k], $keys);
    }

    private function seedMultipleImages(array $sourceFiles, string $folder, int $min = 2, int $max = 3): array
    {
        $picked = $this->pickRandom($sourceFiles, $min, $max);

        return array_values(array_filter(array_map(
            fn ($f) => $this->seedImage($f, $folder), $picked
        )));
    }

    public function run(): void
    {
        $allFolders = [
            'ngoi_am_duong_ct', 'gach_hoa_thong_gio_ct', 'linh_vat_phong_thuy_ct',
            'gach_co_bat_trang_ct', 'gach_trang_tri_ct',
            'ngoi_hai_co_ct', 'mau_sac_ngoi_hai_co_ct',
            'ngoi_hai_van_mieu_ct', 'mau_sac_ngoi_hai_van_mieu_ct',
            'bo_noc_chu_van_ct', 'phan_loai_bo_noc_chu_van_ct',
            'ngoi_bo_noc_ct', 'phan_loai_ngoi_bo_noc_ct',
        ];

        foreach ($allFolders as $folder) {
            Storage::disk('public')->deleteDirectory($folder);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('mau_sac_ngoi_hai_co_ct')->truncate();
        DB::table('mau_sac_ngoi_hai_van_mieu_ct')->truncate();
        DB::table('phan_loai_bo_noc_chu_van_ct')->truncate();
        DB::table('phan_loai_ngoi_bo_noc_ct')->truncate();
        DB::table('ngoi_hai_co_ct')->truncate();
        DB::table('ngoi_hai_van_mieu_ct')->truncate();
        DB::table('bo_noc_chu_van_ct')->truncate();
        DB::table('ngoi_bo_noc_ct')->truncate();
        DB::table('ngoi_am_duong_ct')->truncate();
        DB::table('gach_hoa_thong_gio_ct')->truncate();
        DB::table('linh_vat_phong_thuy_ct')->truncate();
        DB::table('gach_co_bat_trang_ct')->truncate();
        DB::table('gach_trang_tri_ct')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->seedNgoiAmDuong();
        $this->seedGachHoaThongGio();
        $this->seedLinhVatPhongThuy();
        $this->seedGachCoBatTrang();
        $this->seedGachTrangTri();
        $this->seedNgoiHaiVanMieuVaHaiCo();
        $this->seedBoNocChuVan();
        $this->seedNgoiBoNoc();
    }

    // ─── Phase 2: 5 standalone tables ───────────────────────────────────────

    private function seedNgoiAmDuong(): void
    {
        $sourceImages = [
            'am-duong-detail-01.png', 'am-duong-detail-02.png', 'am-duong-detail-03.png',
            'am-duong-detail.png', 'nad-detail.png',
            'ngoi-am-duong-01.jpg', 'ngoi-am-duong-02.png', 'ngoi-am-duong-pk.png',
        ];
        $namePrefixes = ['Tráng Men Xanh', 'Đất Nung Đỏ', 'Men Vàng', 'Men Nâu', 'Men Xanh Ngọc', 'Men Đen', 'Cổ Điển', 'Cao Cấp'];
        $sizeOptions = ['Cỡ Trung (M)', 'Cỡ Lớn (L)', 'Cỡ Nhỏ (S)', 'Cỡ Đại (XL)'];
        $descPool = [
            'Nhiệt độ nung: Lên tới 1200 độ C',
            'Đặc tính: Chống rêu mốc, độ bền màu vĩnh cửu',
            'Ứng dụng: Lợp mái đình, chùa, nhà thờ họ',
            'Chất liệu: Đất sét Bát Tràng nguyên chất',
            'Màu sắc nguyên bản của đất nung tự nhiên',
            'Phù hợp với các công trình mang nét đẹp hoài cổ',
            'Độ bền trên 50 năm, chống thấm tuyệt đối',
            'Sản xuất thủ công bởi nghệ nhân làng nghề',
        ];
        $sizeImages = ['ngoi-am-duong-size.png'];

        for ($i = 1; $i <= 8; $i++) {
            NgoiAmDuongCt::create([
                'code' => 'NAD-'.str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => 'Ngói Âm Dương '.$namePrefixes[$i - 1],
                'images' => $this->seedMultipleImages($sourceImages, 'ngoi_am_duong_ct/images'),
                'price' => rand(10, 25) * 1000,
                'des' => $this->pickRandom($descPool, 2, 4),
                'size' => $sizeOptions[array_rand($sizeOptions)],
                'size_image' => rand(0, 1) ? $this->seedImage($sizeImages[array_rand($sizeImages)], 'ngoi_am_duong_ct/sizes') : null,
                'is_delete' => 0,
            ]);
        }
    }

    private function seedGachHoaThongGio(): void
    {
        $sourceImages = [
            'gach-hoa-01.jpg', 'gach-hoa-02.jpg', 'gach-hoa-03.jpg',
            'gach-hoa-05.jpg', 'gach-hoa-06.jpg', 'gach-hoa-07.jpg', 'gach-hoa-08.jpg',
            'gach-hoa-02.png', 'gach-hoa-03.png', 'gach-hoa-05.png',
            'gach-hoa-06.png', 'gach-hoa-07.png', 'gach-hoa-08.png',
        ];
        $hoaTietNames = ['Hoa Mai', 'Hoa Sen', 'Chữ Thọ', 'Chữ Vạn', 'Hình Học', 'Phong Cảnh', 'Rồng Phượng', 'Tứ Quý'];
        $descPool = [
            'Chất liệu: Đất sét nung Bát Tràng',
            'Giúp lưu thông không khí, lấy sáng tự nhiên',
            'Thẩm mỹ cao, phù hợp xây tường rào, vách ngăn',
            'Chống nóng, cách âm hiệu quả',
            'Họa tiết đắp nổi thủ công tinh xảo',
            'Dễ dàng vệ sinh, không bám bụi',
        ];

        for ($i = 1; $i <= 8; $i++) {
            GachHoaThongGioCt::create([
                'code' => 'GHTG-'.str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => 'Gạch Hoa Thông Gió '.$hoaTietNames[$i - 1],
                'images' => $this->seedMultipleImages($sourceImages, 'gach_hoa_thong_gio_ct/images'),
                'price' => rand(20, 50) * 1000,
                'des' => $this->pickRandom($descPool, 2, 4),
                'size' => '20 x 20 x '.rand(5, 7).' cm',
                'is_delete' => 0,
            ]);
        }
    }

    private function seedLinhVatPhongThuy(): void
    {
        $products = [
            ['name' => 'Đầu Rồng Trang Trí Mái', 'images' => ['dau-rong.png'], 'size' => 'Cao 45cm x Dài 60cm'],
            ['name' => 'Nghê Chầu Bờ Nóc', 'images' => ['nghe.png'], 'size' => 'Cao 35cm'],
            ['name' => 'Tượng Phượng Hoàng', 'images' => ['phuong.png'], 'size' => 'Cao 50cm'],
            ['name' => 'Đầu Rồng Mini', 'images' => ['dau-rong.png'], 'size' => 'Cao 25cm x Dài 35cm'],
            ['name' => 'Nghê Mini Trang Trí', 'images' => ['nghe.png'], 'size' => 'Cao 20cm'],
            ['name' => 'Phượng Hoàng Mini', 'images' => ['phuong.png'], 'size' => 'Cao 30cm'],
        ];
        $descPool = [
            'Biểu tượng của quyền lực và sự uy nghiêm',
            'Chế tác thủ công tinh xảo bởi nghệ nhân Bát Tràng',
            'Linh vật thuần Việt, xua đuổi tà ma, bảo vệ gia chủ',
            'Mang lại may mắn và thịnh vượng',
            'Phù hợp trang trí mái đình, chùa, nhà thờ họ',
            'Đất sét nung ở nhiệt độ cao, bền vững với thời gian',
        ];
        $sizeDescPool = [
            'Trọng lượng: 15kg', 'Trọng lượng: 8kg', 'Trọng lượng: 5kg',
            'Độ dày chân đế: 5cm', 'Độ dày chân đế: 3cm',
            'Chất liệu: Đất sét Bát Tràng nung 1200 độ C',
        ];

        for ($i = 0; $i < 6; $i++) {
            LinhVatPhongThuyCt::create([
                'code' => 'LVPT-'.str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'name' => $products[$i]['name'],
                'images' => array_values(array_filter(array_map(
                    fn ($f) => $this->seedImage($f, 'linh_vat_phong_thuy_ct/images'),
                    $products[$i]['images']
                ))),
                'price' => rand(500, 2000) * 1000,
                'des' => $this->pickRandom($descPool, 2, 4),
                'size' => $products[$i]['size'],
                'size_des' => $this->pickRandom($sizeDescPool, 1, 2),
                'is_delete' => 0,
            ]);
        }
    }

    private function seedGachCoBatTrang(): void
    {
        $sourceImages = ['gach-bat-detail-1.png', 'gach-bat-detail-2.png', 'gach-bat-01.jpg', 'gach-bat-02.jpg'];
        $namePrefixes = ['Cắt Ép Bát Tràng', 'Cổ Điển', 'Giả Cổ', 'Mộc Bản', 'Tráng Men', 'Tự Nhiên'];
        $sizeOptions = ['5 x 20 x 2 cm', '10 x 20 x 2 cm', '7.5 x 30 x 2 cm'];
        $descPool = [
            'Mang nét đẹp thời gian, hoài cổ',
            'Thích hợp ốp tường trang trí quán cafe, nhà hàng',
            'Gạch nung thủ công, từng viên một',
            'Màu sắc tự nhiên, không pha tạp hóa chất',
            'Bề mặt mộc, tạo cảm giác ấm cúng',
        ];
        $sizeImages = ['gach-bat-size-1.png'];

        for ($i = 0; $i < 6; $i++) {
            GachCoBatTrangCt::create([
                'code' => 'GCBT-'.str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'name' => 'Gạch Cổ Bát Tràng '.$namePrefixes[$i],
                'images' => $this->seedMultipleImages($sourceImages, 'gach_co_bat_trang_ct/images', 1, 2),
                'price' => rand(200, 500) * 1000,
                'des' => $this->pickRandom($descPool, 2, 3),
                'size' => $sizeOptions[array_rand($sizeOptions)],
                'size_image' => rand(0, 1) ? $this->seedImage($sizeImages[array_rand($sizeImages)], 'gach_co_bat_trang_ct/sizes') : null,
                'is_delete' => 0,
            ]);
        }
    }

    private function seedGachTrangTri(): void
    {
        $sourceImages = ['trang-tri-01.png', 'trang-tri-02.png'];
        $hoaTietNames = ['Chữ Thọ Khung Tròn', 'Chữ Vạn', 'Hoa Sen', 'Rồng', 'Phượng', 'Tứ Linh'];
        $descPool = [
            'Tạo điểm nhấn phong thủy cho bức tường rào',
            'Họa tiết tinh xảo, sắc nét',
            'Chất liệu đất sét cao cấp Bát Tràng',
            'Chống phai màu, bền đẹp theo thời gian',
            'Dễ dàng lắp đặt vào tường gạch',
        ];

        for ($i = 0; $i < 6; $i++) {
            GachTrangTriCt::create([
                'code' => 'GTT-'.str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'name' => 'Gạch Trang Trí '.$hoaTietNames[$i],
                'images' => $this->seedMultipleImages($sourceImages, 'gach_trang_tri_ct/images', 1, 2),
                'price' => rand(40, 80) * 1000,
                'des' => $this->pickRandom($descPool, 2, 3),
                'size' => 'Đường kính '.rand(25, 40).'cm',
                'is_delete' => 0,
            ]);
        }
    }

    // ─── Phase 3: 4 tables with child relationships ──────────────────────────

    private function seedNgoiHaiVanMieuVaHaiCo(): void
    {
        $haiCoParentImages = ['ngoi-hai-detail.png', 'ngoi-hai-01.png', 'ngoi-hai-02.png', 'ngoi-hai-03.png'];
        $haiCoDescPool = [
            'Lợp cho nhà gỗ 3 gian, 5 gian',
            'Độ bền trên 50 năm, không phai màu',
            'Chống thấm, chống rêu mốc tuyệt đối',
            'Phù hợp công trình nhà thờ họ, đình chùa',
        ];
        $haiCoSizeOptions = ['15 x 15 cm', '18 x 18 cm', '20 x 18 cm'];

        $haiVMParentImages = ['ngoi-van-mieu-detail.png', 'ngoi-van-mieu-detail-2.png', 'ngoi-hai-01.png'];
        $haiVMDescPool = [
            'Chống nóng cực tốt, không bị rêu mốc',
            'Men khô tự nhiên, màu sắc trường tồn',
            'Thích hợp công trình tâm linh, di tích',
            'Sản xuất theo công nghệ nung cổ truyền',
        ];
        $haiVMSizeOptions = ['20 x 15 cm', '22 x 18 cm', '20 x 20 cm'];

        $colorImages = ['ngoi-hai-02.png', 'ngoi-hai-03.png', 'ngoi-01.jpg', 'ngoi-03.jpg', 'ngoi-04.jpg', 'ngoi-05.jpg', 'ngoi-06.jpg'];
        $colorNames = ['Đỏ Gốm', 'Nâu Xám', 'Xanh Rêu', 'Nâu Socola', 'Vàng Đất', 'Đen Tuyền', 'Xanh Ngọc'];

        // Ngói Hài Cổ
        for ($i = 1; $i <= 5; $i++) {
            $haiCo = NgoiHaiCoCt::create([
                'name' => 'Ngói Hài Cổ '.$colorNames[array_rand($colorNames)],
                'images' => $this->seedMultipleImages($haiCoParentImages, 'ngoi_hai_co_ct/images', 1, 2),
                'des' => $this->pickRandom($haiCoDescPool, 2, 3),
                'size' => $haiCoSizeOptions[array_rand($haiCoSizeOptions)],
                'is_delete' => 0,
            ]);

            $numColors = rand(2, 3);
            for ($j = 1; $j <= $numColors; $j++) {
                MauSacNgoiHaiCoCt::create([
                    'name' => $colorNames[array_rand($colorNames)],
                    'image' => $this->seedImage($colorImages[array_rand($colorImages)], 'mau_sac_ngoi_hai_co_ct/images'),
                    'code' => 'NHC-'.str_pad($i, 2, '0', STR_PAD_LEFT).'-C'.$j,
                    'price' => rand(5, 10) * 1000,
                    'ngoi_hai_co_ct_id' => $haiCo->ngoi_hai_co_ct_id,
                    'is_delete' => 0,
                ]);
            }
        }

        // Ngói Hài Văn Miếu
        for ($i = 1; $i <= 5; $i++) {
            $haiVM = NgoiHaiVanMieuCt::create([
                'name' => 'Ngói Hài Văn Miếu '.$colorNames[array_rand($colorNames)],
                'images' => $this->seedMultipleImages($haiVMParentImages, 'ngoi_hai_van_mieu_ct/images', 1, 2),
                'price' => 0,
                'mau_sac_id' => 0,
                'des' => $this->pickRandom($haiVMDescPool, 2, 3),
                'size' => $haiVMSizeOptions[array_rand($haiVMSizeOptions)],
                'is_delete' => 0,
            ]);

            $numColors = rand(2, 3);
            for ($j = 1; $j <= $numColors; $j++) {
                MauSacNgoiHaiVanMieuCt::create([
                    'name' => $colorNames[array_rand($colorNames)],
                    'image' => $this->seedImage($colorImages[array_rand($colorImages)], 'mau_sac_ngoi_hai_van_mieu_ct/images'),
                    'code' => 'NHVM-'.str_pad($i, 2, '0', STR_PAD_LEFT).'-C'.$j,
                    'price' => rand(7, 15) * 1000,
                    'ngoi_hai_van_mieu_ct_id' => $haiVM->ngoi_hai_van_mieu_ct_id,
                    'is_delete' => 0,
                ]);
            }
        }
    }

    private function seedBoNocChuVan(): void
    {
        $chuVanParentImages = ['chu-van-1.png', 'chu-van-2.png', 'chu-van-3.png'];
        $chuVanDescPool = [
            'Họa tiết chữ Vạn mang ý nghĩa tốt lành, trường thọ',
            'Chống thấm nước tuyệt đối',
            'Trang trí bờ nóc đình chùa, nhà thờ',
            'Đất sét nung già, độ bền vượt trội',
        ];
        $chuVanSizeDescPool = [
            'Dài 30cm, Dày 2cm', 'Dài 35cm, Dày 2.5cm',
            'Dài 40cm, Dày 3cm', 'Dài 25cm, Dày 1.5cm',
        ];
        $chuVanPhanLoai = [
            ['name' => 'Men Vàng Đồng', 'price' => 55000],
            ['name' => 'Men Xanh Lục', 'price' => 60000],
            ['name' => 'Men Nâu Đất', 'price' => 45000],
            ['name' => 'Đất Nung Tự Nhiên', 'price' => 35000],
        ];

        for ($i = 1; $i <= 6; $i++) {
            $boNoc = BoNocChuVanCt::create([
                'name' => 'Bộ Nóc Chữ Vạn '.$chuVanPhanLoai[array_rand($chuVanPhanLoai)]['name'],
                'images' => $this->seedMultipleImages($chuVanParentImages, 'bo_noc_chu_van_ct/images', 1, 3),
                'des' => $this->pickRandom($chuVanDescPool, 2, 3),
                'size' => 'Khổ tiêu chuẩn',
                'size_des' => $this->pickRandom($chuVanSizeDescPool, 1, 2),
                'is_delete' => 0,
            ]);

            $numTypes = rand(1, 2);
            $pickedKeys = (array) array_rand($chuVanPhanLoai, $numTypes);
            for ($j = 1; $j <= $numTypes; $j++) {
                $type = $chuVanPhanLoai[$pickedKeys[$j - 1]];
                PhanLoaiBoNocChuVanCt::create([
                    'name' => $type['name'].' - Bộ '.$i,
                    'code' => 'BNCV-'.str_pad($i, 2, '0', STR_PAD_LEFT).'-P'.$j,
                    'price' => $type['price'] + rand(-5000, 5000),
                    'bo_noc_chu_van_ct_id' => $boNoc->bo_noc_chu_van_ct_id,
                ]);
            }
        }
    }

    private function seedNgoiBoNoc(): void
    {
        $boNocParentImages = ['bo-noc.png', 'ngoi-02.png', 'ngoi-07.jpg', 'ngoi-08.jpg'];
        $boNocDescPool = [
            'Dùng để úp nóc mái, chống dột điểm tiếp giáp',
            'Kết nối hai mặt mái, tạo đường nóc hoàn hảo',
            'Chịu lực tốt, không nứt vỡ khi thời tiết khắc nghiệt',
        ];
        $boNocPhanLoai = [
            ['name' => 'Loại Thường Đất Nung', 'price' => 12000],
            ['name' => 'Loại Tráng Men', 'price' => 18000],
            ['name' => 'Loại Cao Cấp Chống Thấm', 'price' => 25000],
        ];

        for ($i = 1; $i <= 6; $i++) {
            $ngoiNoc = NgoiBoNocCt::create([
                'name' => 'Ngói Bò Nóc '.$boNocPhanLoai[array_rand($boNocPhanLoai)]['name'],
                'images' => $this->seedMultipleImages($boNocParentImages, 'ngoi_bo_noc_ct/images', 1, 2),
                'des' => $this->pickRandom($boNocDescPool, 1, 3),
                'size' => rand(25, 35).' x '.rand(12, 18).' cm',
                'size_des' => [],
                'is_delete' => 0,
            ]);

            $numTypes = rand(1, 2);
            $pickedKeys = (array) array_rand($boNocPhanLoai, $numTypes);
            for ($j = 1; $j <= $numTypes; $j++) {
                $type = $boNocPhanLoai[$pickedKeys[$j - 1]];
                PhanLoaiNgoiBoNocCt::create([
                    'name' => $type['name'].' - Dòng '.$i,
                    'code' => 'NBN-'.str_pad($i, 2, '0', STR_PAD_LEFT).'-P'.$j,
                    'price' => $type['price'] + rand(-3000, 3000),
                    'ngoi_bo_noc_ct_id' => $ngoiNoc->ngoi_bo_noc_ct_id,
                ]);
            }
        }
    }
}
