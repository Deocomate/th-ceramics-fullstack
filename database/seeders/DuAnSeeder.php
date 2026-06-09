<?php

namespace Database\Seeders;

use App\Models\DanhMucDuAn;
use App\Models\DuAn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DuAnSeeder extends Seeder
{
    public function run(): void
    {
        if (File::exists(public_path('assets/images'))) {
            File::copyDirectory(public_path('assets/images'), storage_path('app/public/assets/images'));
        }

        $categories = $this->seedCategories();
        $this->seedProjects($categories);
    }

    private function seedCategories(): array
    {
        $names = [
            'Ngói Âm Dương',
            'Ngói Hài - Văn Miếu',
            'Gạch Thông Gió',
            'Gạch Trang Trí',
            'Sản phẩm khác',
        ];

        $map = [];
        foreach ($names as $name) {
            $cat = DanhMucDuAn::firstOrCreate(
                ['ten_danh_muc' => $name],
                ['is_delete' => 0]
            );
            $map[$name] = $cat->danh_muc_du_an_id;
        }

        return $map;
    }

    private function seedProjects(array $categories): void
    {
        $allImages = [
            'factory-01.jpg', 'factory-02.png', 'factory-03.png', 'factory-04.jpg',
            'trang-tri-slide-01.jpg', 'trang-tri-slide-02.jpg', 'trang-tri-slide-03.jpg', 'trang-tri-slide-04.jpg',
            'gach-co-work-1.jpg', 'gach-co-work-2.jpg',
        ];

        $projects = [
            // Ngói Âm Dương
            [
                'ten_du_an' => 'Chùa Bái Đính',
                'dia_diem' => 'Ninh Bình',
                'san_pham' => 'Ngói âm dương men nâu đen, ngói bò nóc men vàng',
                'nam' => 2020,
                'danh_muc' => 'Ngói Âm Dương',
            ],
            [
                'ten_du_an' => 'Chùa Tam Chúc',
                'dia_diem' => 'Hà Nam',
                'san_pham' => 'Ngói âm dương men đỏ cổ, ngói vảy cá',
                'nam' => 2021,
                'danh_muc' => 'Ngói Âm Dương',
            ],
            [
                'ten_du_an' => 'Đền Trần',
                'dia_diem' => 'Nam Định',
                'san_pham' => 'Ngói âm dương men xanh rêu, bộ nóc chữ văn',
                'nam' => 2019,
                'danh_muc' => 'Ngói Âm Dương',
            ],
            [
                'ten_du_an' => 'Nhà thờ Đá Phát Diệm',
                'dia_diem' => 'Ninh Bình',
                'san_pham' => 'Ngói âm dương men nâu trầm, ngói hài cổ',
                'nam' => 2022,
                'danh_muc' => 'Ngói Âm Dương',
            ],

            // Ngói Hài - Văn Miếu
            [
                'ten_du_an' => 'Thiền Viện Trúc Lâm',
                'dia_diem' => 'Đà Lạt',
                'san_pham' => 'Ngói hài cổ men xanh ngọc, ngói văn miếu men vàng',
                'nam' => 2020,
                'danh_muc' => 'Ngói Hài - Văn Miếu',
            ],
            [
                'ten_du_an' => 'Chùa Bà Đanh',
                'dia_diem' => 'Hà Nam',
                'san_pham' => 'Ngói văn miếu men nâu đỏ, ngói hài men xanh lam',
                'nam' => 2021,
                'danh_muc' => 'Ngói Hài - Văn Miếu',
            ],
            [
                'ten_du_an' => 'Chùa Một Cột',
                'dia_diem' => 'Hà Nội',
                'san_pham' => 'Ngói hài cổ men nâu, ngói âm dương men đỏ',
                'nam' => 2022,
                'danh_muc' => 'Ngói Hài - Văn Miếu',
            ],
            [
                'ten_du_an' => 'Đình Bảng',
                'dia_diem' => 'Bắc Ninh',
                'san_pham' => 'Ngói văn miếu men vàng hoàng gia, ngói bò nóc',
                'nam' => 2021,
                'danh_muc' => 'Ngói Hài - Văn Miếu',
            ],

            // Gạch Thông Gió
            [
                'ten_du_an' => 'Chung cư EcoLife',
                'dia_diem' => 'Hà Nội',
                'san_pham' => 'Gạch hoa thông gió 20x20cm men nâu cánh gián',
                'nam' => 2023,
                'danh_muc' => 'Gạch Thông Gió',
            ],
            [
                'ten_du_an' => 'Biệt thự Vinhomes Riverside',
                'dia_diem' => 'Hà Nội',
                'san_pham' => 'Gạch thông gió hoa sen 30x30cm men xanh rêu',
                'nam' => 2022,
                'danh_muc' => 'Gạch Thông Gió',
            ],
            [
                'ten_du_an' => 'Resort Furama',
                'dia_diem' => 'Đà Nẵng',
                'san_pham' => 'Gạch thông gió họa tiết nhiệt đới 20x30cm men trắng ngà',
                'nam' => 2023,
                'danh_muc' => 'Gạch Thông Gió',
            ],
            [
                'ten_du_an' => 'Khách sạn Mường Thanh',
                'dia_diem' => 'Nghệ An',
                'san_pham' => 'Gạch thông gió cổ điển 15x15cm men nâu đen',
                'nam' => 2022,
                'danh_muc' => 'Gạch Thông Gió',
            ],

            // Gạch Trang Trí
            [
                'ten_du_an' => 'Nhà hàng ẩm thực Huế',
                'dia_diem' => 'Huế',
                'san_pham' => 'Gạch trang trí hoa văn cung đình men xanh cobalt',
                'nam' => 2023,
                'danh_muc' => 'Gạch Trang Trí',
            ],
            [
                'ten_du_an' => 'Quán cà phê The Workshop',
                'dia_diem' => 'TP.HCM',
                'san_pham' => 'Gạch trang trí men hỏa biến công nghiệp, ốp tường',
                'nam' => 2022,
                'danh_muc' => 'Gạch Trang Trí',
            ],
            [
                'ten_du_an' => 'Khu nghỉ dưỡng Flamingo',
                'dia_diem' => 'Vĩnh Phúc',
                'san_pham' => 'Gạch trang trí men xanh lá, họa tiết lá nhiệt đới',
                'nam' => 2023,
                'danh_muc' => 'Gạch Trang Trí',
            ],
            [
                'ten_du_an' => 'Nhà cổ Bình Thủy',
                'dia_diem' => 'Cần Thơ',
                'san_pham' => 'Gạch trang trí hoa văn cổ Nam Bộ men nâu đỏ',
                'nam' => 2021,
                'danh_muc' => 'Gạch Trang Trí',
            ],

            // Sản phẩm khác
            [
                'ten_du_an' => 'Lăng Chủ tịch Hồ Chí Minh',
                'dia_diem' => 'Hà Nội',
                'san_pham' => 'Lan can gốm sứ men nâu, gạch cổ Bát Tràng ốp tường',
                'nam' => 2019,
                'danh_muc' => 'Sản phẩm khác',
            ],
            [
                'ten_du_an' => 'Chùa Cầu',
                'dia_diem' => 'Hội An',
                'san_pham' => 'Linh vật phong thủy, đèn gốm sứ trang trí cổng',
                'nam' => 2021,
                'danh_muc' => 'Sản phẩm khác',
            ],
            [
                'ten_du_an' => 'Tháp Po Nagar',
                'dia_diem' => 'Nha Trang',
                'san_pham' => 'Gạch cổ Bát Tràng ốp tháp, phụ kiện ngói men vàng Chăm',
                'nam' => 2022,
                'danh_muc' => 'Sản phẩm khác',
            ],
            [
                'ten_du_an' => 'Chùa Tây Phương',
                'dia_diem' => 'Hà Nội',
                'san_pham' => 'Ngói âm dương men nâu sẫm, lan can gốm sứ cổ điển',
                'nam' => 2020,
                'danh_muc' => 'Sản phẩm khác',
            ],
        ];

        $usedSlugs = [];

        foreach ($projects as $idx => $p) {
            $slug = Str::slug($p['ten_du_an']);

            // Ensure unique slug
            if (isset($usedSlugs[$slug])) {
                $usedSlugs[$slug]++;
                $slug = $slug.'-'.$usedSlugs[$slug];
            } else {
                $usedSlugs[$slug] = 1;
            }

            // Pick 3 images deterministically per project
            $imgIdx = $idx * 3;
            $images = [
                'assets/images/'.$allImages[$imgIdx % count($allImages)],
                'assets/images/'.$allImages[($imgIdx + 1) % count($allImages)],
                'assets/images/'.$allImages[($imgIdx + 2) % count($allImages)],
            ];

            DuAn::firstOrCreate(
                ['slug' => $slug],
                [
                    'ten_du_an' => $p['ten_du_an'],
                    'dia_diem' => $p['dia_diem'],
                    'san_pham' => $p['san_pham'],
                    'nam' => $p['nam'],
                    'images' => $images,
                    'danh_muc_du_an_id' => $categories[$p['danh_muc']],
                ]
            );
        }
    }
}
