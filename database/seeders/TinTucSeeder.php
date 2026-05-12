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

        // 1. Khởi tạo dữ liệu cho Danh Mục Tin Tức
        DanhMucTinTuc::truncate();

        $categories = [
            'Cẩm nang xây dựng',
            'Gốm sứ Thanh Hải',
            'Công trình - Dự án'
        ];

        foreach ($categories as $index => $catName) {
            DanhMucTinTuc::create([
                'danh_muc_tin_tuc_id' => $index + 1,
                'ten_danh_muc' => $catName,
                'is_delete' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Danh sách các bài viết với Tiêu đề, Mô tả ngắn thực tế & Thời gian khác nhau
        $articles = [
            [
                'danh_muc_tin_tuc_id' => 1,
                'tieu_de' => 'Gạch thông gió 300x300 thường được sử dụng trong các công trình nào?',
                'anh_dai_dien' => 'seeders/tin-tuc/gach-hoa-thong-gio-thumb.png',
                'mo_ta_ngan' => 'Gạch thông gió kích thước 300x300 đang trở thành lựa chọn hàng đầu cho các công trình nhà phố, biệt thự và quán cafe nhờ khả năng lấy sáng, đón gió và tạo điểm nhấn kiến trúc độc đáo. Cùng Thanh Hải khám phá các ứng dụng thực tế phổ biến nhất.',
                'ngay_tao' => Carbon::parse('2026-05-12 09:30:00'),
                'subtitle' => 'KIẾN TRÚC & ĐỜI SỐNG'
            ],
            [
                'danh_muc_tin_tuc_id' => 1,
                'tieu_de' => 'Airstream x Pottery Barn Are Inspiring Our Summer Road Trips',
                'anh_dai_dien' => 'seeders/tin-tuc/Airstream-thumb.png',
                'mo_ta_ngan' => 'Sự kết hợp độc đáo giữa thiết kế xe dã ngoại Airstream và phong cách nội thất mộc mạc của Pottery Barn mang đến nguồn cảm hứng bất tận cho không gian sống mở, nơi các vật liệu gốm sứ và gỗ tự nhiên lên ngôi.',
                'ngay_tao' => Carbon::parse('2026-05-08 14:15:00'),
                'subtitle' => 'XU HƯỚNG THIẾT KẾ'
            ],
            [
                'danh_muc_tin_tuc_id' => 2,
                'tieu_de' => 'Nghệ thuật làm gốm thủ công: Những giá trị văn hóa bền bỉ qua thời gian',
                'anh_dai_dien' => 'seeders/tin-tuc/Nghệ thuật làm gốm thumb.png',
                'mo_ta_ngan' => 'Gốm thủ công không chỉ là vật liệu xây dựng mà còn là linh hồn của kiến trúc Việt. Mỗi viên gạch, ngói lợp đều mang trong mình câu chuyện ngàn năm của lửa, đất và bàn tay tài hoa của người nghệ nhân làng nghề.',
                'ngay_tao' => Carbon::parse('2026-05-05 10:00:00'),
                'subtitle' => 'CÂU CHUYỆN LÀNG NGHỀ'
            ],
            [
                'danh_muc_tin_tuc_id' => 2,
                'tieu_de' => 'Quy trình luyện đất và nung gốm ở nhiệt độ 1300°C',
                'anh_dai_dien' => 'seeders/tin-tuc/Quy trình luyện đất thumb.png',
                'mo_ta_ngan' => 'Để tạo ra những mẻ gốm sứ đạt độ cứng, độ bền và màu sắc men hoàn hảo, quy trình luyện đất và nung ở nhiệt độ tiêu chuẩn 1300°C đóng vai trò quyết định. Khám phá bí quyết gia truyền tại xưởng sản xuất Gốm Sứ Thanh Hải.',
                'ngay_tao' => Carbon::parse('2026-04-28 16:45:00'),
                'subtitle' => 'KỸ THUẬT CHẾ TÁC'
            ],
            [
                'danh_muc_tin_tuc_id' => 3,
                'tieu_de' => 'Phục chế mái ngói Chùa Bái Đính: Hành trình di sản',
                'anh_dai_dien' => 'seeders/tin-tuc/Phục chế mái ngói thumb.png',
                'mo_ta_ngan' => 'Quá trình tham gia cung cấp và phục chế hệ thống mái ngói tại Quần thể danh thắng Chùa Bái Đính là một thử thách lớn, đòi hỏi sự am hiểu sâu sắc về kiến trúc tâm linh và kỹ thuật chế tác ngói hài cổ truyền thống.',
                'ngay_tao' => Carbon::parse('2026-04-20 08:20:00'),
                'subtitle' => 'DỰ ÁN TÂM LINH'
            ],
            [
                'danh_muc_tin_tuc_id' => 3,
                'tieu_de' => 'Không gian nghỉ dưỡng với gạch hoa thông gió tại Vinpearl Land',
                'anh_dai_dien' => 'seeders/tin-tuc/Airstream-thumb.png',
                'mo_ta_ngan' => 'Ứng dụng hệ thống tường gạch hoa thông gió tại khu nghỉ dưỡng cao cấp không chỉ giải quyết bài toán khí hậu nhiệt đới nắng nóng mà còn tạo nên những mảng họa tiết ánh sáng nghệ thuật, giao thoa tinh tế giữa truyền thống và hiện đại.',
                'ngay_tao' => Carbon::parse('2026-04-15 11:10:00'),
                'subtitle' => 'DỰ ÁN NGHỈ DƯỠNG'
            ],
        ];

        // 3. Lặp và Insert vào Database (can thiệp timestamps và tự động sinh Blocks theo ngữ cảnh)
        foreach ($articles as $index => $article) {

            // Xây dựng nội dung Blocks chi tiết, đầy đủ dữ liệu thực tế cho từng bài
            $blocks = [
                [
                    'type' => 'split_content',
                    'data' => [
                        'layout' => 'image_left',
                        'subtitle' => $article['subtitle'],
                        'title' => 'Cảm hứng từ chất liệu truyền thống',
                        'description' => "Trong bối cảnh kiến trúc hiện đại ngày càng đề cao tính bền vững, việc quay trở lại sử dụng các vật liệu thủ công nguyên bản đang trở thành xu hướng tất yếu. Gốm sứ không chỉ đơn thuần là vật liệu bề mặt, mà nó còn đóng vai trò là chiếc cầu nối giữa không gian sống của con người và thiên nhiên tự nhiên.\n\nMỗi sản phẩm đều trải qua hàng chục công đoạn xử lý khắt khe, từ việc lựa chọn nguồn đất sét tinh khiết, ủ đất, cho đến thao tác vuốt nặn trên bàn xoay và cuối cùng là làm chủ ngọn lửa trong lò nung.",
                        'image_alt' => 'Chi tiết họa tiết gốm sứ thủ công nghệ thuật',
                        'image_url' => 'seeders/tin-tuc/2.png'
                    ]
                ],
                [
                    'type' => 'image_metadata',
                    'data' => [
                        'image_alt' => 'Không gian ứng dụng vật liệu gốm sứ Thanh Hải',
                        'specs' => [
                            ['label' => 'ĐƠN VỊ CUNG CẤP', 'value' => 'Gốm Sứ Thanh Hải'],
                            ['label' => 'PHONG CÁCH', 'value' => 'Indochine / Tropical Architecture'],
                            ['label' => 'ĐẶC TÍNH VẬT LIỆU', 'value' => 'Thủ công 100%, nung ở 1300°C']
                        ],
                        'image_url' => 'seeders/tin-tuc/3.png'
                    ]
                ],
                [
                    'type' => 'full_width_image',
                    'data' => [
                        'image_alt' => 'Toàn cảnh không gian kiến trúc ứng dụng gốm sứ truyền thống',
                        'image_url' => 'seeders/tin-tuc/4.png'
                    ]
                ],
                [
                    'type' => 'two_image_content',
                    'data' => [
                        'layout' => 'images_right',
                        'subtitle' => 'DẤU ẤN KIẾN TRÚC',
                        'title' => 'Sự kết hợp hoàn hảo giữa công năng và thẩm mỹ',
                        'description' => "Việc bố trí các mảng gạch thông gió hay mái ngói lợp không chỉ giải quyết triệt để vấn đề thông gió tự nhiên, chắn nắng gắt mà còn tạo ra những hiệu ứng bóng đổ tuyệt đẹp khi ánh sáng mặt trời lướt qua.\n\nSự đa dạng trong kích thước và họa tiết cho phép các kiến trúc sư tự do sáng tạo, biến những bức tường đơn điệu thành các tác phẩm nghệ thuật có chiều sâu, mang lại giá trị vĩnh cửu cho tổng thể công trình.",
                        'specs' => [
                            ['label' => 'Ứng dụng chính', 'value' => 'Mặt tiền chắn nắng, vách ngăn không gian'],
                            ['label' => 'Độ bền cam kết', 'value' => 'Chống rêu mốc, bền màu vĩnh viễn'],
                            ['label' => 'Thời gian thi công', 'value' => 'Tối ưu nhanh chóng, dễ lắp đặt']
                        ],
                        'image_alt_1' => 'Mảng tường gạch hoa thông gió đón nắng',
                        'image_alt_2' => 'Chi tiết kỹ thuật xếp gạch trang trí',
                        'image_url_1' => 'seeders/tin-tuc/5.png',
                        'image_url_2' => 'seeders/tin-tuc/6.png'
                    ]
                ]
            ];

            $tinTuc = new TinTuc([
                'tin_tuc_id' => $index + 1,
                'danh_muc_tin_tuc_id' => $article['danh_muc_tin_tuc_id'],
                'tieu_de' => $article['tieu_de'],
                'slug' => Str::slug($article['tieu_de']),
                'anh_dai_dien' => $article['anh_dai_dien'],
                'mo_ta_ngan' => $article['mo_ta_ngan'],
                'the_loai' => $article['subtitle'],
                'noi_dung_blocks' => $blocks,
                'trang_thai' => 'published',
                'ngay_dang' => $article['ngay_tao'],
            ]);

            // Tạm thời tắt auto timestamps của Eloquent để ghi đè `created_at` và `updated_at` theo ý muốn
            $tinTuc->timestamps = false;
            $tinTuc->created_at = $article['ngay_tao'];
            $tinTuc->updated_at = $article['ngay_tao']->copy()->addHours(2);

            $tinTuc->save();
        }
    }
}