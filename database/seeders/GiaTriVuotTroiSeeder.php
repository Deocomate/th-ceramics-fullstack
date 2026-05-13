<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GiaTriVuotTroi;

class GiaTriVuotTroiSeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        GiaTriVuotTroi::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $values = [
            [
                'title' => 'Cốt Đất Luyện Lửa', 
                'desc'  => 'Được tôi luyện ở nhiệt độ 1.300°C liên tục trong 72 giờ, tạo nên cốt gốm đanh chắc, thách thức mọi điều kiện thời tiết khắc nghiệt từ sương muối đến bão giông.',
            ],[
                'title' => 'Kiến Trúc Trường Tồn', 
                'desc'  => 'Sự giao thoa hoàn hảo giữa vẻ đẹp truyền thống và công nghệ sản xuất hiện đại. Sản phẩm tạo nên những hệ mái nhà vững chãi.',
            ],
            [
                'title' => 'Nghệ Thuật Gốm Sứ', 
                'desc'  => 'Không chỉ là vật liệu xây dựng vô tri, từng viên gạch, viên ngói là một tác phẩm nghệ thuật thực thụ, kết tinh từ bàn tay khéo léo làng Bát Tràng.',
            ],
            [
                'title' => 'Sắc Men Độc Bản', 
                'desc'  => 'Lớp men hỏa biến biến ảo khôn lường trong lò nung, tạo nên những sắc thái màu đặc trưng không viên nào giống viên nào.',
            ],
        ];

        // Lấy đúng tên 4 ảnh có thật
        $images = ['gia-tri-vuot-troi-01.jpg', 'gia-tri-vuot-troi-02.jpg', 'gia-tri-vuot-troi-03.jpg', 'gia-tri-vuot-troi-04.jpg'];

        foreach ($values as $i => $value) {
            GiaTriVuotTroi::create([
                'title'        => $value['title'],
                'desscription' => $value['desc'], 
                'image'        => $this->copySingleImage('gia-tri-vuot-troi', $images[$i]),
            ]);
        }
    }
}