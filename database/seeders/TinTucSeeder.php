<?php

namespace Database\Seeders;

use App\Models\DanhMucTinTuc;
use App\Models\TinTuc;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class TinTucSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables('tin_tuc', 'danh_muc_tin_tuc');
        $this->seedFromData('danh_muc_tin_tuc', DanhMucTinTuc::class);
        $this->seedFromData('tin_tuc', TinTuc::class);
        $this->seedRelatedPaginationArticles();
    }

    private function seedRelatedPaginationArticles(): void
    {
        $relatedTitles = [
            'Biệt thự nghỉ dưỡng FLC Sầm Sơn ứng dụng ngói âm dương',
            'Khu đô thị sinh thái Ecopark và hệ mái ngói truyền thống',
            'Resort Mường Thanh Diên Cần lộng gió với gạch thông gió',
            'Nhà hàng Indochine tại Đà Nẵng phối gạch trang trí thủ công',
            'Khu nghỉ dưỡng Alma Resort Cam Ranh và vật liệu gốm sứ',
            'Công trình nhà phố Hà Nội phủ ngói men cao cấp Bát Tràng',
            'Khách sạn boutique Đà Lạt kết hợp gạch hoa thông gió',
            'Trung tâm hội nghị tỉnh Ninh Bình phục chế mái ngói cổ',
            'Khu villa nghỉ dưỡng Phú Quốc ứng dụng gạch trang trí',
        ];

        foreach ($relatedTitles as $index => $title) {
            $articleNumber = $index + 7;

            TinTuc::query()->create([
                'danh_muc_tin_tuc_id' => 3,
                'tieu_de' => $title,
                'slug' => 'cong-trinh-du-an-lien-quan-'.$articleNumber,
                'anh_dai_dien' => 'seeders/tin-tuc/gach-hoa-thong-gio-thumb.png',
                'mo_ta_ngan' => 'Công trình tiêu biểu trong danh mục Công trình - Dự án của Gốm sứ Thanh Hải.',
                'the_loai' => 'CÔNG TRÌNH - DỰ ÁN',
                'noi_dung_blocks' => [],
                'trang_thai' => 'published',
                'ngay_dang' => now()->subDays($articleNumber),
            ]);
        }
    }
}
