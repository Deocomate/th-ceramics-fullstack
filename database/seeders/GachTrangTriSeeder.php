<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GachTrangTri;
use App\Models\GachTrangTriCt;

class GachTrangTriSeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        GachTrangTri::truncate();
        GachTrangTriCt::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        GachTrangTri::create([
            'thumbnail_main'   => $this->generateSingleImage('gach-trang-tri', 'main-banner.jpg'),
            'video'            => $this->generateVideoLink(),
            'images'           => $this->generateGallery('gach-trang-tri-cong-doan', 15),
            'ung_dung_da_dang' => [
                'main' =>[
                    'title' => 'Ốp Tường Mặt Tiền Công Trình — Điểm Nhấn Kiến Trúc Đẳng Cấp Cho Biệt Thự Và Nhà Phố',
                    'image' => $this->generateSingleImage('gach-trang-tri', 'app-main.jpg'),
                ],
                'sub_1' =>[
                    'title' => 'Ốp Tường Rào — Tạo Vẻ Đẹp Sang Trọng Và Bảo Vệ Công Trình Khỏi Tác Động Thời Tiết',
                    'image' => $this->generateSingleImage('gach-trang-tri', 'app-sub-1.jpg'),
                ],
                'sub_2' => [
                    'title' => 'Trang Trí Quầy Bar Và Khu Lễ Tân — Mang Phong Cách Indochine Độc Đáo',
                    'image' => $this->generateSingleImage('gach-trang-tri', 'app-sub-2.jpg'),
                ],
                'sub_3' => [
                    'title' => 'Ốp Tường Phòng Khách — Tạo Điểm Nhấn Nghệ Thuật Cho Không Gian Sống',
                    'image' => $this->generateSingleImage('gach-trang-tri', 'app-sub-3.jpg'),
                ],
                'sub_4' => [
                    'title' => 'Trang Trí Ngoại Thất Sân Vườn — Kết Nối Không Gian Sống Với Thiên Nhiên',
                    'image' => $this->generateSingleImage('gach-trang-tri', 'app-sub-4.jpg'),
                ],
            ],
        ]);

        $applications =[
            'Ốp Tường Rào Biệt Thự', 'Ốp Mặt Tiền Nhà Phố', 'Trang Trí Quầy Bar',
            'Ốp Tường Phòng Khách', 'Trang Trí Đại Sảnh Khách Sạn', 'Ốp Cổng Nhà',
            'Trang Trí Khu Lễ Tân', 'Ốp Chân Tường Ngoại Thất', 'Trang Trí Hành Lang',
            'Ốp Tường Bếp', 'Trang Trí Sân Vườn', 'Ốp Mặt Tiền Nhà Hàng',
            'Trang Trí Hồ Bơi', 'Ốp Tường Phòng Thờ', 'Ốp Cột Trụ Cổng',
        ];

        for ($i = 1; $i <= 15; $i++) {
            GachTrangTriCt::create([
                'code'       => 'GTT-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name'       => "Gạch Trang Trí Men Hỏa Biến Cao Cấp - {$applications[$i-1]}",
                // GỌI HÀM RANDOM Ở ĐÂY CHO MỖI SẢN PHẨM:
                'images'     => $this->generateRandomGallery('gach-trang-tri-chi-tiet', 30, 10),
                'price'      => 28000 + ($i * 1500),
                'des'        => $this->generateDescription(),
                'size'       => '10 x 20 cm (Tiêu chuẩn phổ biến cho ốp tường trang trí)',
                'size_image' => $this->generateSingleImage('gach-trang-tri', 'size-guide.jpg'),
                'is_delete'  => 0,
            ]);
        }
    }
}