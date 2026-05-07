<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\PageContact;
use App\Models\PageFactory;
use App\Models\PageFaq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageConfigSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── Page Factory ──────────────────────────────────────────────
        PageFactory::create([
            'hero_banner_desktop' => 'assets/images/factory-banner.png',
            'hero_banner_mobile' => 'assets/images/factory-banner-02.png',
            'intro_title' => 'Nhà xưởng',
            'intro_subtitle' => 'QUY MÔ ẤN TƯỢNG: 5000M² - 3 TẦNG VẬN HÀNH CHUYÊN BIỆT',
            'intro_description' => 'Để đáp ứng những đơn hàng lớn cho các công trình trọng điểm như đình chùa, biệt thự hay khu nghỉ dưỡng, Thanh Hải đã đầu tư hệ thống nhà xưởng với tổng diện tích lên đến 5.000m², được thiết kế tối ưu với 3 tầng sản xuất. Việc mở rộng không gian không chỉ khẳng định năng lực cung ứng mạnh mẽ mà còn giúp chúng tôi kiểm soát chất lượng sản phẩm một cách khắt khe nhất. Mỗi tầng đều được quy hoạch bài bản, đảm bảo diện tích lưu kho và khu vực chế tác luôn thông thoáng, đáp ứng mọi tiến độ gấp gáp từ khách hàng.',
            'gallery_1' => json_encode([
                'assets/images/trang-tri-slide-01.jpg',
                'assets/images/factory-01.jpg',
                'assets/images/factory-04.jpg',
                'assets/images/trang-tri-slide-02.jpg',
            ]),
            'process_title' => 'QUY TRÌNH "KHOA HỌC - NGĂN NẮP - TÁCH BIỆT"',
            'process_description' => 'Điểm khác biệt lớn nhất giúp khách hàng luôn an tâm khi đặt hàng tại Thanh Hải chính là sự chuyên nghiệp trong cách bố trí nhà xưởng. Chúng tôi hiểu rằng, một sản phẩm gốm sứ hoàn hảo phải được ra đời từ một môi trường làm việc kỷ luật:',
            'process_slider' => json_encode([
                'assets/images/factory-02.png',
                'assets/images/den-gom-01.png',
            ]),
            'process_bottom_title' => 'SỨC MẠNH CỦA SỰ KẾT HỢP: MÁY MÓC HIỆN ĐẠI & BÀN TAY NGHỆ NHÂN',
            'process_bottom_desc' => 'Dù sở hữu hệ thống máy móc hỗ trợ vừa phải và hiện đại để đảm bảo độ chuẩn xác về thông số kỹ thuật (như độ nén, độ bền uốn theo tiêu chuẩn ISO), nhưng tại Thanh Hải, giá trị cốt lõi vẫn nằm ở đôi bàn tay con người. Chúng tôi kiên trì giữ vững phương thức thủ công truyền thống trong các khâu quan trọng. Mỗi sản phẩm đều mang dấu ấn riêng biệt, có chiều sâu và sự ấm áp mà những dây chuyền công nghiệp đại trà không bao giờ có được.',
            'process_bottom_image' => 'assets/images/gach-co-work-2.jpg',
            'material_slider' => json_encode([
                'assets/images/factory-03.png',
                'assets/images/factory-04.jpg',
                'assets/images/factory-04.jpg',
            ]),
            'material_steps' => json_encode([
                [
                    'number' => '1',
                    'title' => 'LỰA CHỌN VÀ XỬ LÝ NGUYÊN LIỆU (PHA CHẾ ĐẤT)',
                    'description' => 'Đây là bước quan trọng nhất quyết định độ bền của sản phẩm. Thanh Hải tuyển chọn những loại đất sét có độ dẻo cao, khả năng chịu nhiệt tốt (thường từ các vùng nguyên liệu nổi tiếng như Trúc Thôn). Đất được xử lý qua hệ thống bể lọc để loại bỏ tạp chất, sau đó pha trộn theo tỷ lệ bí truyền để tạo ra "xương" gốm vững chắc.',
                ],
                [
                    'number' => '2',
                    'title' => 'TẠO HÌNH VÀ HOÀN THIỆN MỘC',
                    'description' => 'Sau khi đạt chuẩn độ dẻo, đất được đưa vào công đoạn tạo hình bằng khuôn hoặc thủ công tùy từng dòng sản phẩm. Mỗi viên ngói, viên gạch đều được chỉnh sửa tỉ mỉ các cạnh và bề mặt trước khi chuyển sang giai đoạn sấy nhằm bảo đảm tính thẩm mỹ và độ đồng đều.',
                ],
                [
                    'number' => '3',
                    'title' => 'NUNG Ở NHIỆT ĐỘ CAO',
                    'description' => 'Sản phẩm sau khi tạo hình sẽ được sấy khô tự nhiên để loại bỏ độ ẩm, sau đó đưa vào lò nung với nhiệt độ lên đến 1200°C. Quá trình nung này giúp đất sét hóa cứng, tạo ra độ bền cơ học cao và khả năng chống thấm nước vượt trội cho ngói và gạch.',
                ],
            ]),
        ]);

        // ── Page Contact ──────────────────────────────────────────────
        PageContact::create([
            'map_image' => 'assets/images/contact-map.png',
            'hotline' => '0966 55 8808',
            'zalo_link' => null,
            'zalo_image' => 'assets/images/zalo2.png',
            'form_title' => 'Hãy nói với chúng tôi những mong muốn của bạn',
        ]);

        // ── Page FAQ ──────────────────────────────────────────────────
        PageFaq::create([
            'banner_image' => 'assets/images/faq-banner.png',
        ]);

        // ── FAQ Items ─────────────────────────────────────────────────
        $faqs = [
            // Category: Sản phẩm
            [
                'category' => 'sản-phẩm',
                'question' => 'Gốm sứ xây dựng Thanh Hải có phải là hàng thủ công không?',
                'answer' => 'Đúng vậy. Chúng tôi tự hào duy trì quy trình sản xuất thủ công truyền thống. Từ khâu chọn đất, tạo hình, đến tráng men và nung lò. Mỗi sản phẩm đều mang dấu ấn bàn tay khéo léo của các nghệ nhân. Điều này tạo nên vẻ đẹp độc bản mà các loại gạch ngói công nghiệp sản xuất hàng loạt không thể có được.',
                'sort_order' => 1,
            ],
            [
                'category' => 'sản-phẩm',
                'question' => 'Tôi có thể mua hàng như thế nào?',
                'answer' => 'Bạn có thể mua hàng trực tiếp tại showroom, qua Hotline hoặc các kênh mạng xã hội của chúng tôi.',
                'sort_order' => 2,
            ],
            [
                'category' => 'sản-phẩm',
                'question' => 'Tôi có thể lấy mẫu thử không?',
                'answer' => 'Chúng tôi sẵn sàng gửi mẫu thử cho khách hàng ở xa. Vui lòng liên hệ để được hỗ trợ.',
                'sort_order' => 3,
            ],
            [
                'category' => 'sản-phẩm',
                'question' => 'Các sản phẩm của gốm sứ Thanh Hải có bền khi sử dụng ngoài trời hay không?',
                'answer' => 'Tất cả sản phẩm của chúng tôi đều được nung ở nhiệt độ cao, đảm bảo độ bền tuyệt đối khi sử dụng ngoài trời.',
                'sort_order' => 4,
            ],
            [
                'category' => 'sản-phẩm',
                'question' => 'Màu men có bị phai dưới ánh nắng mặt trời không?',
                'answer' => 'Lớp men gốm được nung hỏa biến ở nhiệt độ 1300 độ C, cam kết không bao giờ phai màu.',
                'sort_order' => 5,
            ],

            // Category: Giá cả & Đặt hàng
            [
                'category' => 'báo-giá',
                'question' => 'Giá sản phẩm được tính như thế nào?',
                'answer' => 'Giá gốm sứ xây dựng thường được tính theo mét vuông (m2), mét dài (md) hoặc theo viên/ cặp đối với các dòng sản phẩm gạch, ngói và tính theo đơn vị đôi/ chiếc đối với các sản phẩm đơn lẻ khác. Giá cả phụ thuộc vào kích thước, loại men, hay độ phức tạp của hình dáng sản phẩm.',
                'sort_order' => 1,
            ],
            [
                'category' => 'báo-giá',
                'question' => 'Đặt hàng số lượng lớn có được chiết khấu không?',
                'answer' => 'Tất nhiên. Chúng tôi luôn có chính sách chiết khấu linh hoạt cho các đơn hàng số lượng lớn.',
                'sort_order' => 2,
            ],
            [
                'category' => 'báo-giá',
                'question' => 'Có yêu cầu số lượng đặt hàng tối thiểu không?',
                'answer' => 'Chúng tôi tiếp nhận mọi đơn hàng dù chỉ từ một sản phẩm đơn lẻ.',
                'sort_order' => 3,
            ],
            [
                'category' => 'báo-giá',
                'question' => 'Màu sắc có ảnh hưởng đến giá sản phẩm không?',
                'answer' => 'Một số màu men hỏa biến đặc biệt hoặc các yêu cầu pha chế màu riêng có thể có sự chênh lệch nhẹ về giá.',
                'sort_order' => 4,
            ],
            [
                'category' => 'báo-giá',
                'question' => 'Tại sao các kích thước nhỏ lại đắt hơn nhiều so với các kích thước lớn?',
                'answer' => 'Kích thước nhỏ thường đòi hỏi sự tỉ mỉ cao hơn trong khâu tạo hình và hoàn thiện thủ công, công sức bỏ ra cho mỗi cm2 là lớn hơn.',
                'sort_order' => 5,
            ],

            // Category: Vận chuyển & Lắp đặt
            [
                'category' => 'vận-chuyển',
                'question' => 'Thời gian sản xuất và giao hàng là bao lâu?',
                'answer' => 'Đối với hàng có sẵn: Chúng tôi có thể giao hàng trong vòng 2-5 ngày làm việc. Đối với hàng đặt sản xuất: Thường mất từ 3-6 tuần tùy vào quy mô đơn hàng và điều kiện thời tiết (ảnh hưởng đến quá trình phơi gốm mộc).',
                'sort_order' => 1,
            ],
            [
                'category' => 'vận-chuyển',
                'question' => 'Các bạn có giao hàng toàn quốc không?',
                'answer' => 'Chúng tôi vận chuyển toàn quốc bằng xe tải chuyên dụng hoặc các đối tác logistic uy tín.',
                'sort_order' => 2,
            ],
            [
                'category' => 'vận-chuyển',
                'question' => 'Tôi nên lưu ý gì khi lắp đặt gốm thủ công?',
                'answer' => 'Nên sử dụng thợ có tay nghề và hiểu về đặc tính gốm. Chúng tôi luôn cung cấp tài liệu hướng dẫn lắp đặt đi kèm.',
                'sort_order' => 3,
            ],
            [
                'category' => 'vận-chuyển',
                'question' => 'Các bạn có vận chuyển quốc tế không?',
                'answer' => 'Có, chúng tôi hỗ trợ đóng gói kiện gỗ xuất khẩu và làm các thủ tục hải quan cần thiết.',
                'sort_order' => 4,
            ],
            [
                'category' => 'vận-chuyển',
                'question' => 'Tôi có thể tự đến lấy hàng trực tiếp không?',
                'answer' => 'Quý khách có thể nhận hàng trực tiếp tại xưởng sản xuất hoặc showroom của chúng tôi.',
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
