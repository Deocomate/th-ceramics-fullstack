<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\DenGomSu;
use App\Models\DenGomSuAnh;
use App\Models\DenVuonGomSuCt;
use App\Models\PhanLoaiDenVuonGomSuCt;

class DenGomSuSeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DenGomSu::truncate();
        DenGomSuAnh::truncate();
        DenVuonGomSuCt::truncate();
        PhanLoaiDenVuonGomSuCt::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $parent = DenGomSu::create([
            'thumbnail_main' => $this->generateSingleImage('den-gom', 'main-banner.jpg'),
            'video'          => $this->generateVideoLink(),
            'image1'         => $this->generateSingleImage('den-gom', 'thumb-1.jpg'),
            'image2'         => $this->generateSingleImage('den-gom', 'thumb-2.jpg'),
            'title2'         => 'Đèn gốm cao cấp',
            'image3'         => $this->generateSingleImage('den-gom', 'thumb-3.jpg'),
            'title3'         => 'Sản phẩm tiêu biểu',
            'image4'         => $this->generateSingleImage('den-gom', 'thumb-4.jpg'),
        ]);

        for ($i = 1; $i <= 15; $i++) {
            DenGomSuAnh::create([
                'image'         => $this->generateSingleImage('den-gom-gallery', "gallery-{$i}.jpg"),
                'den_gom_su_id' => $parent->den_gom_su_id,
            ]);
        }

        $shapes = ['Lục Giác', 'Hình Tháp Chùa', 'Kiểu Nhật', 'Quả Nhót', 'Lồng Chim'];

        for ($i = 1; $i <= 15; $i++) {
            $shapeName = $shapes[$i % 5];
            $product = DenVuonGomSuCt::create([
                'name'       => "Đèn Vườn Gốm Sứ Kiểu {$shapeName} - Mẫu Số {$i}",
                // GỌI HÀM RANDOM Ở ĐÂY:
                'images'     => $this->generateRandomGallery('den-gom-chi-tiet', 30, 10),
                'des'        => $this->generateDescription(),
                'size'       => 'H500 x D200 mm',
                'size_image' => $this->generateSingleImage('den-gom', 'size-guide.jpg'),
                'size_des'   => $this->generateSizeDescription(),
                'is_delete'  => 0,
            ]);

            $pid = str_pad($product->den_vuon_gom_su_ct_id, 3, '0', STR_PAD_LEFT);
            
            PhanLoaiDenVuonGomSuCt::create([
                'name'                  => "Cỡ Vừa (H400) - {$shapeName} (#{$pid})",
                'code'                  => "DGS-{$pid}-M",
                'price'                 => 350000 + ($i * 10000),
                'den_vuon_gom_su_ct_id' => $product->den_vuon_gom_su_ct_id,
                'is_delete'             => 0,
            ]);

            PhanLoaiDenVuonGomSuCt::create([
                'name'                  => "Cỡ Lớn (H600) - {$shapeName} (#{$pid})",
                'code'                  => "DGS-{$pid}-L",
                'price'                 => 550000 + ($i * 10000),
                'den_vuon_gom_su_ct_id' => $product->den_vuon_gom_su_ct_id,
                'is_delete'             => 0,
            ]);
        }
    }
}