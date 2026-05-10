<?php

namespace Database\Seeders;

use App\Models\DauAnGachTrangTri;
use App\Models\DenGomSu;
use App\Models\DenGomSuAnh;
use App\Models\GachCoBatTrang;
use App\Models\GachCoBatTrangAnh;
use App\Models\GachHoaThongGio;
use App\Models\GachHoaThongGioAnh;
use App\Models\GachTrangTri;
use App\Models\GiaTriGachHoaThongGio;
use App\Models\LanCanGomXu;
use App\Models\LinhVat;
use App\Models\LinhVatPhongThuy;
use App\Models\LinhVatPhongThuyAnh;
use App\Models\NgoiAmDuong;
use App\Models\NgoiHaiVanMieu;
use App\Models\PhuKienNgoi;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedNgoiAmDuong();
        $this->seedNgoiHaiVanMieu();
        $this->seedGachHoaThongGio();
        $this->seedPhuKienNgoi();
        $this->seedGachTrangTri();
        $this->seedLanCanGomXu();
        $this->seedGachCoBatTrang();
        $this->seedLinhVatPhongThuy();
        $this->seedDenGomSu();
    }

    private function seedNgoiAmDuong(): void
    {
        NgoiAmDuong::firstOrCreate(
            ['ngoi_am_duong_id' => 1],
            [
                'thumbnail_main' => 'assets/images/ngoi-am-duong-banner.jpg',
                'thumbnail1' => 'assets/images/ngoi-am-duong-01.jpg',
                'thumbnail2' => 'assets/images/ngoi-am-duong-02.png',
                'video' => null,
            ]
        );
    }

    private function seedNgoiHaiVanMieu(): void
    {
        NgoiHaiVanMieu::firstOrCreate(
            ['ngoi_hai_van_mieu_id' => 1],
            [
                'thumbnail_main' => 'assets/images/ngoi-hai-van-mieu-banner.jpg',
                'title1' => 'Tinh hoa kiến trúc Việt',
                'thumbnail1' => 'assets/images/ngoi-hai-01.png',
                'title2' => 'Ngói Hài - Nét cong di sản',
                'thumbnail2' => 'assets/images/ngoi-hai-02.png',
                'title3' => 'Ngói Văn Miếu - Uy nghi cổ kính',
                'thumbnail3' => 'assets/images/ngoi-hai-03.png',
                'video' => null,
                'images' => [
                    'assets/images/ngoi-hai-01.png',
                    'assets/images/ngoi-hai-02.png',
                    'assets/images/ngoi-hai-03.png',
                ],
            ]
        );
    }

    private function seedGachHoaThongGio(): void
    {
        $parent = GachHoaThongGio::firstOrCreate(
            ['gach_hoa_thong_gio_id' => 1],
            [
                'image' => 'assets/images/gach-hoa-01.jpg',
                'video' => null,
            ]
        );

        $anhs = [
            ['image' => 'assets/images/gach-hoa-thong-gio-anh-01.jpg'],
            ['image' => 'assets/images/gach-hoa-thong-gio-anh-02.jpg'],
        ];
        foreach ($anhs as $anh) {
            GachHoaThongGioAnh::firstOrCreate(
                ['image' => $anh['image']],
                ['gach_hoa_thong_gio_id' => $parent->gach_hoa_thong_gio_id]
            );
        }

        $giaTris = [
            ['title' => 'Thông gió tự nhiên', 'image' => 'assets/images/gia-tri-01.png', 'background' => 'assets/images/gia-tri-bg-01.jpg', 'desscription' => 'Thiết kế gạch hoa rỗng giúp không khí lưu thông tự nhiên, giảm nhiệt độ công trình hiệu quả.'],
            ['title' => 'Chống nóng hiệu quả', 'image' => 'assets/images/gia-tri-02.png', 'background' => 'assets/images/gia-tri-bg-02.jpg', 'desscription' => 'Đất sét nung 1200°C kết hợp cấu trúc rỗng giúp cách nhiệt vượt trội, tiết kiệm năng lượng làm mát.'],
        ];
        foreach ($giaTris as $gt) {
            GiaTriGachHoaThongGio::firstOrCreate(
                ['title' => $gt['title']],
                [
                    'image' => $gt['image'],
                    'background' => $gt['background'],
                    'desscription' => $gt['desscription'],
                    'gach_hoa_thong_gio_id' => $parent->gach_hoa_thong_gio_id,
                ]
            );
        }
    }

    private function seedPhuKienNgoi(): void
    {
        PhuKienNgoi::firstOrCreate(
            ['phu_kien_ngoi_id' => 1],
            [
                'thumbnail_main' => 'assets/images/pk-banner.png',
                'images' => [],
            ]
        );
    }

    private function seedGachTrangTri(): void
    {
        $parent = GachTrangTri::firstOrCreate(
            ['gach_trang_tri_id' => 1],
            [
                'thumbnail_main' => 'assets/images/gach-trang-tri-banner.png',
                'video' => null,
            ]
        );

        $dauAns = [
            ['title' => 'Đậm nét Á Đông', 'background' => 'assets/images/dau-an-01.png', 'location' => 'Mặt tiền', 'description' => 'Họa tiết gạch trang trí mang đậm dấu ấn văn hóa Á Đông, tái hiện tinh hoa kiến trúc cổ.'],
            ['title' => 'Bền bỉ với thời gian', 'background' => 'assets/images/dau-an-02.png', 'location' => 'Tường rào', 'description' => 'Sản phẩm nung 1200°C từ đất sét Bát Tràng, đảm bảo độ bền vượt thời gian.'],
        ];
        foreach ($dauAns as $da) {
            DauAnGachTrangTri::firstOrCreate(
                ['title' => $da['title']],
                [
                    'background' => $da['background'],
                    'location' => $da['location'],
                    'description' => $da['description'],
                    'gach_trang_tri_id' => $parent->gach_trang_tri_id,
                ]
            );
        }
    }

    private function seedLanCanGomXu(): void
    {
        LanCanGomXu::firstOrCreate(
            ['lan_can_gom_xu_id' => 1],
            [
                'thumbnail_main' => 'assets/images/lan-can-01.jpg',
                'video' => null,
            ]
        );
    }

    private function seedGachCoBatTrang(): void
    {
        $parent = GachCoBatTrang::firstOrCreate(
            ['gach_co_bat_trang_id' => 1],
            [
                'thumbnail_main' => 'assets/images/gach-co-banner.png',
                'video' => null,
            ]
        );

        $anhs = [
            ['image' => 'assets/images/gach-co-anh-01.jpg'],
            ['image' => 'assets/images/gach-co-anh-02.jpg'],
        ];
        foreach ($anhs as $anh) {
            GachCoBatTrangAnh::firstOrCreate(
                ['image' => $anh['image']],
                ['gach_co_bat_trang_id' => $parent->gach_co_bat_trang_id]
            );
        }
    }

    private function seedLinhVatPhongThuy(): void
    {
        $parent = LinhVatPhongThuy::firstOrCreate(
            ['linh_vat_phong_thuy_id' => 1],
            [
                'thumbnail_main' => 'assets/images/linh-vat-banner.png',
                'video' => null,
            ]
        );

        $linhVats = [
            ['title' => 'Long', 'image' => 'assets/images/linh-vat-long.png', 'description' => 'Rồng (Long) — linh vật quyền lực nhất trong tứ linh, biểu tượng của sức mạnh và sự uy nghi.'],
            ['title' => 'Lân', 'image' => 'assets/images/linh-vat-lan.png', 'description' => 'Lân — linh vật của sự may mắn, bình an, bảo vệ gia chủ khỏi tà khí.'],
        ];
        foreach ($linhVats as $lv) {
            LinhVat::firstOrCreate(
                ['title' => $lv['title']],
                [
                    'image' => $lv['image'],
                    'description' => $lv['description'],
                    'linh_vat_phong_thuy_id' => $parent->linh_vat_phong_thuy_id,
                ]
            );
        }

        $anhs = [
            ['image' => 'assets/images/linh-vat-anh-01.jpg'],
            ['image' => 'assets/images/linh-vat-anh-02.jpg'],
        ];
        foreach ($anhs as $anh) {
            LinhVatPhongThuyAnh::firstOrCreate(
                ['image' => $anh['image']],
                ['linh_vat_phong_thuy_id' => $parent->linh_vat_phong_thuy_id]
            );
        }
    }

    private function seedDenGomSu(): void
    {
        $parent = DenGomSu::firstOrCreate(
            ['den_gom_su_id' => 1],
            [
                'thumbnail_main' => 'assets/images/den-gom-banner.png',
                'video' => null,
                'title2' => 'Đèn gốm sứ cao cấp',
                'title3' => 'Sản phẩm tiêu biểu',
                'image1' => 'assets/images/den-gom-01.png',
                'image2' => 'assets/images/den-gom-02.png',
                'image3' => 'assets/images/den-gom-01.png',
                'image4' => 'assets/images/den-gom-02.png',
            ]
        );

        $anhs = [
            ['image' => 'assets/images/den-gom-anh-01.jpg'],
            ['image' => 'assets/images/den-gom-anh-02.jpg'],
        ];
        foreach ($anhs as $anh) {
            DenGomSuAnh::firstOrCreate(
                ['image' => $anh['image']],
                ['den_gom_su_id' => $parent->den_gom_su_id]
            );
        }
    }
}
