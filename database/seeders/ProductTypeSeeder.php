<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultImage = 'defaults/placeholder.png'; // Ảnh mặc định nếu chưa upload

        // 1. Ngói Âm Dương
        DB::table('ngoi_am_duong')->insertOrIgnore([
            'ngoi_am_duong_id' => 1,
            'thumbnail_main'   => $defaultImage,
            'thumbnail1'       => $defaultImage,
            'thumbnail2'       => $defaultImage,
            'video'            => null,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // 2. Ngói Hài Văn Miếu
        DB::table('ngoi_hai_van_mieu')->insertOrIgnore([
            'ngoi_hai_van_mieu_id' => 1,
            'thumbnail_main' => $defaultImage,
            'title1'         => 'Sản phẩm 1',
            'thumbnail1'     => $defaultImage,
            'title2'         => 'Sản phẩm 2',
            'thumbnail2'     => $defaultImage,
            'title3'         => 'Sản phẩm 3',
            'thumbnail3'     => $defaultImage,
            'video'          => '',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // 3. Gạch Hoa Thông Gió
        DB::table('gach_hoa_thong_gio')->insertOrIgnore([
            'gach_hoa_thong_gio_id' => 1,
            'image'      => $defaultImage,
            'video'      => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Phụ Kiện Ngói
        DB::table('phu_kien_ngoi')->insertOrIgnore([
            'phu_kien_ngoi_id' => 1,
            'thumbnail_main' => $defaultImage,
            'images'         => json_encode([]),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // 5. Gạch Trang Trí
        DB::table('gach_trang_tri')->insertOrIgnore([
            'gach_trang_tri_id' => 1,
            'thumbnail_main' => $defaultImage,
            'video'          => null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // 6. Lan Can Gốm Sứ
        DB::table('lan_can_gom_xu')->insertOrIgnore([
            'lan_can_gom_xu_id' => 1,
            'thumbnail_main' => $defaultImage,
            'video'          => null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // 7. Gạch Cổ Bát Tràng
        DB::table('gach_co_bat_trang')->insertOrIgnore([
            'gach_co_bat_trang_id' => 1,
            'thumbnail_main' => $defaultImage,
            'video'          => null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // 8. Linh Vật Phong Thủy
        DB::table('linh_vat_phong_thuy')->insertOrIgnore([
            'linh_vat_phong_thuy_id' => 1,
            'thumbnail_main' => $defaultImage,
            'video'          => null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // 9. Đèn Gốm Sứ
        DB::table('den_gom_su')->insertOrIgnore([
            'den_gom_su_id'  => 1,
            'thumbnail_main' => $defaultImage,
            'video'          => null,
            'image1'         => $defaultImage,
            'image2'         => $defaultImage,
            'title2'         => null,
            'image3'         => $defaultImage,
            'title3'         => null,
            'image4'         => $defaultImage,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}