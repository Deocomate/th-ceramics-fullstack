<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\NgoiAmDuong;
use App\Models\NgoiAmDuongCt;
use App\Models\MauSacNgoiAmDuongCt;

class NgoiAmDuongSeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        NgoiAmDuong::truncate();
        NgoiAmDuongCt::truncate();
        MauSacNgoiAmDuongCt::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        NgoiAmDuong::create([
            'thumbnail_main' => $this->generateSingleImage('ngoi-am-duong', 'main-banner.jpg'),
            'thumbnail1'     => $this->generateSingleImage('ngoi-am-duong', 'thumb-1.jpg'),
            'thumbnail2'     => $this->generateSingleImage('ngoi-am-duong', 'thumb-2.jpg'),
            'video'          => $this->generateVideoLink(),
        ]);

        for ($i = 1; $i <= 16; $i++) {
            NgoiAmDuongCt::create([
                'code'       => 'NAD-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name'       => "Ngói Âm Dương Tráng Men Cao Cấp Bát Tràng - Phiên bản {$i}",
                // GỌI HÀM RANDOM Ở ĐÂY CHO MỖI SẢN PHẨM:
                'images'     => $this->generateRandomGallery('ngoi-am-duong-chi-tiet', 30, 10), 
                'price'      => 25000 + ($i * 1000),
                'des'        => $this->generateDescription(), 
                'size'       => '27 viên / 1 Mét vuông',
                'size_image' => $this->generateSingleImage('ngoi-am-duong', "size-guide.jpg"),
                'is_delete'  => 0,
            ]);
        }

        $colors =[
            'Men Xanh Lục Cổ Điển', 'Men Vàng Hoàng Gia', 'Men Đỏ Nâu Trầm Ấm',
            'Men Xanh Ngọc Hỏa Biến', 'Men Trắng Sứ Cao Cấp', 'Men Đen Huyền Bí', 'Đất Nung Tự Nhiên Không Men'
        ];

        foreach ($colors as $index => $color) {
            MauSacNgoiAmDuongCt::create([
                'name'  => $color,
                'image' => $this->generateSingleImage('ngoi-am-duong', "color-swatch-{$index}.jpg"),
            ]);
        }
    }
}