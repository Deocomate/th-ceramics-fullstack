<?php

namespace Database\Seeders;

use App\Models\GiaiThuongThanhTuu;
use App\Models\GiaTriVuotTroi;
use App\Models\TrangChu;
use App\Models\VeChungToi;
use Illuminate\Database\Seeder;

class HomeAndAboutUsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTrangChu();
        $this->seedVeChungToi();
        $this->seedGiaiThuongThanhTuu();
        $this->seedGiaTriVuotTroi();
    }

    private function seedTrangChu(): void
    {
        TrangChu::firstOrCreate(
            ['trang_chu_id' => 1],
            [
                'banner' => [
                    ['image' => 'assets/images/home-hero-01.png', 'alt' => 'TH Ceramics - Gốm Việt Trường Tồn'],
                ],
                'khach_hang_doi_tac' => [
                    ['image' => 'assets/images/partner-01.png', 'alt' => 'Đối tác 1'],
                    ['image' => 'assets/images/partner-02.png', 'alt' => 'Đối tác 2'],
                    ['image' => 'assets/images/partner-03.png', 'alt' => 'Đối tác 3'],
                    ['image' => 'assets/images/partner-04.png', 'alt' => 'Đối tác 4'],
                    ['image' => 'assets/images/partner-05.png', 'alt' => 'Đối tác 5'],
                ],
                'loi_tri_an' => [
                    'Hơn 40 năm gắn bó với gốm sứ trang trí nội - ngoại thất, chúng tôi tin rằng mỗi sản phẩm không chỉ là vật liệu xây dựng, mà là kết tinh của đất, lửa và tấm lòng người làm nghề.',
                    'Sinh ra và lớn lên tại làng gốm Bát Tràng, nơi lưu giữ di sản nghề gốm được truyền qua nhiều thế hệ, chúng tôi thừa hưởng nền tảng thủ công truyền thống cùng nguồn nguyên liệu quý giá của quê hương. Là người kế thừa thế hệ thứ ba của dòng họ Vũ Gia, tôi tiếp bước con đường cha ông đã gây dựng, gìn giữ và làm giàu thêm giá trị di sản gốm Việt, đặc biệt là ngói âm dương - loại ngói gắn liền với kiến trúc cung đình, đền chùa từ thời Lý – Trần đến nay.',
                    'Từ ngói lợp gốm sứ, gạch hoa thông gió đến các phụ kiện trang trí, mỗi sản phẩm đều là sự giao thoa giữa di sản truyền thống và tinh thần đương đại. Hành trình ấy đã đưa sản phẩm của chúng tôi hiện diện trên khắp mọi miền đất nước, trở thành một phần trong nhiều công trình mang đậm bản sắc và thẩm mỹ Việt.',
                ],
                'loi_tri_an_anh' => 'assets/images/ceo.jpg',
                've_chung_toi_logo' => [
                    ['image' => 'assets/images/logo.png', 'alt' => 'TH Ceramics Logo'],
                ],
                'video' => null,
                'nhung_con_so' => [
                    ['head' => '1000', 'body' => 'Công trình lớn nhỏ'],
                    ['head' => '5000m²', 'body' => 'Nhà xưởng'],
                    ['head' => '100%', 'body' => 'Sản xuất thủ công'],
                    ['head' => '50', 'body' => 'Dòng sản phẩm'],
                    ['head' => '40', 'body' => 'Năm kinh nghiệm'],
                ],
                'showroom_images' => [
                    ['image' => 'assets/images/showroom-01.jpg', 'des' => 'Showroom Hà Nội - 286 Nguyễn Xiển'],
                    ['image' => 'assets/images/showroom-02.jpg', 'des' => 'Không gian trưng bày sản phẩm ngói'],
                    ['image' => 'assets/images/showroom-03.png', 'des' => 'Gạch trang trí cao cấp'],
                ],
                'showroom_noidung' => 'Xưởng sản xuất và showroom Thanh Hải Ceramics là nơi quá khứ và hiện tại giao thoa. Từ ngọn lửa lò nung Bát Tràng, những viên ngói âm dương ra đời, mang theo tinh hoa làng nghề, dấu ấn kiến trúc Việt và câu chuyện di sản sống động. Trong từng lớp ngói xếp là ký ức của thời gian, được tiếp nối bằng bàn tay người thợ hôm nay để gìn giữ và lan tỏa giá trị truyền thống.',
            ]
        );
    }

    private function seedVeChungToi(): void
    {
        VeChungToi::firstOrCreate(
            ['ve_chung_toi_id' => 1],
            [
                'banner' => 'assets/images/about-banner.jpg',
                'header_banner' => 'GỐM SỨ THANH HẢI',
                'body_banner' => "40 NĂM - LỬA NGHỀ\nCHƯA BAO GIỜ TẮT",
                'gs_head' => [
                    [
                        'image' => 'assets/images/about-01.png',
                        'head' => 'Những công việc giản dị và ngọn lửa nghề luôn ấm',
                        'body' => 'Từ những bàn tay khéo léo của người thợ Việt tới ngôi nhà của bạn. Từ những ngày làm thuê trong xí nghiệp gốm, nghệ nhân Vũ Mạnh Hải và Nguyễn Thị Thanh – hai người sáng lập đầy tâm huyết – đã sớm nuôi dưỡng khát khao tạo ra những sản phẩm của riêng mình. Những mẫu lan can gốm sứ đầu tiên, được tự tay thiết kế, sản xuất và mang đi chào hàng trên phố Cát Linh, đã đặt nền móng cho hành trình theo nghề nhiều thử thách nhưng bền bỉ.',
                    ],
                    [
                        'image' => 'assets/images/about-02.jpg',
                        'head' => 'Kiên định một con đường, bền vững qua thời gian',
                        'body' => "Dù Bát Tràng đã trải qua nhiều giai đoạn chuyển đổi và thử nghiệm các loại hành gốm sứ khác nhau, Thanh Hải vẫn kiên định với lựa chọn ban đầu: gốm sứ xây dựng – gạch và ngói – làm nền tảng phát triển lâu dài.\nMỗi sản phẩm được nghiên cứu kỹ về chất liệu và kỹ thuật, đồng thời được tinh chỉnh trong thiết kế để phù hợp với kiến trúc hôm nay. Thanh Hải tin rằng giá trị bền vững nhất nằm ở sự kết hợp hài hòa giữa di sản nghề gốm và hơi thở của thời đại.",
                    ],
                ],
                'gs_gia_tri' => [
                    [
                        'image' => 'assets/images/gia-tri-vuot-troi-01.jpg',
                        'head' => 'Tính kế thừa',
                        'body' => 'Gốm sứ xây dựng Thanh Hải là câu chuyện được tiếp nối qua nhiều thế hệ. Từ những ngày đầu cha ông nhóm lửa lò nung, qua năm tháng thế hệ kế thừa vẫn bền bỉ tích lũy kinh nghiệm. Mỗi thế hệ sau không chỉ học cách làm gốm, mà còn học cách tôn trọng nghề, giữ chữ tín và làm ra những sản phẩm đủ bền để đi cùng thời gian. Chính sự kế thừa lặng lẽ nhưng bền bỉ ấy đã tạo nên Thanh Hải của hôm nay.',
                    ],
                    [
                        'image' => 'assets/images/gia-tri-vuot-troi-02.jpg',
                        'head' => 'Nghệ thuật thủ công',
                        'body' => '"Nghệ thuật thủ công" là giá trị cốt lõi khiến sản phẩm Gốm sứ xây dựng của Thanh Hải khác biệt với các vật liệu công nghiệp trên thị trường. Từ đất thô qua bàn tay người thợ, mỗi viên gạch, viên ngói được chế tác bằng sự am hiểu vật liệu, kết hợp kỹ thuật nung truyền thống và sự tuyển chọn nguyên liệu hàng đầu nhằm đảm bảo độ bền, tính thẩm mỹ và giá trị sử dụng lâu dài cho từng công trình.',
                    ],
                    [
                        'image' => 'assets/images/gia-tri-vuot-troi-03.jpg',
                        'head' => 'Giá trị vượt thời gian',
                        'body' => 'Với Thanh Hải, mỗi viên gạch, viên ngói không chỉ để dựng nhà, mà để gìn giữ một tổ ấm. Đó là nơi che mưa nắng, chứng kiến những bữa cơm sum họp và những đổi thay của đời người theo năm tháng. Chính vì vậy, từng sản phẩm đều được làm ra bằng sự nâng niu và trách nhiệm, đủ bền để ở lại cùng gia đình qua nhiều thế hệ. Với Thanh Hải, góp phần xây tổ ấm cho người khác luôn là một sứ mệnh cao cả.',
                    ],
                ],
                'gs_hanh_trinh' => [
                    [
                        'image' => 'assets/images/hanh-trinh-01.jpg',
                        'head' => '1985',
                        'body' => 'Hai người sáng lập là ông Vũ Mạnh Hải và bà Nguyễn Thị Thanh bắt đầu được giao lại toàn bộ công việc sản xuất gốm sứ từ cụ Vũ Đình Sơn. Nhận thấy thị trường sản xuất bát đĩa đang dần bão hòa và nhiều cạnh tranh, ông bà bắt đầu tìm hướng đi mới thông qua việc tự thiết kế mẫu mã mới cho dòng sản phẩm gốm sứ xây dựng, gốm sứ trang trí – đặt những viên gạch đầu tiên cho con đường làm nghề.',
                    ],
                    [
                        'image' => 'assets/images/hanh-trinh-02.jpg',
                        'head' => '1993',
                        'body' => 'Cửa hàng bán lẻ đầu tiên được mở ra, đánh dấu bước phát triển mới trong hoạt động kinh doanh. Từ đây, mẫu mã không ngừng được nghiên cứu và sáng tạo, chủng loại sản phẩm ngày càng đa dạng nhằm đáp ứng các nhu cầu khác nhau của khách hàng. Đồng thời, Gốm Sứ Thanh Hải dần tạo được sự tín nhiệm từ các doanh nghiệp lớn và được giao thực hiện những công trình quy mô đầu tiên.',
                    ],
                    [
                        'image' => 'assets/images/hanh-trinh-03.jpg',
                        'head' => '2000',
                        'body' => 'Công ty TNHH Sản xuất và Thương mại Thanh Hải chính thức được thành lập, đánh dấu bước chuyển quan trọng sang mô hình hoạt động chuyên nghiệp. Hệ thống xưởng sản xuất gốm sứ xây dựng được đầu tư đồng bộ, từng bước áp dụng quy trình để nâng cao tay nghề thợ thủ công, mở rộng quy mô sản xuất.',
                    ],
                    [
                        'image' => 'assets/images/hanh-trinh-04.jpg',
                        'head' => '2008',
                        'body' => 'Showroom chuyên nghiệp đầu tiên được xây dựng tại số 42 Phố Gốm, đánh dấu bước phát triển trong việc nâng cao trải nghiệm tham quan và giới thiệu sản phẩm gốm sứ Thanh Hải. Từ đây, không gian trưng bày được chú trọng đầu tư bài bản, sắp đặt sản phẩm theo từng nhóm ứng dụng và giá trị thẩm mỹ, góp phần thể hiện rõ nét hơn tinh thần và chất lượng của thương hiệu.',
                    ],
                    [
                        'image' => 'assets/images/hanh-trinh-05.jpg',
                        'head' => '2024',
                        'body' => 'Showroom thứ hai với diện tích gần 400m² tại số 18 Phố Gốm được hình thành như một không gian trưng bày và kể chuyện di sản. Tại đây, ngói âm dương được tái hiện thông qua việc kết hợp ngói âm và gạch xây cũ, tạo nên các họa tiết mô phỏng ngói thời Lý – Trần, dấu ấn tiêu biểu của kiến trúc cổ Việt Nam. Không gian vừa là nơi giới thiệu sản phẩm, vừa là điểm kết nối văn hóa, lưu giữ và lan tỏa giá trị kiến trúc truyền thống.',
                    ],
                ],
                'gs_nguoi_sang_lap_anh' => 'assets/images/ceo.jpg',
                'gs_nguoi_sang_lap_noi_dung' => 'Trải qua nhiều thăng trầm của nghề, ông Vũ Mạnh Hải và bà Nguyễn Thị Thanh vẫn bền bỉ đồng hành, kiên định theo đuổi dòng sản phẩm gốm sứ xây dựng. Dù ngói thủ công không phải là sản phẩm phổ biến, Thanh Hải vẫn xây dựng được một cộng đồng khách hàng trung thành và tin cậy, thể hiện qua việc được lựa chọn cho nhiều công trình lớn nhỏ trên cả nước như Chùa Bái Đính, Bệnh viện Y học cổ truyền Quân đội, Viện 103, Vinpearl Land Nha Trang. Những dấu ấn ấy đã đưa hai nhà sáng lập đến niềm vinh dự khi lần lượt được UBND Thành phố Hà Nội trao tặng danh hiệu Nghệ nhân Hà Nội vào các năm 2023 và 2025.',
                'gs_giai_thuong' => [
                    ['image' => 'assets/images/award-01.jpg', 'head' => '2016'],
                    ['image' => 'assets/images/award-02.jpg', 'head' => '2016'],
                    ['image' => 'assets/images/award-03.jpg', 'head' => '2016'],
                ],
                'nt_head' => 'Từ vật liệu xây dựng đến di sản kiến trúc',
                'nt_body' => 'Chúng tôi dành nhiều năm nghiên cứu để phục chế mẫu ngói âm dương lấy cảm hứng từ ngói thời Lý – Trần, kết hợp đối chiếu tư liệu kiến trúc cổ và hệ mái Kinh thành Huế. Bắt đầu từ những bản vẽ và mẫu thử đầu tiên, từng viên ngói được chế tác thủ công, đo đạc tỷ lệ, kiểm tra độ thoát nước, độ bền và khả năng thích ứng với kiến trúc đương đại. Không chỉ tái hiện hình dáng cổ xưa, chúng tôi hướng tới việc giữ lại tinh thần của ngói âm dương truyền thống, đồng thời nâng cao chất lượng để mỗi mái ngói vừa mang giá trị di sản, vừa đáp ứng yêu cầu sử dụng lâu dài trong công trình hôm nay.',
                'nt_ngon_ngu' => [
                    [
                        'image' => 'assets/images/dat-set-bat-trang.jpg',
                        'head' => 'Ngôn ngữ của vật liệu',
                        'body' => 'Chúng tôi hiểu rằng đất chính là cốt lõi làm nên hồn của mỗi sản phẩm. Dù không dễ để thuyết phục khách hàng ngay từ cái nhìn đầu tiên rằng giá trị của Thanh Hải nằm sâu trong lớp đất được tuyển chọn kỹ lưỡng, chúng tôi chọn để thời gian lên tiếng. Giá trị ấy được chứng minh bằng độ bền qua năm tháng và bằng các chứng chỉ kiểm nghiệm độc lập. Nếu men là vẻ đẹp thu hút ánh nhìn ban đầu, thì đất chính là chiều sâu lặng lẽ – đủ để giữ khách hàng ở lại lâu dài cùng chúng tôi.',
                    ],
                ],
                'nt_che_tac_head' => 'NGHỆ THUẬT CHẾ TÁC THỦ CÔNG ĐIÊU LUYỆN',
                'nt_che_tac_body' => 'Nghệ thuật chế tác của Thanh Hải bắt đầu từ những đôi tay thuần thục mỗi ngày. Dù thị trường ngày càng đòi hỏi tốc độ và sản lượng, chúng tôi vẫn giữ lại gần như trọn vẹn các công đoạn thủ công, vì đó là cách duy nhất để mỗi sản phẩm còn giữ được "chất" của nghề. Những đôi tay cạo hàng thuần thục, những đôi tay dội men uyển chuyển, hay đôi tay rắn rỏi đổ khuôn mỗi ngày - tất cả đều mang theo sự tập trung, kiên nhẫn và tình yêu nghề. Chúng tôi khác biệt ngay từ chất liệu ban đầu, và lựa chọn gìn giữ sự khác biệt ấy đến tận cùng. Men và lửa, qua những biến thiên tự nhiên, tạo nên sắc độ đậm nhạt không lặp lại - mộc mạc, thuần khiết như chính thiên nhiên ban tặng.',
                'nt_che_tac_anh' => [
                    'assets/images/che-tac-01.png',
                    'assets/images/che-tac-02.jpg',
                ],
                'nt_luyen_dat_head' => 'Kỹ thuật luyện đất',
                'nt_luyen_dat_body' => 'Đất là gốc rễ, nền tảng của mọi sản phẩm gốm chất lượng. Chúng tôi tuyển chọn nguyên liệu đất sét tinh khiết, loại bỏ tạp chất để đảm bảo độ dẻo, độ bám và khả năng chịu nhiệt vượt trội. Đất sau khi được ngâm, lắng và luyện qua nhiều giai đoạn xử lý truyền thống sẽ cho ra hỗn hợp mịn, ổn định, sẵn sàng cho từng bước tạo hình, nung và hoàn thiện.',
                'nt_luyen_dat_item' => [
                    [
                        'image' => 'assets/images/luyen-dat-01.jpg',
                        'head' => 'Kỹ thuật tạo hình',
                        'body' => 'Tạo hình bắt đầu từ bản vẽ, nơi tỉ lệ và hình dáng sản phẩm được tính toán kỹ lưỡng. Để tạo hình hàng loạt, nghệ nhân sẽ bắt đầu với cốt sản phẩm. Phần cốt này sẽ được điêu khắc hoàn toàn bằng tay với nguyên liệu chính là thạch cao. Cốt càng sắc nét, khuôn thạch cao càng chuẩn, hàng càng đẹp. Đất sau luyện được đổ rót vào khuôn. Cách để kiểm soát độ dày đồng đều giữa các sản phẩm phụ thuộc nhiều vào khuôn và tay nghề cũng như sức bền của mỗi người thợ đổ rót.',
                    ],
                    [
                        'image' => 'assets/images/luyen-dat-02.jpg',
                        'head' => 'Kỹ thuật tráng men',
                        'body' => 'Mỗi nghệ nhân sẽ có một bí quyết pha men riêng, kế thừa từ cha ông và tiếp tục sáng tạo trên nền tảng đó. Thông thường, men sẽ được nghiền một cách cẩn thận, sau đó trộn, lọc kỹ rồi mới pha theo tỷ lệ để tạo nên màu sắc và bề mặt khác nhau. Với ngói, chúng tôi phát triển hơn hai mươi màu men, quý khách hàng hoàn toàn có thể lựa chọn theo ngũ hành, hay tính chất từng công trình như: Chùa chiền, Từ đường hay Khu nghỉ dưỡng.',
                    ],
                ],
                'nt_dun_lo_head' => 'Kỹ thuật đun lò',
                'nt_dun_lo_body' => 'Công đoạn nung sản phẩm trong lò là bước quyết định chất lượng gốm của Thanh Hải. Lò được đun ở nhiệt độ cao, có thể lên tới 1.300°C, đòi hỏi đất và men đạt chuẩn để chịu nhiệt bền vững. Việc tăng – giữ – hạ nhiệt theo từng giai đoạn là bí quyết riêng của các nghệ nhân. Sau khi nung, sản phẩm được ủ chậm khoảng 12 giờ để nguội dần, giúp gốm đanh chắc, hạn chế nứt vỡ và bền bỉ theo thời gian.',
                'nt_dun_lo_anh' => [
                    'assets/images/dun-lo-01.png',
                    'assets/images/dun-lo-02.jpg',
                ],
            ]
        );
    }

    private function seedGiaiThuongThanhTuu(): void
    {
        $awards = [
            ['image' => 'assets/images/award-01.jpg', 'des' => 'Ông Vũ Mạnh Hải - Nghệ nhân Hà Nội năm 2024 do UBND thành phố Hà Nội trao tặng'],
            ['image' => 'assets/images/award-02.jpg', 'des' => 'Sản phẩm công nghiệp nông thôn tiêu biểu 2023'],
            ['image' => 'assets/images/award-03.jpg', 'des' => 'Bà Nguyễn Thị Thanh - Nghệ nhân Hà Nội năm 2025 do UBND thành phố Hà Nội trao tặng'],
            ['image' => 'assets/images/award-05.jpg', 'des' => 'Top 10 thương hiệu gốm sứ uy tín 2024'],
        ];

        foreach ($awards as $award) {
            GiaiThuongThanhTuu::firstOrCreate(
                ['des' => $award['des']],
                ['image' => $award['image']]
            );
        }
    }

    private function seedGiaTriVuotTroi(): void
    {
        $values = [
            [
                'title' => 'Đất sét Bát Tràng nguyên chất',
                'desscription' => 'Sử dụng 100% đất sét trắng Bát Tràng, khai thác tại địa phương, đảm bảo độ dẻo và độ kết dính tự nhiên. Nguyên liệu được tuyển chọn kỹ lưỡng qua quy trình lắng lọc 3 giai đoạn.',
                'image' => 'assets/images/gia-tri-vuot-troi-01.jpg',
            ],
            [
                'title' => 'Nung ở 1200°C - Độ cứng vượt trội',
                'desscription' => 'Công nghệ lò nung tuynel đạt 1200°C giúp sản phẩm đạt độ cứng tối ưu, chống thấm nước, chống rêu mốc vĩnh viễn. Quy trình nung kéo dài 72 giờ đảm bảo xương gốm đanh chắc từ trong ra ngoài.',
                'image' => 'assets/images/gia-tri-vuot-troi-02.jpg',
            ],
            [
                'title' => 'Men hỏa biến độc quyền',
                'desscription' => 'Kỹ thuật tráng men thủ công kết hợp biến nhiệt tạo hiệu ứng hoả biến độc nhất. Mỗi viên ngói là một tác phẩm nghệ thuật không thể sao chép, với sắc độ và vân men riêng biệt.',
                'image' => 'assets/images/gia-tri-vuot-troi-03.jpg',
            ],
            [
                'title' => 'Bảo hành 20 năm - Trường tồn cùng thời gian',
                'desscription' => 'Cam kết bảo hành chống phai màu, chống thấm nước 20 năm. Sản phẩm đã kiểm chứng qua hơn 500 công trình trên toàn quốc, từ đình chùa di tích đến biệt thự nghỉ dưỡng cao cấp.',
                'image' => 'assets/images/gia-tri-vuot-troi-04.jpg',
            ],
        ];

        foreach ($values as $v) {
            GiaTriVuotTroi::firstOrCreate(
                ['title' => $v['title']],
                [
                    'desscription' => $v['desscription'],
                    'image' => $v['image'],
                ]
            );
        }
    }
}
