<?php

namespace Database\Seeders;

use App\Models\TrangChu;
use Illuminate\Database\Seeder;

class HomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TrangChu::updateOrCreate(
            ['trang_chu_id' => 1],
            [
                'banner' => [
                    'seeders/home/home-banner-1.png',
                    'seeders/home/home-banner-2.png'
                ],

                'khach_hang_doi_tac' => [
                    'seeders/home/khach-hang-doi-tac-1.png',
                    'seeders/home/khach-hang-doi-tac-2.png',
                    'seeders/home/khach-hang-doi-tac-3.png',
                    'seeders/home/khach-hang-doi-tac-4.png',
                    'seeders/home/khach-hang-doi-tac-5.png'
                ],

                'loi_tri_an' => [
                    'Hơn 40 năm gắn bó với gốm sứ trang trí nội - ngoại thất, chúng tôi tin rằng mỗi sản phẩm không chỉ là vật liệu xây dựng, mà là kết tinh của đất, lửa và tấm lòng người làm nghề.',
                    'Sinh ra và lớn lên tại làng gốm Bát Tràng, nơi lưu giữ di sản nghề gốm được truyền qua nhiều thế hệ, chúng tôi thừa hưởng nền tảng thủ công truyền thống cùng nguồn nguyên liệu quý giá của quê hương. Là người kế thừa thể hệ thứ ba của dòng họ Vũ Gia, tôi tiếp bước con đường cha ông đã gây dựng, gìn giữ và làm giàu thêm giá trị di sản gốm Việt, đặc biệt là ngói âm dương loại ngói gắn liền với kiến trúc cung đình, đền chùa từ thời Lý – Trần đến nay.',
                    'Từ ngói lợp gốm sứ, gạch hoa thông gió đến các phụ kiện trang trí, mỗi sản phẩm đều là sự giao thoa giữa di sản truyền thống và tinh thần đương đại. Hành trình ấy đã đưa sản phẩm của chúng tôi hiện diện trên khắp mọi miền đất nước, trở thành một phần trong nhiều công trình mang đậm bản sắc và thẩm mỹ Việt.'
                ],

                'loi_tri_an_anh' => 'seeders/home/loi-tri-an-image.png',

                've_chung_toi_logo' => [
                    'seeders/home/bao-chi-1.png',
                    'seeders/home/bao-chi-2.png',
                    'seeders/home/bao-chi-3.png',
                    'seeders/home/bao-chi-4.png',
                    'seeders/home/bao-chi-5.png'
                ],

                'video' => "https://www.youtube.com/watch?v=yY6m-W6kXEA",

                'nhung_con_so' => [
                    [
                        'head' => '10+',
                        'body' => 'Năm Kinh Nghiệm'
                    ],
                    [
                        'head' => '5000+',
                        'body' => 'Khách Hàng'
                    ],
                    [
                        'head' => '200+',
                        'body' => 'Đối Tác'
                    ],
                    [
                        'head' => '50+',
                        'body' => 'Sản Phẩm'
                    ],
                    [
                        'head' => '100%',
                        'body' => 'Sự Hài Lòng'
                    ]
                ],

                'showroom_images' => [
                    'seeders/home/showroom-1.png',
                    'seeders/home/showroom-2.png',
                    'seeders/home/showroom-3.png'
                ],

                'showroom_noidung' => 'Xưởng sản xuất và showroom Thanh Hải Ceramics là nơi quá khứ và hiện tại giao thoa. Từ ngọn lửa lò nung Bát Tràng, những viên ngói âm dương ra đời, mang theo tinh hoa làng nghề, dấu ấn kiến trúc Việt và câu chuyện di sản sống động. Trong từng lớp ngói xếp là ký ức của thời gian, được tiếp nối bằng bàn tay người thợ hôm nay để gìn giữ và lan tỏa giá trị truyền thống.',
            ]
        );
    }
}