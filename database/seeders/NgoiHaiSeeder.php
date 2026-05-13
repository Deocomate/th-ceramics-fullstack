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
            'thumbnail_main' => $this->generateSingleImage('ngoi-hai', 'main-banner.jpg'),
            'title1'         => 'Tinh Hoa Kiến Trúc Cổ Việt Nam', 
            'thumbnail1'     => $this->generateSingleImage('ngoi-hai', 'thumb-1.jpg'),
            'title2'         => 'Ngói Hài Cổ - Nét Mềm Cung Đình', 
            'thumbnail2'     => $this->generateSingleImage('ngoi-hai', 'thumb-2.jpg'),
            'title3'         => 'Ngói Văn Miếu - Dáng Uy Nghi', 
            'thumbnail3'     => $this->generateSingleImage('ngoi-hai', 'thumb-3.jpg'),
            'video'          => $this->generateVideoLink(),
            'images'         => $this->generateGallery('ngoi-hai-cong-doan', 15), 
        ]);

        $baseColors = [
            ['name' => 'Men Xanh Lục',        'price_offset' => 22000],
            ['name' => 'Men Vàng',            'price_offset' => 25000],
            ['name' => 'Men Đỏ Nâu',          'price_offset' => 20000],
            ['name' => 'Men Xanh Ngọc',       'price_offset' => 24000],
            ['name' => 'Đất Nung Tự Nhiên',   'price_offset' => 16000],
        ];

        // Ngói Hài Cổ CT (15 records)
        $coDescriptions =[
            'Ngói Hài Cổ Thanh Hải tái hiện nguyên mẫu viên ngói hài cung đình triều Nguyễn với phần mũi hài bo tròn đặc trưng.',
            'Sản phẩm được chế tác hoàn toàn thủ công từ nguồn đất sét Bát Tràng trứ danh, ủ kỹ trong 6 tháng để đạt độ dẻo tiêu chuẩn.',
            'Quá trình nung luyện khắc nghiệt ở nhiệt độ 1.250 độ C trong lò tuynel suốt 72 giờ giúp đất sét hóa sành, tạo nên cốt gốm cực kỳ đanh chắc.',
            'Bên cạnh độ bền vượt trội, ngói Hài Cổ còn là lựa chọn hoàn hảo cho các công trình mang phong cách Indochine, nhà vườn truyền thống.',
        ];

        for ($i = 1; $i <= 15; $i++) {
            $productCo = NgoiHaiCoCt::create([
                'name'       => "Ngói Hài Cổ Phục Chế Cung Đình - Bản Số {$i}",
                // GỌI HÀM RANDOM Ở ĐÂY CHO MỖI SẢN PHẨM:
                'images'     => $this->generateRandomGallery('ngoi-hai-co-chi-tiet', 30, 10),
                'des'        => $coDescriptions,
                'size'       => '260mm x 160mm (Tiêu chuẩn)',
                'size_image' => $this->generateSingleImage('ngoi-hai', "size-guide-co.jpg"),
                'is_delete'  => 0,
            ]);

            foreach ($baseColors as $j => $color) {
                MauSacNgoiHaiCoCt::create([
                    'name'              => $color['name'],
                    'image'             => $this->generateSingleImage('ngoi-hai-colors', "color-{$j}.jpg"),
                    'code'              => 'NHC-' . str_pad($productCo->ngoi_hai_co_ct_id, 3, '0', STR_PAD_LEFT) . '-C' . ($j + 1),
                    'price'             => $color['price_offset'] + ($i * 500),
                    'ngoi_hai_co_ct_id' => $productCo->ngoi_hai_co_ct_id,
                    'is_delete'         => 0,
                ]);
            }
        }

        // Ngói Văn Miếu CT (15 records)
        $vmDescriptions =[
            'Ngói Hài Văn Miếu được thiết kế với phần mũi vuốt nhọn sắc nét, lấy cảm hứng từ kiến trúc mái đình, chùa cổ kính.',
            'Sản phẩm sử dụng nguồn đất sét cao cấp từ Bát Tràng, trải qua nhiều công đoạn lọc tạp chất và nhào nặn công phu.',
            'Lớp men hỏa biến bao phủ bề mặt ngói được nung ở nhiệt độ cao 1.300 độ C, tạo thành một màng thủy tinh siêu cứng.',
            'Sản phẩm đặc biệt phù hợp với các công trình kiến trúc tâm linh, nhà thờ họ, đài tưởng niệm hay các không gian văn hóa.',
        ];

        for ($i = 1; $i <= 15; $i++) {
            $productVm = NgoiHaiVanMieuCt::create([
                'name'       => "Ngói Hài Văn Miếu Phục Chế Cổ - Bản Số {$i}",
                // GỌI HÀM RANDOM Ở ĐÂY CHO MỖI SẢN PHẨM:
                'images'     => $this->generateRandomGallery('ngoi-hai-vm-chi-tiet', 30, 10),
                'price'      => 0,
                'des'        => $vmDescriptions,
                'mau_sac_id' => 0, 
                'size'       => '260mm x 160mm (Tiêu chuẩn)',
                'size_image' => $this->generateSingleImage('ngoi-hai', "size-guide-vm.jpg"),
                'is_delete'  => 0,
            ]);

            foreach ($baseColors as $j => $color) {
                MauSacNgoiHaiVanMieuCt::create([
                    'name'                    => $color['name'],
                    'image'                   => $this->generateSingleImage('ngoi-hai-colors', "color-{$j}.jpg"),
                    'code'                    => 'NHVM-' . str_pad($productVm->ngoi_hai_van_mieu_ct_id, 3, '0', STR_PAD_LEFT) . '-C' . ($j + 1),
                    'price'                   => $color['price_offset'] + ($i * 500),
                    'ngoi_hai_van_mieu_ct_id' => $productVm->ngoi_hai_van_mieu_ct_id,
                    'is_delete'               => 0,
                ]);
            }
        }
    }
}