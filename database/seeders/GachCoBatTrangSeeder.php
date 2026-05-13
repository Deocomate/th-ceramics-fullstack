<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GachCoBatTrang;
use App\Models\GachCoBatTrangAnh;
use App\Models\GachCoBatTrangCt;

class GachCoBatTrangSeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        GachCoBatTrang::truncate();
        GachCoBatTrangAnh::truncate();
        GachCoBatTrangCt::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $parent = GachCoBatTrang::create([
            'thumbnail_main' => $this->copySingleImage('gach-co-bat-trang', 'gach-co-banner.png'),
            'video'          => $this->generateVideoLink(),
            'images'         => $this->copySpecificImages('gach-co-bat-trang-cong-doan', ['gach-co-work-1.jpg', 'gach-co-work-2.jpg']),
            'section_bat'    =>[
                'title'       => 'Sản Phẩm Gạch Bát — Tinh Hoa Gốm Mộc',
                'description' => 'Gạch bát được tạo hình thủ công từ đất sét nguyên chất, nung 1.200°C. Bề mặt hoài cổ phù hợp Indochine.',
                'colors'      => ['#A98467', '#B22222', '#5D5FEF'],
                'gallery'     => $this->copySpecificImages('gach-co-bat-trang-sec', ['gach-bat-01.jpg', 'gach-bat-02.jpg', 'gach-bat-detail-1.png', 'gach-bat-detail-2.png']),
            ],
            'section_that'   =>[
                'title'       => 'Gạch Thất & Xây — Nghệ Thuật Đất Nung',
                'description' => 'Khối gạch vuông vắn là kết quả của tạo hình thủ công truyền qua nhiều thế hệ. Phân tán lực tốt, xây tường vững chắc.',
                'colors'      => ['#A98467', '#B22222', '#5D5FEF'],
                'gallery'     => $this->copySpecificImages('gach-co-bat-trang-sec', ['gach-that-01.jpg', 'gach-co-work-1.jpg']),
            ],
            'section_the'    =>[
                'title'       => 'Gạch Thẻ — Linh Hoạt Thiết Kế',
                'description' => 'Gạch thẻ mỏng, dẹt mang đến sự linh hoạt thi công ốp vách. Độ dày 1.5-2.5cm, đa dạng màu sắc.',
                'colors'      => ['#A98467', '#B22222', '#5D5FEF'],
                'gallery'     => $this->copySpecificImages('gach-co-bat-trang-sec', ['gach-the-01.jpg', 'gach-co-work-2.jpg']),
            ],
        ]);

        $files = ['gach-bat-01.jpg', 'gach-bat-02.jpg', 'gach-bat-detail-1.png', 'gach-bat-detail-2.png', 'gach-that-01.jpg', 'gach-the-01.jpg', 'gach-co-work-1.jpg', 'gach-co-work-2.jpg'];

        foreach ($files as $file) {
            GachCoBatTrangAnh::create([
                'image'                => $this->copySingleImage('gach-co-bat-trang-gallery', $file),
                'gach_co_bat_trang_id' => $parent->gach_co_bat_trang_id,
            ]);
        }

        $categories = ['bat', 'that', 'the'];
        $catNames = ['bat' => 'Gạch Bát', 'that' => 'Gạch Thất', 'the' => 'Gạch Thẻ'];

        for ($i = 1; $i <= 20; $i++) {
            $cat = $categories[$i % 3];
            shuffle($files);

            GachCoBatTrangCt::create([
                'code'          => 'GCB-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name'          => "{$catNames[$cat]} Mộc Không Men - Bản {$i}",
                'category_type' => $cat,
                'images'        => $this->copySpecificImages('gach-co-bat-trang-chi-tiet', array_slice($files, 0, 5)),
                'price'         => 15000 + ($i * 800),
                'des'           => $this->generateDescription(),
                'size'          => match($cat) { 'bat' => '10x20x5 cm', 'that' => '8x18x4 cm', 'the' => '5x20x2.5 cm' },
                'dinh_muc'      => match($cat) { 'bat' => '50 viên/m²', 'that' => '55 viên/m²', 'the' => '70 viên/m²' },
                'weight'        => match($cat) { 'bat' => '1.5 kg', 'that' => '1.2 kg', 'the' => '0.8 kg' },
                'size_image'    => $this->copySingleImage('gach-co-bat-trang', 'gach-bat-size-1.png'),
                'is_delete'     => 0,
            ]);
        }
    }
}