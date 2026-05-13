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
            'thumbnail_main' => $this->generateSingleImage('lan-can', 'main-banner.jpg'),
            'video'          => $this->generateVideoLink(),
        ]);

        $patterns = ['Hoa Chanh', 'Lục Bình', 'Chữ Thọ', 'Trúc Lâm', 'Hoa Sen'];

        for ($i = 1; $i <= 15; $i++) {
            $patternName = $patterns[$i % 5];

            $product = LanCanGomSuCt::create([
                'name'       => "Lan Can Gốm Sứ Họa Tiết {$patternName} - Bản Số {$i}",
                // GỌI HÀM RANDOM Ở ĐÂY:
                'images'     => $this->generateRandomGallery('lan-can-chi-tiet', 30, 10),
                'des'        => $this->generateDescription(),
                'size'       => 'L400 x W150 x H500 mm',
                'size_image' => $this->generateSingleImage('lan-can', 'size-guide.jpg'),
                'size_des'   => $this->generateSizeDescription(),
                'is_delete'  => 0,
            ]);

            $pid = str_pad($product->lan_can_gom_su_ct_id, 3, '0', STR_PAD_LEFT);
            
            PhanLoaiLanCanGomSuCt::create([
                'name'                 => "Men Xanh Lục - {$patternName} (#{$pid})",
                'code'                 => "LC-{$pid}-XL",
                'price'                => 250000 + ($i * 5000),
                'lan_can_gom_su_ct_id' => $product->lan_can_gom_su_ct_id,
                'is_delete'            => 0,
            ]);

            PhanLoaiLanCanGomSuCt::create([
                'name'                 => "Men Trắng Sứ - {$patternName} (#{$pid})",
                'code'                 => "LC-{$pid}-TS",
                'price'                => 280000 + ($i * 5000),
                'lan_can_gom_su_ct_id' => $product->lan_can_gom_su_ct_id,
                'is_delete'            => 0,
            ]);

            PhanLoaiLanCanGomSuCt::create([
                'name'                 => "Đất Nung - {$patternName} (#{$pid})",
                'code'                 => "LC-{$pid}-DN",
                'price'                => 180000 + ($i * 5000),
                'lan_can_gom_su_ct_id' => $product->lan_can_gom_su_ct_id,
                'is_delete'            => 0,
            ]);
        }
    }
}