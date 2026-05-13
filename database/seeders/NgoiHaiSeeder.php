<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\NgoiHaiVanMieu;
use App\Models\NgoiHaiCoCt;
use App\Models\MauSacNgoiHaiCoCt;
use App\Models\NgoiHaiVanMieuCt;
use App\Models\MauSacNgoiHaiVanMieuCt;

class NgoiHaiSeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        NgoiHaiVanMieu::truncate();
        NgoiHaiCoCt::truncate();
        MauSacNgoiHaiCoCt::truncate();
        NgoiHaiVanMieuCt::truncate();
        MauSacNgoiHaiVanMieuCt::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        NgoiHaiVanMieu::create([
            'thumbnail_main' => $this->copySingleImage('ngoi-hai', 'ngoi-hai-van-mieu-banner.jpg'),
            'title1'         => 'Tinh Hoa Kiến Trúc Cổ Việt Nam', 
            'thumbnail1'     => $this->copySingleImage('ngoi-hai', 'ngoi-hai-01.png'),
            'title2'         => 'Ngói Hài Cổ - Nét Mềm Cung Đình', 
            'thumbnail2'     => $this->copySingleImage('ngoi-hai', 'ngoi-hai-02.png'),
            'title3'         => 'Ngói Văn Miếu - Dáng Uy Nghi', 
            'thumbnail3'     => $this->copySingleImage('ngoi-hai', 'ngoi-hai-03.png'),
            'video'          => $this->generateVideoLink(),
            'images'         => $this->copySpecificImages('ngoi-hai-cong-doan', ['ngoi-hai-01.png', 'ngoi-hai-02.png', 'ngoi-hai-03.png', 'cong-doan-01.jpg', 'cong-doan-02.jpg']), 
        ]);

        $baseColors = [
            ['name' => 'Men Xanh Lục', 'file' => 'ngoi-01.jpg', 'price_offset' => 22000],
            ['name' => 'Men Vàng',     'file' => 'ngoi-03.jpg', 'price_offset' => 25000],
            ['name' => 'Men Đỏ Nâu',   'file' => 'ngoi-04.jpg', 'price_offset' => 20000],
            ['name' => 'Men Xanh Ngọc', 'file' => 'ngoi-02.png', 'price_offset' => 24000],
            ['name' => 'Đất Nung Tự Nhiên', 'file' => 'ngoi-05.jpg', 'price_offset' => 16000],
        ];

        // 1. Ngói Hài Cổ CT (15 records)
        $haiCoFiles = ['ngoi-hai-01.png', 'ngoi-hai-02.png', 'ngoi-hai-03.png', 'ngoi-hai-detail.png'];
        $coDescriptions =['Ngói Hài Cổ Thanh Hải tái hiện nguyên mẫu viên ngói hài cung đình triều Nguyễn với phần mũi hài bo tròn.', 'Sản phẩm được chế tác hoàn toàn thủ công từ nguồn đất sét Bát Tràng trứ danh, ủ kỹ trong 6 tháng.', 'Quá trình nung luyện khắc nghiệt ở nhiệt độ 1.250 độ C trong lò tuynel suốt 72 giờ giúp đất sét hóa sành.', 'Bên cạnh độ bền vượt trội, ngói Hài Cổ còn là lựa chọn hoàn hảo cho các công trình mang phong cách Indochine.'];

        for ($i = 1; $i <= 15; $i++) {
            shuffle($haiCoFiles);
            $productCo = NgoiHaiCoCt::create([
                'name'       => "Ngói Hài Cổ Phục Chế Cung Đình - Bản Số {$i}",
                'images'     => $this->copySpecificImages('ngoi-hai-co-chi-tiet', $haiCoFiles),
                'des'        => $coDescriptions,
                'size'       => '260mm x 160mm (Tiêu chuẩn)',
                'size_image' => $this->copySingleImage('ngoi-hai', "ngoi-hai-size.png"),
                'is_delete'  => 0,
            ]);

            foreach ($baseColors as $j => $color) {
                MauSacNgoiHaiCoCt::create([
                    'name'              => $color['name'],
                    'image'             => $this->copySingleImage('ngoi-hai-colors', $color['file']),
                    'code'              => 'NHC-' . str_pad($productCo->ngoi_hai_co_ct_id, 3, '0', STR_PAD_LEFT) . '-C' . ($j + 1),
                    'price'             => $color['price_offset'] + ($i * 500),
                    'ngoi_hai_co_ct_id' => $productCo->ngoi_hai_co_ct_id,
                    'is_delete'         => 0,
                ]);
            }
        }

        // 2. Ngói Văn Miếu CT (15 records)
        $vanMieuFiles = ['ngoi-van-mieu-detail.png', 'ngoi-van-mieu-detail-2.png', 'ngoi-hai-01.png'];
        $vmDescriptions =['Ngói Hài Văn Miếu được thiết kế với phần mũi vuốt nhọn sắc nét, lấy cảm hứng từ kiến trúc mái đình chùa Bắc Bộ.', 'Tương tự như ngói hài cổ, ngói Văn Miếu sử dụng nguồn đất sét cao cấp từ Bát Tràng.', 'Lớp men hỏa biến bao phủ bề mặt ngói được nung ở nhiệt độ cao 1.300 độ C, tạo màng thủy tinh siêu cứng.', 'Sản phẩm đặc biệt phù hợp với các công trình kiến trúc tâm linh, nhà thờ họ.'];

        for ($i = 1; $i <= 15; $i++) {
            shuffle($vanMieuFiles);
            $productVm = NgoiHaiVanMieuCt::create([
                'name'       => "Ngói Hài Văn Miếu Phục Chế Cổ - Bản Số {$i}",
                'images'     => $this->copySpecificImages('ngoi-hai-vm-chi-tiet', $vanMieuFiles),
                'price'      => 0,
                'des'        => $vmDescriptions,
                'mau_sac_id' => 0, 
                'size'       => '260mm x 160mm (Tiêu chuẩn)',
                'size_image' => $this->copySingleImage('ngoi-hai', "ngoi-hai-size.png"),
                'is_delete'  => 0,
            ]);

            foreach ($baseColors as $j => $color) {
                MauSacNgoiHaiVanMieuCt::create([
                    'name'                    => $color['name'],
                    'image'                   => $this->copySingleImage('ngoi-hai-colors', $color['file']),
                    'code'                    => 'NHVM-' . str_pad($productVm->ngoi_hai_van_mieu_ct_id, 3, '0', STR_PAD_LEFT) . '-C' . ($j + 1),
                    'price'                   => $color['price_offset'] + ($i * 500),
                    'ngoi_hai_van_mieu_ct_id' => $productVm->ngoi_hai_van_mieu_ct_id,
                    'is_delete'               => 0,
                ]);
            }
        }
    }
}