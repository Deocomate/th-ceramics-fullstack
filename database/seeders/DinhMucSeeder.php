<?php

namespace Database\Seeders;

use App\Models\DinhMucGachCoBatTrang;
use App\Models\DinhMucGachHoaThongGio;
use App\Models\DinhMucGachTrangTri;
use App\Models\DinhMucNgoiAmDuong;
use App\Models\DinhMucNgoiHaiCo;
use App\Models\DinhMucNgoiHaiVanMieu;
use Illuminate\Database\Seeder;

class DinhMucSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedNgoiAmDuong();
        $this->seedNgoiHaiCo();
        $this->seedNgoiHaiVanMieu();
        $this->seedGachTrangTri();
        $this->seedGachHoaThongGio();
        $this->seedGachCoBatTrang();
    }

    private function seedNgoiAmDuong(): void
    {
        $data = [
            ['roof_type' => 'Mái gỗ', 'tile_type' => '15 cặp/m²', 'ngoi_am' => 25, 'ngoi_duong' => 15, 'diem' => 3],
            ['roof_type' => 'Mái gỗ', 'tile_type' => '27 cặp/m²', 'ngoi_am' => 40, 'ngoi_duong' => 27, 'diem' => 5],
            ['roof_type' => 'Mái gỗ', 'tile_type' => '43 cặp/m²', 'ngoi_am' => 60, 'ngoi_duong' => 43, 'diem' => 6],
            ['roof_type' => 'Mái gỗ', 'tile_type' => '80 cặp/m²', 'ngoi_am' => 120, 'ngoi_duong' => 80, 'diem' => 8],
            ['roof_type' => 'Mái bê tông', 'tile_type' => '15 cặp/m²', 'ngoi_am' => 15, 'ngoi_duong' => 15, 'diem' => 3],
            ['roof_type' => 'Mái bê tông', 'tile_type' => '27 cặp/m²', 'ngoi_am' => 27, 'ngoi_duong' => 27, 'diem' => 5],
            ['roof_type' => 'Mái bê tông', 'tile_type' => '43 cặp/m²', 'ngoi_am' => 43, 'ngoi_duong' => 43, 'diem' => 6],
            ['roof_type' => 'Mái bê tông', 'tile_type' => '80 cặp/m²', 'ngoi_am' => 80, 'ngoi_duong' => 80, 'diem' => 8],
        ];

        foreach ($data as $item) {
            DinhMucNgoiAmDuong::firstOrCreate(
                ['roof_type' => $item['roof_type'], 'tile_type' => $item['tile_type']],
                $item
            );
        }
    }

    private function seedNgoiHaiCo(): void
    {
        DinhMucNgoiHaiCo::firstOrCreate(
            ['roof_type' => 'Kích thước tiêu chuẩn'],
            [
                'ngoi_tren_mai_go' => 125,
                'ngoi_tren_mai_be_tong' => 75,
            ]
        );
    }

    private function seedNgoiHaiVanMieu(): void
    {
        DinhMucNgoiHaiVanMieu::firstOrCreate(
            ['roof_type' => 'Kích thước tiêu chuẩn'],
            [
                'ngoi_tren_mai_go' => 125,
                'ngoi_tren_mai_be_tong' => 88,
            ]
        );
    }

    private function seedGachTrangTri(): void
    {
        $sizes = [
            ['10x10 cm', 100],
            ['10x20 cm', 50],
            ['15x15 cm', 44],
            ['20x20 cm', 25],
            ['30x30 cm', 11],
        ];

        foreach ($sizes as [$size, $value]) {
            DinhMucGachTrangTri::firstOrCreate(
                ['brick_type' => 'Kích thước ' . $size],
                ['value' => $value]
            );
        }
    }

    private function seedGachHoaThongGio(): void
    {
        $sizes = [
            ['15x15x6 cm', 44],
            ['20x20x6 cm', 25],
            ['20x30x10 cm', 16],
            ['30x30x10 cm', 11],
        ];

        foreach ($sizes as [$size, $value]) {
            DinhMucGachHoaThongGio::firstOrCreate(
                ['brick_type' => 'Gạch ' . $size],
                ['value' => $value]
            );
        }
    }

    private function seedGachCoBatTrang(): void
    {
        $sizes = [
            ['5x20x2.5 cm (ốp tường)', 100],
            ['10x20x5 cm (xây tường)', 50],
            ['20x20 cm (lát nền)', 25],
            ['10x10x2 cm (ốp vách)', 100],
        ];

        foreach ($sizes as [$size, $value]) {
            DinhMucGachCoBatTrang::firstOrCreate(
                ['brick_type' => 'Kích thước ' . $size],
                ['value' => $value]
            );
        }
    }
}
