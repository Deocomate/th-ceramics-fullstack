<?php

namespace Database\Seeders;

use App\Models\Catalog;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ trước khi seed (nếu cần thiết, nếu không bạn có thể comment dòng này lại)
        Catalog::truncate();

        // Tạo bản ghi Catalog mới
        Catalog::create([
            'tieu_de' => 'Catalog Gạch Hoa Thông Gió',
            'anh_dai_dien' => 'assets/images/gach-hoa-01.jpg', // Bạn có thể đổi thành đường dẫn ảnh bìa thực tế
            'file' => 'catalog/files/Catalog - GHTG 1 (1).pdf', // Không cần chữ 'storage/' ở đầu
        ]);

    }
}
