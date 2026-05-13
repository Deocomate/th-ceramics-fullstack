<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LanCanGomXu;
use App\Models\LanCanGomSuCt;
use App\Models\PhanLoaiLanCanGomSuCt;

class LanCanGomSuSeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        LanCanGomXu::truncate();
        LanCanGomSuCt::truncate();
        PhanLoaiLanCanGomSuCt::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        LanCanGomXu::create([
            'thumbnail_main' => $this->copySingleImage('lan-can', 'lan-can-01.jpg'),
            'video'          => $this->generateVideoLink(),
        ]);

        $patterns = ['Hoa Chanh', 'Lục Bình', 'Chữ Thọ', 'Trúc Lâm', 'Hoa Sen'];
        $lcFiles =['lan-can-01.jpg', 'lan-can-01.png', 'lan-can-02.jpg', 'lan-can-02.png', 'lan-can-03.jpg', 'lan-can-03.png', 'lan-can-04.jpg', 'lan-can-04.png', 'lan-can-05.jpg', 'lan-can-05.png', 'lan-can-06.jpg', 'lan-can-06.png', 'lan-can-07.jpg', 'lan-can-08.jpg', 'lan-can-bau.png', 'lan-can-giot-le.jpg', 'lan-can-related.jpg'];

        for ($i = 1; $i <= 15; $i++) {
            $patternName = $patterns[$i % 5];
            shuffle($lcFiles);

            $product = LanCanGomSuCt::create([
                'name'       => "Lan Can Gốm Sứ {$patternName} - Bản {$i}",
                'images'     => $this->copySpecificImages('lan-can-chi-tiet', array_slice($lcFiles, 0, 6)),
                'des'        => $this->generateDescription(),
                'size'       => 'L400 x W150 x H500 mm',
                'size_image' => $this->copySingleImage('lan-can', 'ngoi-am-duong-size.png'),
                'size_des'   => $this->generateSizeDescription(),
                'is_delete'  => 0,
            ]);

            $pid = str_pad($product->lan_can_gom_su_ct_id, 3, '0', STR_PAD_LEFT);
            PhanLoaiLanCanGomSuCt::create(['name' => "Men Xanh Lục - {$patternName} (#{$pid})", 'code' => "LC-{$pid}-XL", 'price' => 250000 + ($i * 5000), 'lan_can_gom_su_ct_id' => $product->lan_can_gom_su_ct_id, 'is_delete' => 0]);
            PhanLoaiLanCanGomSuCt::create(['name' => "Men Trắng Sứ - {$patternName} (#{$pid})", 'code' => "LC-{$pid}-TS", 'price' => 280000 + ($i * 5000), 'lan_can_gom_su_ct_id' => $product->lan_can_gom_su_ct_id, 'is_delete' => 0]);
        }
    }
}