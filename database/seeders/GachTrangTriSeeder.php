<?php

namespace Database\Seeders;

use App\Models\GachTrangTri;
use App\Models\GachTrangTriCt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'thumbnail_main' => $this->copySingleImage('gach-trang-tri', 'gach-trang-tri-banner.png'),
            'video' => $this->generateVideoLink(),
            'images' => $this->copySpecificImages('gach-trang-tri-cong-doan', ['trang-tri-slide-01.jpg', 'trang-tri-slide-02.jpg', 'trang-tri-slide-03.jpg']),
            'ung_dung_da_dang' => [
                'main' => [
                    'title' => 'Ốp Tường Mặt Tiền - Điểm Nhấn Kiến Trúc',
                    'image' => $this->copySingleImage('gach-trang-tri', 'work-01.jpg'),
                ],
                'sub_1' => [
                    'title' => 'Ốp Tường Rào - Sang Trọng Và Bảo Vệ',
                    'image' => $this->copySingleImage('gach-trang-tri', 'work-02.jpg'),
                ],
                'sub_2' => [
                    'title' => 'Trang Trí Quầy Bar - Phong Cách Indochine',
                    'image' => $this->copySingleImage('gach-trang-tri', 'work-03.jpg'),
                ],
                'sub_3' => [
                    'title' => 'Phòng Khách - Nghệ Thuật Không Gian',
                    'image' => $this->copySingleImage('gach-trang-tri', 'trang-tri-01.png'),
                ],
                'sub_4' => [
                    'title' => 'Ngoại Thất Sân Vườn - Hài Hòa Thiên Nhiên',
                    'image' => $this->copySingleImage('gach-trang-tri', 'trang-tri-02.png'),
                ],
            ],
        ]);

        $applications = [
            'Ốp Tường Rào', 'Ốp Mặt Tiền', 'Trang Trí Quầy Bar',
            'Ốp Tường Phòng Khách', 'Đại Sảnh Khách Sạn', 'Ốp Cổng Nhà',
            'Khu Lễ Tân', 'Chân Tường Ngoại Thất', 'Hành Lang',
            'Tường Bếp', 'Sân Vườn', 'Mặt Tiền Nhà Hàng',
            'Hồ Bơi', 'Phòng Thờ', 'Cột Trụ Cổng',
        ];

        $files = ['trang-tri-01.png', 'trang-tri-02.png', 'trang-tri-slide-01.jpg', 'trang-tri-slide-02.jpg', 'trang-tri-slide-03.jpg', 'trang-tri-slide-04.jpg', 'gach-detail.png'];

        for ($i = 1; $i <= 15; $i++) {
            shuffle($files);
            GachTrangTriCt::create([
                'code' => 'GTT-2026-'.str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => "Gạch Trang Trí Men Hỏa Biến - {$applications[$i - 1]}",
                'color' => 'Tự chọn',
                'images' => $this->copySpecificImages('gach-trang-tri-chi-tiet', array_slice($files, 0, 5)),
                'price' => 28000 + ($i * 1500),
                'des' => $this->generateDescription(),
                'size' => '10 x 20 cm',
                'size_image' => $this->copySingleImage('gach-trang-tri', 'gach-detail.png'),
                'is_delete' => 0,
            ]);
        }
    }
}
