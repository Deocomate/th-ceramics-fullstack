<?php

namespace Database\Seeders;

use App\Models\DenGomSu;
use App\Models\DenGomSuAnh;
use App\Models\DenVuonGomSuCt;
use App\Models\PhanLoaiDenVuonGomSuCt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'thumbnail_main' => $this->copySingleImage('den-gom', 'den-gom-banner.png'),
            'video' => $this->generateVideoLink(),
            'image1' => $this->copySingleImage('den-gom', 'den-gom-01.png'),
            'image2' => $this->copySingleImage('den-gom', 'den-gom-02.png'),
            'title2' => 'Đèn gốm cao cấp',
            'image3' => $this->copySingleImage('den-gom', 'den-gom-bg.png'),
            'title3' => 'Sản phẩm tiêu biểu',
            'image4' => $this->copySingleImage('den-gom', 'den-gom-01.png'),
        ]);

        $dgFiles = ['den-gom-01.png', 'den-gom-02.png', 'den-gom-bg.png'];

        foreach ($dgFiles as $file) {
            DenGomSuAnh::create([
                'image' => $this->copySingleImage('den-gom-gallery', $file),
                'den_gom_su_id' => $parent->den_gom_su_id,
            ]);
        }

        $shapes = ['Lục Giác', 'Hình Tháp', 'Kiểu Nhật', 'Quả Nhót', 'Lồng Chim'];

        for ($i = 1; $i <= 15; $i++) {
            $shapeName = $shapes[$i % 5];
            shuffle($dgFiles);
            $product = DenVuonGomSuCt::create([
                'name' => "Đèn Vườn Gốm Sứ {$shapeName} - Mẫu {$i}",
                'color' => 'Tự chọn',
                'category_type' => $i % 2 === 0 ? 'den_su' : 'den_gom',
                'images' => $this->copySpecificImages('den-gom-chi-tiet', $dgFiles),
                'des' => $this->generateDescription(),
                'size' => 'H500 x D200 mm',
                'size_image' => $this->copySingleImage('den-gom', 'ngoi-hai-size.png'),
                'size_des' => $this->generateSizeDescription(),
                'is_delete' => 0,
            ]);

            $pid = str_pad($product->den_vuon_gom_su_ct_id, 3, '0', STR_PAD_LEFT);
            PhanLoaiDenVuonGomSuCt::create(['name' => "Cỡ Vừa (H400) (#{$pid})", 'code' => "DGS-{$pid}-M", 'price' => 350000 + ($i * 10000), 'den_vuon_gom_su_ct_id' => $product->den_vuon_gom_su_ct_id, 'is_delete' => 0]);
            PhanLoaiDenVuonGomSuCt::create(['name' => "Cỡ Lớn (H600) (#{$pid})", 'code' => "DGS-{$pid}-L", 'price' => 550000 + ($i * 10000), 'den_vuon_gom_su_ct_id' => $product->den_vuon_gom_su_ct_id, 'is_delete' => 0]);
        }
    }
}
