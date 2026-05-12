<?php

namespace Database\Seeders;

use App\Models\DanhMucTinTuc;
use App\Models\TinTuc;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class TinTucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tắt kiểm tra khóa ngoại (nếu có) và xóa dữ liệu cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TinTuc::truncate();
        
        // 1. Khởi tạo dữ liệu cho Danh Mục Tin Tức (để không bị lỗi khóa ngoại)
        // Ta sử dụng truncate() hoặc firstOrCreate để chắc chắn có ID 1, 2, 3
        DanhMucTinTuc::truncate();
        
        $categories = [
            'Cẩm nang xây dựng',
            'Gốm sứ Thanh Hải',
            'Công trình - Dự án'
        ];

        foreach ($categories as $index => $catName) {
            DanhMucTinTuc::create([
                'danh_muc_tin_tuc_id' => $index + 1,
                'ten_danh_muc'        => $catName,
                'is_delete'           => 0,
                'created_at'          => Carbon::now(),
                'updated_at'          => Carbon::now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Định nghĩa Block Nội dung dùng chung cho cả 6 bài viết
        $defaultBlocks = [[
                'type' => 'split_content',
                'data' =>[
                    'layout' => 'image_left',
                    'subtitle' => 'ELLE DECOR',
                    'title' => 'Gạch thông gió 300x300',
                    'description' => 'Điều này có nghĩa là chúng tôi tạo ra từng viên gạch hoàn toàn bằng tay, từ đầu đến cuối. Chúng tôi ép đất sét và cắt thủ công thành các hình dạng khác nhau mà chúng tôi cung cấp. Sau đó, lớp men được phủ bằng tay, điều này khiến quy trình của chúng tôi trở nên độc đáo... Điều này có nghĩa là chúng tôi tạo ra từng viên gạch hoàn toàn bằng tay, từ đầu đến cuối.',
                    'image_alt' => null,
                    'image_url' => 'seeders/tin-tuc/2.png'
                ]
            ],[
                'type' => 'image_metadata',
                'data' => [
                    'image_alt' => null,
                    'specs' => [
                        ['label' => 'DỰ ÁN', 'value' => null],
                        ['label' => 'ĐỊA ĐIỂM', 'value' => null],
                        ['label' => 'STYLE', 'value' => null]
                    ],
                    'image_url' => 'seeders/tin-tuc/3.png'
                ]
            ],
            [
                'type' => 'full_width_image',
                'data' => [
                    'image_alt' => null,
                    'image_url' => 'seeders/tin-tuc/4.png'
                ]
            ],[
                'type' => 'two_image_content',
                'data' =>[
                    'layout' => 'images_right',
                    'subtitle' => 'ELLE DECOR',
                    'title' => 'Gạch thông gió 300x300 thường',
                    'description' => "Điều này có nghĩa là chúng tôi tạo ra từng viên gạch hoàn toàn bằng tay, từ đầu đến cuối. Chúng tôi ép đất sét và cắt thủ công thành các hình dạng khác nhau mà chúng tôi cung cấp. Sau đó, lớp men được phủ bằng tay, điều này khiến quy trình của chúng tôi trở nên độc đáo...\n\nĐiều này có nghĩa là chúng tôi tạo ra từng viên gạch hoàn toàn bằng tay, từ đầu đến cuối.",
                    'specs' => [
                        ['label' => 'Dự Án', 'value' => null],
                        ['label' => 'Địa điểm', 'value' => null],
                        ['label' => 'Style', 'value' => null]
                    ],
                    'image_alt_1' => null,
                    'image_alt_2' => null,
                    'image_url_1' => 'seeders/tin-tuc/5.png',
                    'image_url_2' => 'seeders/tin-tuc/6.png'
                ]
            ]
        ];

        // 3. Mô tả ngắn dùng chung
        $defaultMoTaNgan = 'Điều này có nghĩa là chúng tôi tạo ra từng viên gạch hoàn toàn bằng tay, từ đầu đến cuối. Chúng tôi ép đất sét và cắt thủ công thành các hình dạng khác nhau mà chúng tôi cung cấp.';

        // 4. Danh sách các bài viết với Tên, Danh mục, Ảnh đại diện & Thời gian khác nhau
        $articles = [[
                'danh_muc_tin_tuc_id' => 1,
                'tieu_de'      => 'Gạch thông gió 300x300 thường được sử dụng trong các công trình nào?',
                'anh_dai_dien' => 'seeders/tin-tuc/gach-hoa-thong-gio-thumb.png',
                'ngay_tao'     => Carbon::parse('2026-05-12 09:30:00'),
            ],[
                'danh_muc_tin_tuc_id' => 1,
                'tieu_de'      => 'Airstream x Pottery Barn Are Inspiring Our Summer Road Trips',
                'anh_dai_dien' => 'seeders/tin-tuc/Airstream-thumb.png',
                'ngay_tao'     => Carbon::parse('2026-05-08 14:15:00'),
            ],[
                'danh_muc_tin_tuc_id' => 2,
                'tieu_de'      => 'Nghệ thuật làm gốm thủ công: Những giá trị văn hóa bền bỉ qua thời gian',
                'anh_dai_dien' => 'seeders/tin-tuc/Nghệ thuật làm gốm thumb.png',
                'ngay_tao'     => Carbon::parse('2026-05-05 10:00:00'),
            ],[
                'danh_muc_tin_tuc_id' => 2,
                'tieu_de'      => 'Quy trình luyện đất và nung gốm ở nhiệt độ 1300°C',
                'anh_dai_dien' => 'seeders/tin-tuc/Quy trình luyện đất thumb.png',
                'ngay_tao'     => Carbon::parse('2026-04-28 16:45:00'),
            ],[
                'danh_muc_tin_tuc_id' => 3,
                'tieu_de'      => 'Phục chế mái ngói Chùa Bái Đính: Hành trình di sản',
                'anh_dai_dien' => 'seeders/tin-tuc/Phục chế mái ngói thumb.png',
                'ngay_tao'     => Carbon::parse('2026-04-20 08:20:00'),
            ],[
                'danh_muc_tin_tuc_id' => 3,
                'tieu_de'      => 'Không gian nghỉ dưỡng với gạch hoa thông gió tại Vinpearl Land',
                'anh_dai_dien' => 'seeders/tin-tuc/Airstream-thumb.png',
                'ngay_tao'     => Carbon::parse('2026-04-15 11:10:00'),
            ],
        ];

        // 5. Lặp và Insert vào Database (can thiệp timestamps)
        foreach ($articles as $index => $article) {
            $tinTuc = new TinTuc([
                'tin_tuc_id'          => $index + 1,
                'danh_muc_tin_tuc_id' => $article['danh_muc_tin_tuc_id'],
                'tieu_de'             => $article['tieu_de'],
                'slug'                => Str::slug($article['tieu_de']),
                'anh_dai_dien'        => $article['anh_dai_dien'],
                'mo_ta_ngan'          => $defaultMoTaNgan,
                'the_loai'            => 'ELLE DECOR',
                'noi_dung_blocks'     => $defaultBlocks,
                'trang_thai'          => 'published',
                'ngay_dang'           => $article['ngay_tao'],
            ]);

            // Tạm thời tắt auto timestamps của Eloquent để ghi đè `created_at` và `updated_at` theo ý muốn
            $tinTuc->timestamps = false;
            $tinTuc->created_at = $article['ngay_tao'];
            $tinTuc->updated_at = $article['ngay_tao']->copy()->addHours(2);
            
            $tinTuc->save();
        }
    }
}