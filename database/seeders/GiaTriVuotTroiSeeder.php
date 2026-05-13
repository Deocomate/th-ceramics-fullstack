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

        $values =[[
                'title' => 'Cốt Đất Luyện Lửa', // Chú ý: Đã rút gọn để vừa Max 50 ký tự của Database
                'desc'  => 'Được tôi luyện ở nhiệt độ 1.300°C liên tục trong 72 giờ, tạo nên cốt gốm đanh chắc, thách thức mọi điều kiện thời tiết khắc nghiệt từ sương muối đến bão giông. Đây là sự đầu tư xứng đáng một lần cho giá trị truyền đời, chẳng ngại dấu vết của thời gian.',
            ],[
                'title' => 'Kiến Trúc Trường Tồn', 
                'desc'  => 'Sự giao thoa hoàn hảo giữa vẻ đẹp truyền thống và công nghệ sản xuất hiện đại. Sản phẩm tạo nên những hệ mái nhà, những bức tường vững chãi, che chở vạn vật qua bao thăng trầm, mang lại sự bình yên, vượng khí và hưng thịnh cho mọi gia chủ.',
            ],[
                'title' => 'Nghệ Thuật Gốm Sứ', 
                'desc'  => 'Không chỉ là vật liệu xây dựng vô tri, từng viên gạch, viên ngói là một tác phẩm nghệ thuật thực thụ. Kết tinh từ đôi bàn tay khéo léo và tâm huyết của các nghệ nhân làng gốm Bát Tràng, mang trong mình tâm hồn và sức sống mãnh liệt của di sản Việt.',
            ],[
                'title' => 'Sắc Men Độc Bản', 
                'desc'  => 'Lớp men hỏa biến biến ảo khôn lường trong lò nung, tạo nên những sắc thái màu đặc trưng. Quá trình hỏa biến tự nhiên khiến không viên gạch nào hoàn toàn giống viên nào, tạo nên dấu ấn riêng biệt, độc bản và vô giá cho không gian sống của bạn.',
            ],
        ];

        foreach ($values as $i => $value) {
            GiaTriVuotTroi::create([
                'title'        => $value['title'],
                // Lưu ý tên cột trong DB của bạn đang bị sai chính tả là "desscription" (2 chữ s) -> Cần gọi đúng tên
                'desscription' => $value['desc'], 
                'image'        => $this->generateSingleImage('gia-tri-vuot-troi', "gia-tri-{$i}.jpg"),
            ]);
        }
    }
}