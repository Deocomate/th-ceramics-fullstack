<?php

namespace Database\Seeders;

use App\Models\GiaiThuongThanhTuu;
use Illuminate\Database\Seeder;

class GiaiThuongThanhTuuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $awards = [
            [
                'id' => 1,
                'image' => 'seeders/giai-thuong-thanh-tuu/giai-thuong-1.png',
                'des' => 'Bà Nguyễn Thị Thanh - Nghệ nhân Hà Nội năm 2025 do UBND thành phố Hà Nội trao tặng',
            ],
            [
                'id' => 2,
                'image' => 'seeders/giai-thuong-thanh-tuu/giai-thuong-2.png',
                'des' => 'Bà Nguyễn Thị Thanh - Nghệ nhân Hà Nội năm 2025 do UBND thành phố Hà Nội trao tặng',
            ],
            [
                'id' => 3,
                'image' => 'seeders/giai-thuong-thanh-tuu/giai-thuong-3.png',
                'des' => 'Ông Vũ Mạnh Hải - Nghệ nhân Hà Nội năm 2024 do UBND thành phố Hà Nội trao tặng',
            ],
            [
                'id' => 4,
                'image' => 'seeders/giai-thuong-thanh-tuu/giai-thuong-4.png',
                'des' => 'Bà Nguyễn Thị Thanh - Nghệ nhân Hà Nội năm 2025 do UBND thành phố Hà Nội trao tặng',
            ],
            [
                'id' => 5,
                'image' => 'seeders/giai-thuong-thanh-tuu/giai-thuong-5.png',
                'des' => 'Ông Vũ Mạnh Hải - Nghệ nhân Hà Nội năm 2024 do UBND thành phố Hà Nội trao tặng',
            ],
        ];

        foreach ($awards as $item) {
            GiaiThuongThanhTuu::updateOrCreate(
                ['giai_thuong_thanh_tuu_id' => $item['id']],
                [
                    'image' => $item['image'],
                    'des' => $item['des'],
                ]
            );
        }
    }
}
