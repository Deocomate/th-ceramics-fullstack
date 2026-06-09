<?php

namespace Database\Seeders;

use App\Models\TacGia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TacGiaSeeder extends Seeder
{
    use BaseProductSeeder;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TacGia::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $authors = [[
            'name' => 'Nghệ nhân Vũ Mạnh Hải',
            'desc' => 'Là người kế thừa thế hệ thứ ba của dòng họ Vũ Gia tại làng gốm Bát Tràng. Ông đã dành hơn 40 năm cuộc đời để gắn bó với ngọn lửa lò nung, nghiên cứu và phục dựng thành công nhiều bài men cổ quý hiếm.',
        ], [
            'name' => 'Nghệ nhân Nguyễn Thị Thanh',
            'desc' => 'Đồng sáng lập Gốm Sứ Thanh Hải, bà là một trong số ít những nữ nghệ nhân được vinh danh tại Bát Tràng. Với đôi bàn tay tài hoa, bà thổi hồn vào những họa tiết hoa văn chạm khắc.',
        ], [
            'name' => 'Kiến trúc sư Lê Hoàng',
            'desc' => 'Cố vấn chuyên môn về ứng dụng gốm sứ trong kiến trúc đương đại. Anh giúp Thanh Hải kết nối những giá trị truyền thống vào không gian sống hiện đại một cách hài hòa.',
        ],
        ];

        foreach ($authors as $i => $author) {
            TacGia::create([
                'ten_tac_gia' => $author['name'],
                'link_fb' => 'https://facebook.com/nghenhan'.$i,
                'link_linkedin' => 'https://linkedin.com/in/nghenhan'.$i,
                'link_tele' => 'https://t.me/nghenhan'.$i,
                'link_sky' => 'https://join.skype.com/invite/nghenhan'.$i,
                'mo_ta' => $author['desc'],
                // Dùng hàm copySingleImage, gọi đúng file author.svg
                'anh_dai_dien' => $this->copySingleImage('tac-gia', 'author.svg'),
            ]);
        }
    }
}
