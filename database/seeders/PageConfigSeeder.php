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
        $this->seedPageFactory();
        $this->seedPageContact();
        $this->seedPageFaq();
        $this->seedFaqs();
    }

    private function seedPageFactory(): void
    {
        PageFactory::firstOrCreate(
            ['page_factory_id' => 1],
            [
                'hero_banner_desktop' => 'assets/images/factory-banner.png',
                'hero_banner_mobile' => 'assets/images/factory-banner-02.png',
                'intro_title' => 'Nhà xưởng',
                'intro_subtitle' => 'QUY MÔ ẤN TƯỢNG: 5000M² - 3 TẦNG VẬN HÀNH CHUYÊN BIỆT',
                'intro_description' => [
                    [
                        'type' => 'paragraph',
                        'content' => 'Để đáp ứng những đơn hàng lớn cho các công trình trọng điểm như đình chùa, biệt thự hay khu nghỉ dưỡng, Thanh Hải đã đầu tư hệ thống nhà xưởng với tổng diện tích lên đến 5.000m², được thiết kế tối ưu với 3 tầng sản xuất. Việc mở rộng không gian không chỉ khẳng định năng lực cung ứng mạnh mẽ mà còn giúp chúng tôi kiểm soát chất lượng sản phẩm một cách khắt khe nhất. Mỗi tầng đều được quy hoạch bài bản, đảm bảo diện tích lưu kho và khu vực chế tác luôn thông thoáng, đáp ứng mọi tiến độ gấp gáp từ khách hàng.',
                    ],
                ],
                'gallery_1' => [
                    'assets/images/trang-tri-slide-01.jpg',
                    'assets/images/factory-01.jpg',
                ],
                'gallery_2' => [
                    'assets/images/factory-04.jpg',
                    'assets/images/trang-tri-slide-02.jpg',
                ],
                'process_title' => "QUY TRÌNH\n\"KHOA HỌC - NGĂN NẮP - TÁCH BIỆT\"",
                'process_description' => [
                    [
                        'type' => 'paragraph',
                        'content' => 'Điểm khác biệt lớn nhất giúp khách hàng luôn an tâm khi đặt hàng tại Thanh Hải chính là sự chuyên nghiệp trong cách bố trí nhà xưởng. Chúng tôi hiểu rằng, một sản phẩm gốm sứ hoàn hảo phải được ra đời từ một môi trường làm việc kỷ luật:',
                    ],
                    [
                        'type' => 'list',
                        'items' => [
                            [
                                'title' => 'Khu vực tạo cốt và pha men:',
                                'content' => 'Được tách biệt hoàn toàn để tránh bụi bẩn ảnh hưởng đến các khâu sau.',
                            ],
                            [
                                'title' => 'Khu vực chế tác & tạo hình:',
                                'content' => 'Nơi những nghệ nhân tập trung cao độ để thổi hồn vào đất.',
                            ],
                            [
                                'title' => 'Khu vực nung & kiểm định:',
                                'content' => 'Được bố trí lối đi rộng rãi, giúp quy trình vận chuyển bán thành phẩm diễn ra trơn tru, hạn chế tối đa nứt vỡ.',
                            ],
                        ],
                    ],
                ],
                'process_slider' => [
                    'assets/images/factory-02.png',
                    'assets/images/den-gom-01.png',
                ],
                'process_bottom_title' => "SỨC MẠNH CỦA SỰ KẾT HỢP:\nMÁY MÓC HIỆN ĐẠI & BÀN TAY NGHỆ NHÂN",
                'process_bottom_desc' => [
                    [
                        'type' => 'paragraph',
                        'content' => 'Dù sở hữu hệ thống máy móc hỗ trợ vừa phải và hiện đại để đảm bảo độ chuẩn xác về thông số kỹ thuật (như độ nén, độ bền uốn theo tiêu chuẩn ISO), nhưng tại Thanh Hải, giá trị cốt lõi vẫn nằm ở đôi bàn tay con người.',
                    ],
                    [
                        'type' => 'paragraph',
                        'content' => 'Chúng tôi kiên trì giữ vững phương thức thủ công truyền thống trong các khâu quan trọng. Mỗi sản phẩm đều mang dấu ấn riêng biệt, có chiều sâu và sự ấm áp mà những dây chuyền công nghiệp đại trà không bao giờ có được.',
                    ],
                ],
                'process_bottom_image' => 'assets/images/gach-co-work-2.jpg',
                'material_slider' => [
                    'assets/images/factory-03.png',
                    'assets/images/factory-04.jpg',
                    'assets/images/factory-04.jpg',
                ],
                'material_steps' => [
                    [
                        'number' => '1',
                        'title' => 'LỰA CHỌN VÀ XỬ LÝ NGUYÊN LIỆU (PHA CHẾ ĐẤT)',
                        'description' => 'Đây là bước quan trọng nhất quyết định độ bền của sản phẩm. Thanh Hải tuyển chọn những loại đất sét có độ dẻo cao, khả năng chịu nhiệt tốt từ các vùng nguyên liệu nổi tiếng như Trúc Thôn. Đất được xử lý qua hệ thống bể lọc để loại bỏ tạp chất, sau đó pha trộn theo tỷ lệ bí truyền để tạo ra "xương" gốm vững chắc.',
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
                ],
            ]
        );
    }

    private function seedPageContact(): void
    {
        PageContact::firstOrCreate(
            ['page_contact_id' => 1],
            [
                'map_image' => 'assets/images/contact-map.png',
                'hotline' => '0966 55 8808',
                'zalo_link' => 'https://zalo.me/0966558808',
                'form_title' => 'Hãy nói với chúng tôi những mong muốn của bạn',
            ]
        );
    }

    private function seedPageFaq(): void
    {
        PageFaq::firstOrCreate(
            ['page_faq_id' => 1],
            ['banner_image' => 'assets/images/faq-banner.png']
        );
    }

    private function seedFaqs(): void
    {
        $faqs = $this->faqData();

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question'], 'category' => $faq['category']],
                $faq
            );
        }
    }

    private function faqData(): array
    {
        return [
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
                'answer' => 'Tất cả sản phẩm của chúng tôi đều được nung ở nhiệt độ cao (1200°C), đảm bảo độ bền tuyệt đối khi sử dụng ngoài trời. Chất liệu đất sét Bát Tràng kết hợp men hỏa biến giúp sản phẩm chống thấm nước, chống rêu mốc vĩnh viễn.',
                'sort_order' => 4,
            ],
            [
                'category' => 'sản-phẩm',
                'question' => 'Màu men có bị phai dưới ánh nắng mặt trời không?',
                'answer' => 'Lớp men gốm được nung hỏa biến ở nhiệt độ 1200°C, cam kết không bao giờ phai màu dưới tác động của thời tiết. Màu men hòa quyện vào xương gốm trong quá trình nung, tạo nên độ bền màu vĩnh cửu.',
                'sort_order' => 5,
            ],

            // Category: Báo giá & Đặt hàng
            [
                'category' => 'báo-giá',
                'question' => 'Giá sản phẩm được tính như thế nào?',
                'answer' => 'Giá gốm sứ xây dựng thường được tính theo mét vuông (m²), mét dài (md) hoặc theo viên/cặp đối với các dòng gạch, ngói và tính theo đơn vị đôi/chiếc đối với các sản phẩm đơn lẻ. Giá phụ thuộc vào kích thước, loại men, và độ phức tạp của hình dáng sản phẩm.',
                'sort_order' => 1,
            ],
            [
                'category' => 'báo-giá',
                'question' => 'Đặt hàng số lượng lớn có được chiết khấu không?',
                'answer' => 'Chúng tôi luôn có chính sách chiết khấu linh hoạt và cạnh tranh cho các đơn hàng số lượng lớn, đặc biệt là các dự án công trình trọng điểm. Vui lòng liên hệ trực tiếp để nhận báo giá ưu đãi nhất.',
                'sort_order' => 2,
            ],
            [
                'category' => 'báo-giá',
                'question' => 'Có yêu cầu số lượng đặt hàng tối thiểu không?',
                'answer' => 'Chúng tôi tiếp nhận mọi đơn hàng, từ một sản phẩm đơn lẻ đến các đơn hàng lớn cho công trình quy mô hàng nghìn mét vuông.',
                'sort_order' => 3,
            ],
            [
                'category' => 'báo-giá',
                'question' => 'Màu sắc có ảnh hưởng đến giá sản phẩm không?',
                'answer' => 'Một số màu men hỏa biến đặc biệt hoặc yêu cầu pha chế màu riêng theo thiết kế có thể có sự chênh lệch nhẹ về giá so với các màu men tiêu chuẩn.',
                'sort_order' => 4,
            ],
            [
                'category' => 'báo-giá',
                'question' => 'Tại sao kích thước nhỏ lại đắt hơn kích thước lớn?',
                'answer' => 'Kích thước nhỏ đòi hỏi sự tỉ mỉ cao hơn trong khâu tạo hình và hoàn thiện thủ công. Công sức cho mỗi cm² sản phẩm nhỏ lớn hơn đáng kể, đồng thời tỷ lệ hao hụt trong quá trình nung cũng cao hơn.',
                'sort_order' => 5,
            ],

            // Category: Vận chuyển & Lắp đặt
            [
                'category' => 'vận-chuyển',
                'question' => 'Thời gian sản xuất và giao hàng là bao lâu?',
                'answer' => 'Hàng có sẵn: giao trong 2-5 ngày làm việc. Hàng đặt sản xuất: thường mất 3-6 tuần tùy quy mô đơn hàng và điều kiện thời tiết (ảnh hưởng đến quá trình phơi gốm mộc ngoài trời).',
                'sort_order' => 1,
            ],
            [
                'category' => 'vận-chuyển',
                'question' => 'Các bạn có giao hàng toàn quốc không?',
                'answer' => 'Chúng tôi vận chuyển toàn quốc bằng xe tải chuyên dụng hoặc đối tác logistic uy tín. Hàng hóa được đóng gói cẩn thận, đảm bảo an toàn trong suốt quá trình vận chuyển.',
                'sort_order' => 2,
            ],
            [
                'category' => 'vận-chuyển',
                'question' => 'Tôi nên lưu ý gì khi lắp đặt gốm thủ công?',
                'answer' => 'Nên sử dụng thợ có tay nghề và am hiểu đặc tính gốm nung thủ công. Chúng tôi luôn cung cấp tài liệu hướng dẫn lắp đặt chi tiết kèm theo mỗi đơn hàng.',
                'sort_order' => 3,
            ],
            [
                'category' => 'vận-chuyển',
                'question' => 'Các bạn có vận chuyển quốc tế không?',
                'answer' => 'Có. Chúng tôi hỗ trợ đóng gói kiện gỗ xuất khẩu đạt chuẩn và làm thủ tục hải quan cần thiết cho các đơn hàng quốc tế.',
                'sort_order' => 4,
            ],
            [
                'category' => 'vận-chuyển',
                'question' => 'Tôi có thể tự đến lấy hàng trực tiếp không?',
                'answer' => 'Quý khách có thể nhận hàng trực tiếp tại xưởng sản xuất hoặc showroom của chúng tôi. Vui lòng liên hệ trước để chúng tôi chuẩn bị hàng sẵn sàng.',
                'sort_order' => 5,
            ],
        ];
    }
}
