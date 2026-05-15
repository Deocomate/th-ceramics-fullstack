<?php

namespace Database\Seeders;

use App\Models\PhanLoaiPhuKienNgoiCt;
use App\Models\PhuKienNgoi;
use App\Models\PhuKienNgoiCt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhuKienNgoiSeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PhuKienNgoi::truncate();
        PhanLoaiPhuKienNgoiCt::truncate();
        PhuKienNgoiCt::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        PhuKienNgoi::create([
            'thumbnail_main' => $this->copySingleImage('phu-kien-ngoi', 'pk-banner.png'),
            'banner_text_1' => 'Phụ Kiện Ngói Thanh Hải — Bát Tràng Nguyên Chất',
            'banner_text_2' => 'Nung luyện ở 1.250 độ C trong 72 giờ, đảm bảo độ cứng chắc.',
            'sec1_title' => 'Ngói Bò Nóc — Uy Nghi',
            'sec1_image' => $this->copySingleImage('phu-kien-ngoi', 'bo-noc.png'),
            'sec2_title' => 'Bộ Nóc Chữ Vạn — Cát Tường',
            'sec2_image' => $this->copySingleImage('phu-kien-ngoi', 'chu-van-1.png'),
            'images' => $this->copySpecificImages('phu-kien-ngoi-gallery', ['pk-01.jpg', 'pk-02.jpg', 'pk-03.jpg', 'pk-04.jpg', 'pk-05.jpg', 'pk-06.jpg', 'pk-07.jpg', 'pk-08.jpg']),
        ]);

        $boNocNames = ['Men Xanh Lục', 'Men Vàng Hoàng Gia', 'Men Đỏ Nâu', 'Men Trắng Sứ', 'Đất Nung Tự Nhiên', 'Men Xanh Ngọc', 'Men Đen Huyền Bí', 'Men Xanh Dương', 'Men Vân Đá', 'Men Rêu Phong', 'Men Ngọc Trai', 'Men Lam', 'Men Hồng Đào', 'Men Nâu Socola', 'Men Xanh Rêu'];
        $bnFiles = ['bo-noc.png', 'pk-01.jpg', 'pk-02.jpg', 'pk-03.jpg'];

        for ($i = 1; $i <= 15; $i++) {
            shuffle($bnFiles);
            $productBN = PhuKienNgoiCt::create([
                'name' => "Ngói Bò Nóc Tráng Men {$boNocNames[$i - 1]}",
                'category_type' => PhuKienNgoiCt::TYPE_BO_NOC,
                'color' => 'Tự chọn',
                'images' => $this->copySpecificImages('phu-kien-ngoi-chi-tiet', $bnFiles),
                'des' => $this->generateDescription(),
                'size' => '35 x 20 x 8 cm',
                'size_image' => $this->copySingleImage('phu-kien-ngoi', 'ngoi-am-duong-size.png'),
                'size_des' => $this->generateSizeDescription(),
                'is_delete' => 0,
            ]);

            $pid = str_pad($productBN->phu_kien_ngoi_ct_id, 3, '0', STR_PAD_LEFT);
            PhanLoaiPhuKienNgoiCt::create(['name' => "Bò Nóc {$boNocNames[$i - 1]} - Tiêu Chuẩn", 'code' => "NBN-{$pid}-STD", 'price' => 45000 + ($i * 1000), 'phu_kien_ngoi_ct_id' => $productBN->phu_kien_ngoi_ct_id, 'is_delete' => 0]);
            PhanLoaiPhuKienNgoiCt::create(['name' => "Bò Nóc {$boNocNames[$i - 1]} - Cao Cấp", 'code' => "NBN-{$pid}-PRE", 'price' => 65000 + ($i * 1500), 'phu_kien_ngoi_ct_id' => $productBN->phu_kien_ngoi_ct_id, 'is_delete' => 0]);
        }

        $chuVanNames = ['Men Vàng Đồng', 'Men Xanh Lục', 'Men Nâu Đất', 'Đất Nung Mộc', 'Men Hỏa Biến', 'Men Trắng Sứ', 'Men Xanh Ngọc', 'Men Đen Tuyền', 'Men Đỏ Gạch', 'Men Lam Huế', 'Men Xanh Rêu', 'Men Vàng Nghệ', 'Men Hồng Phấn', 'Men Ngọc Bích', 'Men Đồng Hun'];
        $cvFiles = ['chu-van-1.png', 'chu-van-2.png', 'chu-van-3.png'];

        for ($i = 1; $i <= 15; $i++) {
            shuffle($cvFiles);
            $productCV = PhuKienNgoiCt::create([
                'name' => "Bộ Nóc Chữ Vạn {$chuVanNames[$i - 1]}",
                'category_type' => PhuKienNgoiCt::TYPE_CHU_VAN,
                'color' => 'Tự chọn',
                'images' => $this->copySpecificImages('phu-kien-ngoi-chi-tiet', $cvFiles),
                'des' => $this->generateDescription(),
                'size' => '30 x 20 x 2.5 cm',
                'size_image' => $this->copySingleImage('phu-kien-ngoi', 'ngoi-am-duong-size.png'),
                'size_des' => $this->generateSizeDescription(),
                'is_delete' => 0,
            ]);

            $pid = str_pad($productCV->phu_kien_ngoi_ct_id, 3, '0', STR_PAD_LEFT);
            PhanLoaiPhuKienNgoiCt::create(['name' => "Chữ Vạn {$chuVanNames[$i - 1]} - Tiêu Chuẩn", 'code' => "BNC-{$pid}-STD", 'price' => 45000 + ($i * 800), 'phu_kien_ngoi_ct_id' => $productCV->phu_kien_ngoi_ct_id, 'is_delete' => 0]);
            PhanLoaiPhuKienNgoiCt::create(['name' => "Chữ Vạn {$chuVanNames[$i - 1]} - Cao Cấp", 'code' => "BNC-{$pid}-PRE", 'price' => 65000 + ($i * 1200), 'phu_kien_ngoi_ct_id' => $productCV->phu_kien_ngoi_ct_id, 'is_delete' => 0]);
        }
    }
}
