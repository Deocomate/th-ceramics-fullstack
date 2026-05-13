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
            'thumbnail_main' => $this->generateSingleImage('gach-co-bat-trang', 'main-banner.jpg'),
            'video'          => $this->generateVideoLink(),
            'images'         => $this->generateGallery('gach-co-bat-trang-cong-doan', 15),
            'section_bat'    =>[
                'title'       => 'Sản Phẩm Gạch Bát — Tinh Hoa Gốm Mộc Bát Tràng Trứ Danh',
                'description' => 'Gạch bát được tạo hình thủ công từ đất sét nguyên chất, giữ nguyên màu mộc tự nhiên sau khi nung ở nhiệt độ 1.200 độ C. Bề mặt thô ráp đặc trưng mang đến cảm giác hoài cổ, ấm cúng, phù hợp với các công trình kiến trúc Indochine, Rustic và nhà truyền thống.',
                'colors'      => ['#A98467', '#B22222', '#5D5FEF'],
                'gallery'     => $this->generateGallery('gach-co-bat-trang-sec-bat', 6),
            ],
            'section_that'   =>[
                'title'       => 'Sản Phẩm Gạch Thất & Xây — Nghệ Thuật Đất Nung Kháng Nước',
                'description' => 'Khối gạch vuông vắn là kết quả của kỹ thuật tạo hình thủ công tinh xảo truyền qua nhiều thế hệ. Hình dáng độc đáo này phân tán lực cực tốt, giúp tường xây vững chắc. Gạch thường được dùng trong biệt thự cổ, nhà vườn và nhà hàng cao cấp.',
                'colors'      => ['#A98467', '#B22222', '#5D5FEF'],
                'gallery'     => $this->generateGallery('gach-co-bat-trang-sec-that', 6),
            ],
            'section_the'    =>[
                'title'       => 'Sản Phẩm Gạch Thẻ — Sự Linh Hoạt Trong Thiết Kế',
                'description' => 'Gạch thẻ với thiết kế mỏng, dẹt mang đến sự linh hoạt tối đa trong thi công. Với độ dày chỉ từ 1.5-2.5cm, gạch thẻ đặc biệt phù hợp cho các hạng mục ốp vách nội và ngoại thất.',
                'colors'      => ['#A98467', '#B22222', '#5D5FEF'],
                'gallery'     => $this->generateGallery('gach-co-bat-trang-sec-the', 6),
            ],
        ]);

        for ($i = 1; $i <= 15; $i++) {
            GachCoBatTrangAnh::create([
                'image'                => $this->generateSingleImage('gach-co-bat-trang-gallery', "gallery-{$i}.jpg"),
                'gach_co_bat_trang_id' => $parent->gach_co_bat_trang_id,
            ]);
        }

        $categories = ['bat', 'that', 'the'];
        $catNames = ['bat' => 'Gạch Bát', 'that' => 'Gạch Thất', 'the' => 'Gạch Thẻ'];

        for ($i = 1; $i <= 20; $i++) {
            $cat = $categories[$i % 3];

            GachCoBatTrangCt::create([
                'code'          => 'GCB-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name'          => "{$catNames[$cat]} Bát Tràng Thủ Công Không Men - Bản Số {$i}",
                'category_type' => $cat,
                // GỌI HÀM RANDOM Ở ĐÂY:
                'images'        => $this->generateRandomGallery('gach-co-bat-trang-chi-tiet', 30, 10),
                'price'         => 15000 + ($i * 800),
                'des'           => $this->generateDescription(),
                'size'          => match($cat) {
                    'bat'  => '10 x 20 x 5 cm',
                    'that' => '8 x 18 x 4 cm',
                    'the'  => '5 x 20 x 2.5 cm',
                },
                'dinh_muc'      => match($cat) {
                    'bat'  => '50 viên/m² (Xây tường)',
                    'that' => '55 viên/m² (Xây tường)',
                    'the'  => '70 viên/m² (Ốp vách)',
                },
                'weight'        => match($cat) {
                    'bat'  => '1.5 kg/viên',
                    'that' => '1.2 kg/viên',
                    'the'  => '0.8 kg/viên',
                },
                'size_image'    => $this->generateSingleImage('gach-co-bat-trang', 'size-guide.jpg'),
                'is_delete'     => 0,
            ]);
        }
    }
}