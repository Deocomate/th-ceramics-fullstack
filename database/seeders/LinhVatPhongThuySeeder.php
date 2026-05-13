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
            'thumbnail_main' => $this->generateSingleImage('linh-vat', 'main-banner.jpg'),
            'video'          => $this->generateVideoLink(),
        ]);

        $linhVats = [
            ['title' => 'Long (Rồng Uy Nghi)', 'desc' => 'Rồng là linh vật quyền lực nhất trong tứ linh, biểu tượng của sức mạnh, quyền uy và vượng khí.'],
            ['title' => 'Lân (Nghê Chấn Thủy)', 'desc' => 'Lân (Nghê) là linh vật thuần Việt, mang ý nghĩa canh giữ bình an, bảo vệ gia chủ khỏi tà khí.'],
            ['title' => 'Phượng (Tái Sinh)', 'desc' => 'Phượng hoàng biểu trưng cho sự thanh cao, trường tồn bất diệt và mang đến điềm lành.'],
        ];

        foreach ($linhVats as $index => $lv) {
            LinhVat::create([
                'title'                  => $lv['title'],
                'image'                  => $this->generateSingleImage('linh-vat', "item-{$index}.jpg"),
                'description'            => $lv['desc'],
                'linh_vat_phong_thuy_id' => $parent->linh_vat_phong_thuy_id,
            ]);
        }

        for ($i = 1; $i <= 15; $i++) {
            LinhVatPhongThuyAnh::create([
                'image'                  => $this->generateSingleImage('linh-vat-gallery', "gallery-{$i}.jpg"),
                'linh_vat_phong_thuy_id' => $parent->linh_vat_phong_thuy_id,
            ]);
        }

        $names = ['Đầu Rồng Bờ Nóc', 'Tượng Nghê Chầu', 'Phượng Hoàng Lửa', 'Cá Chép Hóa Rồng', 'Thiềm Thừ Chiêu Tài'];

        for ($i = 1; $i <= 15; $i++) {
            $baseName = $names[$i % 5];
            LinhVatPhongThuyCt::create([
                'code'       => 'LVPT-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name'       => "{$baseName} Men Hỏa Biến - Tác Phẩm Nghệ Thuật Số {$i}",
                // GỌI HÀM RANDOM Ở ĐÂY:
                'images'     => $this->generateRandomGallery('linh-vat-chi-tiet', 30, 10),
                'price'      => 800000 + ($i * 50000),
                'des'        => $this->generateDescription(),
                'size'       => 'Cao 45cm x Rộng 25cm',
                'size_image' => $this->generateSingleImage('linh-vat', 'size-guide.jpg'),
                'size_des'   => $this->generateSizeDescription(),
                'is_delete'  => 0,
            ]);
        }
    }
}