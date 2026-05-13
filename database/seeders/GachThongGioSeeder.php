<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GachHoaThongGio;
use App\Models\GiaTriGachHoaThongGio;
use App\Models\GachHoaThongGioAnh;
use App\Models\GachHoaThongGioCt;

class GachThongGioSeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        GachHoaThongGio::truncate();
        GiaTriGachHoaThongGio::truncate();
        GachHoaThongGioAnh::truncate();
        GachHoaThongGioCt::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $parent = GachHoaThongGio::create([
            'video_thumbnail' => $this->copySingleImage('gach-thong-gio', 'gach-hoa-01.jpg'),
            'video_url'       => $this->generateVideoLink(),
            'process_images'  => $this->copySpecificImages('gach-thong-gio-cong-doan', ['cong-doan-01.jpg', 'cong-doan-02.jpg', 'work-01.jpg', 'work-02.jpg']),
        ]);

        $giaTris = [
            ['title' => 'Cách Nhiệt Tự Nhiên Tối Ưu', 'background' => '#1D78AD', 'image' => 'value-01.png', 'desscription' => 'Cấu trúc rỗng độc đáo của gạch hoa thông gió tạo ra lớp đệm không khí cách nhiệt hoàn hảo...'],
            ['title' => 'Điều Tiết Ánh Sáng Tinh Tế', 'background' => '#B28373', 'image' => 'value-02.png', 'desscription' => 'Những ô trống được thiết kế theo tỉ lệ vàng trong kiến trúc giúp điều tiết ánh sáng một cách tinh tế...'],
            ['title' => 'Thẩm Mỹ Độc Bản Nghệ Thuật', 'background' => '#5A7E46', 'image' => 'value-03.png', 'desscription' => 'Vượt ra khỏi khái niệm vật liệu xây dựng thông thường, mỗi viên gạch hoa là một tác phẩm...'],
            ['title' => 'Độ Bền Thách Thức Thời Tiết', 'background' => '#C08B5C', 'image' => 'value-04.png', 'desscription' => 'Nhờ nung luyện chuyên sâu ở 1.250 độ C, đất sét được thủy tinh hóa, mang lại độ nén đáng kinh ngạc...'],['title' => 'Hài Hòa Phong Thủy Vượng Khí', 'background' => '#7B6B8A', 'image' => 'gach-hoa-value.png', 'desscription' => 'Lưu thông không khí đóng vai trò quan trọng, thu hút vượng khí, xua tan tà khí, mang lại bình an...'],
        ];

        foreach ($giaTris as $gt) {
            GiaTriGachHoaThongGio::create([
                'title'                 => $gt['title'],
                'image'                 => $this->copySingleImage('gach-thong-gio', $gt['image']),
                'background'            => $gt['background'],
                'desscription'          => $gt['desscription'],
                'gach_hoa_thong_gio_id' => $parent->gach_hoa_thong_gio_id,
            ]);
        }

        $allGachHoaFiles =[
            'gach-hoa-01.jpg', 'gach-hoa-01.png', 'gach-hoa-02.jpg', 'gach-hoa-02.png',
            'gach-hoa-03.jpg', 'gach-hoa-03.png', 'gach-hoa-04.jpg', 'gach-hoa-04.png',
            'gach-hoa-05.jpg', 'gach-hoa-05.png', 'gach-hoa-06.jpg', 'gach-hoa-06.png',
            'gach-hoa-07.jpg', 'gach-hoa-07.png', 'gach-hoa-08.jpg', 'gach-hoa-08.png'
        ];

        foreach ($allGachHoaFiles as $file) {
            GachHoaThongGioAnh::create([
                'image'                 => $this->copySingleImage('gach-thong-gio-gallery', $file),
                'gach_hoa_thong_gio_id' => $parent->gach_hoa_thong_gio_id,
            ]);
        }

        $patterns =[
            'Hoa Sen Cách Điệu', 'Đồng Tiền Cổ', 'Chữ Thọ Phúc Lộc', 'Kỷ Hà Sóng Nước',
            'Hoa Mai Tứ Quý', 'Rồng Uy Nghi', 'Hình Học Đương Đại', 'Lá Đề Tâm Linh',
            'Mây Trời Phiêu Lãng', 'Cửu Long Tranh Châu', 'Song Hỷ Lâm Môn', 'Phượng Hoàng Lửa',
            'Thủy Ba Sông Hồng', 'Âm Dương Hòa Hợp', 'Bát Quái Trấn Trạch', 'Vân Xoắn Cổ Điển',
        ];

        for ($i = 1; $i <= 16; $i++) {
            shuffle($allGachHoaFiles);
            $selectedFiles = array_slice($allGachHoaFiles, 0, 10);
            
            GachHoaThongGioCt::create([
                'code'       => 'GTG-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name'       => "Gạch Hoa Thông Gió {$patterns[$i-1]}",
                'images'     => $this->copySpecificImages('gach-thong-gio-chi-tiet', $selectedFiles),
                'price'      => 22000 + ($i * 1000),
                'des'        => $this->generateDescription(),
                'size'       => '20 x 20 x 6 cm (Định mức 25 viên/m²)',
                'size_image' => $this->copySingleImage('gach-thong-gio', 'gtt-size.png'),
                'is_delete'  => 0,
            ]);
        }
    }
}