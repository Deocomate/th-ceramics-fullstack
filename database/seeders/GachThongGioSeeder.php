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
            'video_thumbnail' => $this->generateSingleImage('gach-thong-gio', 'main-banner.jpg'),
            'video_url'       => $this->generateVideoLink(),
            'process_images'  => $this->generateGallery('gach-thong-gio-cong-doan', 15),
        ]);

        $giaTris = [[
                'title'        => 'Cách Nhiệt Tự Nhiên Tối Ưu',
                'background'   => '#1D78AD',
                'desscription' => 'Cấu trúc rỗng độc đáo của gạch hoa thông gió tạo ra lớp đệm không khí cách nhiệt hoàn hảo. Kết hợp với chất liệu đất sét nung ở 1.250 độ C, sản phẩm giúp giảm nhiệt độ công trình từ 3-5 độ C vào mùa hè, tiết kiệm 30% chi phí điện năng làm mát.',
            ],[
                'title'        => 'Điều Tiết Ánh Sáng Tinh Tế',
                'background'   => '#B28373',
                'desscription' => 'Những ô trống được thiết kế theo tỉ lệ vàng trong kiến trúc giúp điều tiết ánh sáng một cách tinh tế. Ánh sáng xuyên qua từng ô gạch tạo nên hiệu ứng thị giác đầy nghệ thuật, mang đến vẻ đẹp sống động biến đổi theo từng thời điểm trong ngày.',
            ],[
                'title'        => 'Thẩm Mỹ Độc Bản Nghệ Thuật',
                'background'   => '#5A7E46',
                'desscription' => 'Vượt ra khỏi khái niệm vật liệu xây dựng thông thường, mỗi viên gạch hoa thông gió là một tác phẩm nghệ thuật. Lớp men hỏa biến phủ lên bề mặt giúp mỗi sản phẩm sở hữu một phổ màu riêng biệt, không có viên gạch nào hoàn toàn giống nhau.',
            ],
        ];

        foreach ($giaTris as $index => $gt) {
            GiaTriGachHoaThongGio::create([
                'title'                 => $gt['title'],
                'image'                 => $this->generateSingleImage('gach-thong-gio', "value-{$index}.jpg"),
                'background'            => $gt['background'],
                'desscription'          => $gt['desscription'],
                'gach_hoa_thong_gio_id' => $parent->gach_hoa_thong_gio_id,
            ]);
        }

        for ($i = 1; $i <= 15; $i++) {
            GachHoaThongGioAnh::create([
                'image'                 => $this->generateSingleImage('gach-thong-gio-gallery', "gallery-{$i}.jpg"),
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
            GachHoaThongGioCt::create([
                'code'       => 'GTG-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name'       => "Gạch Hoa Thông Gió {$patterns[$i-1]}",
                // GỌI HÀM RANDOM Ở ĐÂY CHO MỖI SẢN PHẨM:
                'images'     => $this->generateRandomGallery('gach-thong-gio-chi-tiet', 30, 10),
                'price'      => 22000 + ($i * 1000),
                'des'        => $this->generateDescription(),
                'size'       => '20 x 20 x 6 cm (Định mức 25 viên/m²)',
                'size_image' => $this->generateSingleImage('gach-thong-gio', 'size-guide.jpg'),
                'is_delete'  => 0,
            ]);
        }
    }
}