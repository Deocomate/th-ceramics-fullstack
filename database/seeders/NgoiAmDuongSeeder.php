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
            'thumbnail_main' => $this->copySingleImage('ngoi-am-duong', 'ngoi-am-duong-banner.jpg'),
            'thumbnail1'     => $this->copySingleImage('ngoi-am-duong', 'ngoi-am-duong-01.jpg'),
            'thumbnail2'     => $this->copySingleImage('ngoi-am-duong', 'ngoi-am-duong-02.png'),
            'video'          => $this->generateVideoLink(),
        ]);

        // Tập hợp danh sách tên file ảnh Ngói Âm Dương có thật
        $sourceFiles = [
            'am-duong-detail-01.png', 'am-duong-detail-02.png', 'am-duong-detail-03.png', 
            'am-duong-detail.png', 'ngoi-am-duong-01.jpg', 'ngoi-am-duong-02.png', 'nad-detail.png'
        ];

        for ($i = 1; $i <= 16; $i++) {
            // Xáo trộn ảnh để thumbnail luôn khác nhau
            shuffle($sourceFiles);
            
            NgoiAmDuongCt::create([
                'code'       => 'NAD-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name'       => "Ngói Âm Dương Tráng Men Cao Cấp Bát Tràng - Phiên bản {$i}",
                'images'     => $this->copySpecificImages('ngoi-am-duong-chi-tiet', $sourceFiles), 
                'price'      => 25000 + ($i * 1000),
                'des'        => $this->generateDescription(), 
                'size'       => '27 viên / 1 Mét vuông',
                'size_image' => $this->copySingleImage('ngoi-am-duong', 'ngoi-am-duong-size.png'),
                'is_delete'  => 0,
            ]);
        }

        $colorFiles =[
            'Men Xanh Lục' => 'ngoi-01.jpg', 
            'Men Xanh Ngọc' => 'ngoi-02.png', 
            'Men Vàng' => 'ngoi-03.jpg', 
            'Men Đỏ Nâu' => 'ngoi-04.jpg', 
            'Đất Nung Tự Nhiên' => 'ngoi-05.jpg', 
            'Men Xanh Dương' => 'ngoi-06.jpg', 
            'Men Trắng Sứ' => 'ngoi-07.jpg',
            'Đỏ Cờ' => 'do-co.png'
        ];

        foreach ($colorFiles as $colorName => $fileName) {
            MauSacNgoiAmDuongCt::create([
                'name'  => $colorName,
                'image' => $this->copySingleImage('ngoi-am-duong-colors', $fileName),
            ]);
        }
    }
}