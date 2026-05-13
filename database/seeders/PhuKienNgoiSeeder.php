<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PhuKienNgoi;
use App\Models\NgoiBoNocCt;
use App\Models\PhanLoaiNgoiBoNocCt;
use App\Models\BoNocChuVanCt;
use App\Models\PhanLoaiBoNocChuVanCt;

class PhuKienNgoiSeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PhuKienNgoi::truncate();
        NgoiBoNocCt::truncate();
        PhanLoaiNgoiBoNocCt::truncate();
        BoNocChuVanCt::truncate();
        PhanLoaiBoNocChuVanCt::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        PhuKienNgoi::create([
            'thumbnail_main' => $this->generateSingleImage('phu-kien-ngoi', 'main-banner.jpg'),
            'banner_text_1'  => 'Phụ Kiện Ngói Thanh Hải — Hoàn Thiện Vẻ Đẹp Kiến Trúc Mái Ngói',
            'banner_text_2'  => 'Nung luyện ở nhiệt độ 1.250 độ C trong 72 giờ, đảm bảo độ cứng chắc và khả năng chống rêu mốc vĩnh viễn.',
            'sec1_title'     => 'Ngói Bò Nóc — Đường Nét Uy Nghi',
            'sec1_image'     => $this->generateSingleImage('phu-kien-ngoi', 'sec1-banner.jpg'),
            'sec2_title'     => 'Bộ Nóc Chữ Vạn — Biểu Tượng Cát Tường',
            'sec2_image'     => $this->generateSingleImage('phu-kien-ngoi', 'sec2-banner.jpg'),
            'images'         => $this->generateGallery('phu-kien-ngoi-gallery', 15),
        ]);

        $boNocNames =[
            'Men Xanh Lục', 'Men Vàng Hoàng Gia', 'Men Đỏ Nâu',
            'Men Trắng Sứ', 'Đất Nung Tự Nhiên', 'Men Xanh Ngọc',
            'Men Đen Huyền Bí', 'Men Xanh Dương', 'Men Vân Đá',
            'Men Rêu Phong', 'Men Ngọc Trai', 'Men Lam',
            'Men Hồng Đào', 'Men Nâu Socola', 'Men Xanh Rêu',
        ];

        for ($i = 1; $i <= 15; $i++) {
            $productBN = NgoiBoNocCt::create([
                'name'       => "Ngói Bò Nóc Tráng Men {$boNocNames[$i-1]}",
                // GỌI HÀM RANDOM Ở ĐÂY:
                'images'     => $this->generateRandomGallery('phu-kien-ngoi-chi-tiet', 30, 10),
                'des'        => $this->generateDescription(),
                'size'       => '35 x 20 x 8 cm',
                'size_image' => $this->generateSingleImage('phu-kien-ngoi', 'size-guide-bn.jpg'),
                'size_des'   => $this->generateSizeDescription(),
                'is_delete'  => 0,
            ]);

            $pid = str_pad($productBN->ngoi_bo_noc_ct_id, 3, '0', STR_PAD_LEFT);
            PhanLoaiNgoiBoNocCt::create(['name' => "Ngói Bò Nóc {$boNocNames[$i-1]} - Tiêu Chuẩn (#{$pid})", 'code' => "NBN-{$pid}-STD", 'price' => 45000 + ($i*1000), 'ngoi_bo_noc_ct_id' => $productBN->ngoi_bo_noc_ct_id, 'is_delete' => 0]);
            PhanLoaiNgoiBoNocCt::create(['name' => "Ngói Bò Nóc {$boNocNames[$i-1]} - Cao Cấp (#{$pid})", 'code' => "NBN-{$pid}-PRE", 'price' => 65000 + ($i*1500), 'ngoi_bo_noc_ct_id' => $productBN->ngoi_bo_noc_ct_id, 'is_delete' => 0]);
            PhanLoaiNgoiBoNocCt::create(['name' => "Ngói Bò Nóc {$boNocNames[$i-1]} - Đặc Biệt (#{$pid})", 'code' => "NBN-{$pid}-SPC", 'price' => 95000 + ($i*2000), 'ngoi_bo_noc_ct_id' => $productBN->ngoi_bo_noc_ct_id, 'is_delete' => 0]);
        }

        $chuVanNames =[
            'Men Vàng Đồng', 'Men Xanh Lục', 'Men Nâu Đất',
            'Đất Nung Mộc', 'Men Hỏa Biến', 'Men Trắng Sứ',
            'Men Xanh Ngọc', 'Men Đen Tuyền', 'Men Đỏ Gạch',
            'Men Lam Huế', 'Men Xanh Rêu', 'Men Vàng Nghệ',
            'Men Hồng Phấn', 'Men Ngọc Bích', 'Men Đồng Hun',
        ];

        for ($i = 1; $i <= 15; $i++) {
            $productCV = BoNocChuVanCt::create([
                'name'       => "Bộ Nóc Chữ Vạn {$chuVanNames[$i-1]}",
                // GỌI HÀM RANDOM Ở ĐÂY:
                'images'     => $this->generateRandomGallery('phu-kien-ngoi-chi-tiet', 30, 10),
                'des'        => $this->generateDescription(),
                'size'       => '30 x 20 x 2.5 cm',
                'size_image' => $this->generateSingleImage('phu-kien-ngoi', 'size-guide-cv.jpg'),
                'size_des'   => $this->generateSizeDescription(),
                'is_delete'  => 0,
            ]);

            $pid = str_pad($productCV->bo_noc_chu_van_ct_id, 3, '0', STR_PAD_LEFT);
            PhanLoaiBoNocChuVanCt::create(['name' => "Chữ Vạn {$chuVanNames[$i-1]} - Tiêu Chuẩn (#{$pid})", 'code' => "BNC-{$pid}-STD", 'price' => 45000 + ($i*800), 'bo_noc_chu_van_ct_id' => $productCV->bo_noc_chu_van_ct_id, 'is_delete' => 0]);
            PhanLoaiBoNocChuVanCt::create(['name' => "Chữ Vạn {$chuVanNames[$i-1]} - Cao Cấp (#{$pid})", 'code' => "BNC-{$pid}-PRE", 'price' => 65000 + ($i*1200), 'bo_noc_chu_van_ct_id' => $productCV->bo_noc_chu_van_ct_id, 'is_delete' => 0]);
            PhanLoaiBoNocChuVanCt::create(['name' => "Chữ Vạn {$chuVanNames[$i-1]} - Đặc Biệt (#{$pid})", 'code' => "BNC-{$pid}-SPC", 'price' => 95000 + ($i*2000), 'bo_noc_chu_van_ct_id' => $productCV->bo_noc_chu_van_ct_id, 'is_delete' => 0]);
        }
    }
}