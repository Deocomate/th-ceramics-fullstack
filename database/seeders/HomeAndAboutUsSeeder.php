<?php

namespace Database\Seeders;

use App\Models\TrangChu;
use App\Models\VeChungToi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeAndAboutUsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Làm sạch dữ liệu cũ
        TrangChu::truncate();
        VeChungToi::truncate();

        // 2. Dữ liệu Trang chủ
        TrangChu::create([
            // [QUAN TRỌNG] Các trường thư viện ảnh đều phải là mảng 1 chiều chứa chuỗi (String array)
            'banner' =>[
                'assets/images/ngoi-am-duong-banner.jpg',
            ],
            'khach_hang_doi_tac' =>[
                'assets/images/partner-01.png',
                'assets/images/partner-02.png',
                'assets/images/partner-03.png',
            ],
            'loi_tri_an' =>[
                'Thanh Hải Ceramics xin gửi lời tri ân sâu sắc nhất đến Quý khách hàng đã luôn đồng hành cùng chúng tôi.',
                'Với hơn 10 năm phát triển, chúng tôi cam kết tiếp tục giữ gìn và phát huy tinh hoa làng nghề Bát Tràng.',
            ],
            'loi_tri_an_anh' => 'assets/images/am-duong-detail-01.png',
            
            've_chung_toi_logo' =>[
                'assets/images/partner-01.png',
                'assets/images/partner-02.png',
                'assets/images/partner-03.png',
            ],
            'video' => 'https://www.youtube.com/watch?v=yY6m-W6kXEA',
            
            // Chỉ riêng "nhung_con_so" là mảng của các objects
            'nhung_con_so' => [
                ['head' => '10+', 'body' => 'Năm Kinh Nghiệm'],
                ['head' => '5000+', 'body' => 'Khách Hàng'],['head' => '200+', 'body' => 'Đối Tác'],['head' => '50+', 'body' => 'Sản Phẩm'],['head' => '100%', 'body' => 'Sự Hài Lòng'],
            ],
            
            // Showroom cũng là mảng 1 chiều chứa chuỗi (String array)
            'showroom_images' =>[
                'assets/images/showroom-01.jpg',
                'assets/images/showroom-02.jpg',
            ],
            'showroom_noidung' => 'Showroom Thanh Hải trưng bày đầy đủ các mẫu gạch ngói cao cấp nhất hiện nay.',
        ]);

        // 3. Dữ liệu Về Chúng Tôi
        VeChungToi::create([
            'banner' => 'assets/images/about-banner.jpg',
            'header_banner' => 'Câu Chuyện Thanh Hải',
            'body_banner' => 'Hành trình gìn giữ và phát triển tinh hoa gốm sứ Bát Tràng...',
            
            // Các block nội dung chi tiết là mảng objects
            'gs_head' => [['image' => 'assets/images/about-01.png', 'head' => 'Tầm Nhìn', 'body' => 'Trở thành thương hiệu hàng đầu cung cấp vật liệu gốm sứ.'],['image' => 'assets/images/about-02.jpg', 'head' => 'Sứ Mệnh', 'body' => 'Góp phần bảo tồn và tôn vinh nét đẹp kiến trúc truyền thống.'],
            ],
            'gs_gia_tri' =>[['image' => 'assets/images/value-01.png', 'head' => 'Chất lượng', 'body' => 'Cam kết độ bền bỉ cùng thời gian.'],['image' => 'assets/images/value-02.png', 'head' => 'Sáng tạo', 'body' => 'Không ngừng cập nhật xu hướng.'],
            ],
            'gs_hanh_trinh' => [['image' => 'assets/images/value-01.png', 'head' => '2010', 'body' => 'Xưởng gốm sứ Thanh Hải chính thức ra đời tại Bát Tràng.'],
            ],
            
            'gs_nguoi_sang_lap_anh' => 'assets/images/ceo.jpg',
            'gs_nguoi_sang_lap_noi_dung' => 'Với xuất thân từ cái nôi gốm sứ Bát Tràng, người sáng lập luôn mang trong mình khao khát cháy bỏng...',
            
            'gs_giai_thuong' => [['image' => 'assets/images/award-01.jpg', 'head' => 'Năm 2024', 'body' => 'Giải thưởng Nghệ nhân Bát Tràng xuất sắc.'],
            ],
            
            'nt_head' => 'Nghệ thuật chế tác thủ công',
            'nt_body' => 'Khám phá bí mật tạo nên những tuyệt tác...',
            'nt_ngon_ngu' => [['image' => 'assets/images/cong-doan-01.jpg', 'head' => 'Ngôn Ngữ Của Đất', 'body' => 'Từ những khối đất vô tri, qua bàn tay nghệ nhân trở nên có hồn.'],
            ],
            
            'nt_che_tac_head' => 'Khâu Chế Tác',
            'nt_che_tac_body' => 'Mỗi sản phẩm đều được trau chuốt từng đường nét.',
            // Thư viện ảnh (Flat array)
            'nt_che_tac_anh' =>[
                'assets/images/cong-doan-01.jpg',
                'assets/images/cong-doan-02.jpg',
            ],
            
            'nt_luyen_dat_head' => 'Luyện Đất',
            'nt_luyen_dat_body' => 'Quy trình xử lý đất sét tuân thủ nguyên tắc nghiêm ngặt.',
            'nt_luyen_dat_item' => [['image' => 'assets/images/cong-doan-02.jpg', 'head' => 'Bước 1', 'body' => 'Lọc tạp chất tinh khiết'],
            ],
            
            'nt_dun_lo_head' => 'Đun Lò',
            'nt_dun_lo_body' => 'Ngọn lửa là linh hồn quyết định màu sắc hỏa biến.',
            // Thư viện ảnh (Flat array)
            'nt_dun_lo_anh' =>[
                'assets/images/cong-doan-01.jpg',
                'assets/images/cong-doan-02.jpg',
            ],
        ]);
    }
}