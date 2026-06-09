<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThiCongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('thi_cong')->insert([
            [
                'thi_cong' => 1,
                'tieu_de' => 'Ngói Âm Dương',
                'anh' => 'seeders/huong-dan-thi-cong/ngoi-am-duong.png',
                'link_youtube' => 'https://www.youtube.com/embed/Win12rIicBI?si=Ca9pEVJ5RtUMC2kr',
                'created_at' => '2026-05-12 07:09:30',
                'updated_at' => '2026-05-12 07:13:31',
            ],
            [
                'thi_cong' => 2,
                'tieu_de' => 'Ngói Hài Văn Miếu',
                'anh' => 'seeders/huong-dan-thi-cong/ngoi-hai-van-mieu.png',
                'link_youtube' => 'https://www.youtube.com/embed/Win12rIicBI?si=Ca9pEVJ5RtUMC2kr',
                'created_at' => '2026-05-12 07:14:02',
                'updated_at' => '2026-05-12 07:14:02',
            ],
            [
                'thi_cong' => 3,
                'tieu_de' => 'Gạch Hoa Thông Gió',
                'anh' => 'seeders/huong-dan-thi-cong/gach-hoa-thong-gio.png',
                'link_youtube' => 'https://www.youtube.com/embed/Win12rIicBI?si=Ca9pEVJ5RtUMC2kr',
                'created_at' => '2026-05-12 07:09:30',
                'updated_at' => '2026-05-12 07:13:31',
            ],
            [
                'thi_cong' => 4,
                'tieu_de' => 'Phụ Kiện Ngói',
                'anh' => 'seeders/huong-dan-thi-cong/phu-kien-ngoi.png',
                'link_youtube' => 'https://www.youtube.com/embed/Win12rIicBI?si=Ca9pEVJ5RtUMC2kr',
                'created_at' => '2026-05-12 07:14:02',
                'updated_at' => '2026-05-12 07:14:02',
            ],
            [
                'thi_cong' => 5,
                'tieu_de' => 'Gạch Trang Trí',
                'anh' => 'seeders/huong-dan-thi-cong/gach-trang-tri.png',
                'link_youtube' => 'https://www.youtube.com/embed/Win12rIicBI?si=Ca9pEVJ5RtUMC2kr',
                'created_at' => '2026-05-12 07:09:30',
                'updated_at' => '2026-05-12 07:13:31',
            ],
            [
                'thi_cong' => 6,
                'tieu_de' => 'Lan Can Gốm Xứ',
                'anh' => 'seeders/huong-dan-thi-cong/ngoi-am-duong.png',
                'link_youtube' => 'https://www.youtube.com/embed/Win12rIicBI?si=Ca9pEVJ5RtUMC2kr',
                'created_at' => '2026-05-12 07:14:02',
                'updated_at' => '2026-05-12 07:14:02',
            ],
        ]);
    }
}
