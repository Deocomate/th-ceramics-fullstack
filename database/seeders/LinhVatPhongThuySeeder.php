<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LinhVatPhongThuy;
use App\Models\LinhVat;
use App\Models\LinhVatPhongThuyAnh;
use App\Models\LinhVatPhongThuyCt;

class LinhVatPhongThuySeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        LinhVatPhongThuy::truncate();
        LinhVat::truncate();
        LinhVatPhongThuyAnh::truncate();
        LinhVatPhongThuyCt::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $parent = LinhVatPhongThuy::create([
            'thumbnail_main' => $this->copySingleImage('linh-vat', 'linh-vat-banner.png'),
            'video'          => $this->generateVideoLink(),
        ]);

        $linhVats = [
            ['title' => 'Long (Rồng Uy Nghi)', 'image' => 'dau-rong.png', 'desc' => 'Rồng là linh vật quyền lực nhất trong tứ linh.'],
            ['title' => 'Lân (Nghê Chấn Thủy)', 'image' => 'nghe.png', 'desc' => 'Lân (Nghê) là linh vật thuần Việt, bảo vệ gia chủ.'],
            ['title' => 'Phượng (Tái Sinh)', 'image' => 'phuong.png', 'desc' => 'Phượng hoàng biểu trưng cho sự thanh cao.'],
        ];

        foreach ($linhVats as $lv) {
            LinhVat::create([
                'title'                  => $lv['title'],
                'image'                  => $this->copySingleImage('linh-vat', $lv['image']),
                'description'            => $lv['desc'],
                'linh_vat_phong_thuy_id' => $parent->linh_vat_phong_thuy_id,
            ]);
        }

        $lvFiles = ['dau-rong.png', 'nghe.png', 'phuong.png', 'dao-kim.png', 'dao-kim.jpg'];

        foreach ($lvFiles as $file) {
            LinhVatPhongThuyAnh::create([
                'image'                  => $this->copySingleImage('linh-vat-gallery', $file),
                'linh_vat_phong_thuy_id' => $parent->linh_vat_phong_thuy_id,
            ]);
        }

        $names = ['Đầu Rồng Bờ Nóc', 'Tượng Nghê Chầu', 'Phượng Hoàng Lửa', 'Kim Thiềm', 'Tỳ Hưu'];

        for ($i = 1; $i <= 15; $i++) {
            $baseName = $names[$i % 5];
            shuffle($lvFiles);
            LinhVatPhongThuyCt::create([
                'code'       => 'LVPT-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name'       => "{$baseName} Men Hỏa Biến Số {$i}",
                'images'     => $this->copySpecificImages('linh-vat-chi-tiet', array_slice($lvFiles, 0, 3)),
                'price'      => 800000 + ($i * 50000),
                'des'        => $this->generateDescription(),
                'size'       => 'Cao 45cm x Rộng 25cm',
                'size_image' => $this->copySingleImage('linh-vat', 'gtt-size.png'),
                'size_des'   => $this->generateSizeDescription(),
                'is_delete'  => 0,
            ]);
        }
    }
}