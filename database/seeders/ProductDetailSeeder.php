<?php

namespace Database\Seeders;

use App\Models\BoNocChuVanCt;
use App\Models\GachCoBatTrangCt;
use App\Models\GachHoaThongGioCt;
use App\Models\GachTrangTriCt;
use App\Models\LinhVatPhongThuyCt;
use App\Models\MauSacNgoiHaiCoCt;
use App\Models\MauSacNgoiHaiVanMieuCt;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiBoNocCt;
use App\Models\NgoiHaiCoCt;
use App\Models\NgoiHaiVanMieuCt;
use App\Models\PhanLoaiBoNocChuVanCt;
use App\Models\PhanLoaiNgoiBoNocCt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductDetailSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        NgoiAmDuongCt::truncate();
        GachHoaThongGioCt::truncate();
        GachTrangTriCt::truncate();
        GachCoBatTrangCt::truncate();
        LinhVatPhongThuyCt::truncate();
        NgoiHaiCoCt::truncate();
        NgoiHaiVanMieuCt::truncate();
        NgoiBoNocCt::truncate();
        BoNocChuVanCt::truncate();
        MauSacNgoiHaiCoCt::truncate();
        MauSacNgoiHaiVanMieuCt::truncate();
        PhanLoaiNgoiBoNocCt::truncate();
        PhanLoaiBoNocChuVanCt::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->seedNgoiAmDuongCt();
        $this->seedGachHoaThongGioCt();
        $this->seedGachTrangTriCt();
        $this->seedGachCoBatTrangCt();
        $this->seedLinhVatPhongThuyCt();
        $this->seedNgoiHaiCoCt();
        $this->seedNgoiHaiVanMieuCt();
        $this->seedNgoiBoNocCt();
        $this->seedBoNocChuVanCt();
    }

    private function seedNgoiAmDuongCt(): void
    {
        $products = [
            [
                'code' => 'NAD-001', 'name' => 'Ngói Âm Dương Tráng Men Lục',
                'images' => ['assets/images/am-duong-detail-01.png', 'assets/images/am-duong-detail-02.png', 'assets/images/am-duong-detail-03.png'],
                'price' => 25000,
                'des' => [
                    'Men lục truyền thống lấy cảm hứng từ mái đình, chùa Việt cổ, tạo nên vẻ đẹp uy nghi, trầm mặc.',
                    'Đất sét Bát Tràng nguyên chất, nung ở 1200°C trong lò tuynel liên tục 72 giờ.',
                    'Khả năng chống thấm tuyệt đối, chống rêu mốc vĩnh viễn nhờ lớp men phủ kín bề mặt.',
                    'Phù hợp cho công trình tâm linh, biệt thự sân vườn, nhà hàng tiệc cưới phong cách Indochine.',
                ],
                'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
            ],
            [
                'code' => 'NAD-002', 'name' => 'Ngói Âm Dương Tráng Men Vàng',
                'images' => ['assets/images/am-duong-detail-02.png', 'assets/images/am-duong-detail-01.png'],
                'price' => 28000,
                'des' => [
                    'Men vàng hoàng gia - biểu tượng của sự phú quý, thịnh vượng trong kiến trúc Á Đông.',
                    'Đất sét Bát Tràng nguyên chất, nung ở 1200°C tạo độ cứng vượt trội, chịu lực tốt.',
                    'Màu vàng ấm áp, bền màu vĩnh cửu dưới mọi điều kiện thời tiết khắc nghiệt.',
                ],
                'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
            ],
            [
                'code' => 'NAD-003', 'name' => 'Ngói Âm Dương Đất Nung Tự Nhiên',
                'images' => ['assets/images/am-duong-detail-03.png', 'assets/images/am-duong-detail.png'],
                'price' => 18000,
                'des' => [
                    'Giữ nguyên màu đất nung tự nhiên, phù hợp công trình phong cách Rustic, mộc mạc.',
                    'Không tráng men, độ bền đến từ chất lượng đất sét và nhiệt độ nung 1200°C.',
                ],
                'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
            ],
            [
                'code' => 'NAD-004', 'name' => 'Ngói Âm Dương Men Xanh Lục Bảo',
                'images' => ['assets/images/am-duong-detail-01.png', 'assets/images/ngoi-am-duong-01.jpg'],
                'price' => 35000,
                'des' => [
                    'Sắc xanh lục bảo đặc trưng của dòng men hỏa biến, mỗi viên ngói là một tác phẩm độc bản.',
                    'Đất sét Bát Tràng nguyên chất, nung ở 1200°C — chịu được mọi điều kiện khí hậu.',
                ],
                'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
            ],
            [
                'code' => 'NAD-005', 'name' => 'Ngói Âm Dương Men Đỏ Nâu Cổ Điển',
                'images' => ['assets/images/ngoi-am-duong-02.png', 'assets/images/am-duong-detail-02.png'],
                'price' => 22000,
                'des' => [
                    'Tông màu đỏ nâu ấm áp, phù hợp với kiến trúc chùa chiền miền Bắc Việt Nam.',
                    'Chất liệu đất sét nung 1200°C, đảm bảo độ bền trên 50 năm.',
                ],
                'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
            ],
            [
                'code' => 'NAD-006', 'name' => 'Ngói Âm Dương Men Xanh Ngọc Hỏa Biến',
                'images' => ['assets/images/am-duong-detail-03.png', 'assets/images/ngoi-am-duong-02.png'],
                'price' => 32000,
                'des' => [
                    'Men xanh ngọc — sắc màu của sự thanh bình, mang lại cảm giác thư thái cho không gian sống.',
                    'Kỹ thuật tráng men thủ công, màu sắc độc nhất cho từng lô sản xuất.',
                ],
                'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
            ],
            [
                'code' => 'NAD-007', 'name' => 'Ngói Âm Dương Men Trắng Sứ Cao Cấp',
                'images' => ['assets/images/am-duong-detail.png', 'assets/images/am-duong-detail-01.png'],
                'price' => 38000,
                'des' => [
                    'Men trắng sứ tinh khiết — lựa chọn hoàn hảo cho công trình hiện đại pha cổ điển.',
                    'Bề mặt nhẵn bóng, dễ vệ sinh, không bám bụi, chống rêu mốc tuyệt đối.',
                ],
                'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
            ],
            [
                'code' => 'NAD-008', 'name' => 'Ngói Âm Dương Men Đen Huyền Bí',
                'images' => ['assets/images/ngoi-am-duong-01.jpg', 'assets/images/am-duong-detail-03.png'],
                'price' => 40000,
                'des' => [
                    'Sắc đen huyền bí — tạo điểm nhấn mạnh mẽ cho công trình kiến trúc tối giản.',
                    'Men đen bóng, hiệu ứng ánh sáng độc đáo, đẳng cấp và khác biệt.',
                ],
                'size' => '27 viên/m²', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
            ],
        ];

        foreach ($products as $p) {
            NgoiAmDuongCt::create([
                'code' => $p['code'], 'name' => $p['name'],
                'images' => $p['images'], 'price' => $p['price'],
                'des' => $p['des'], 'size' => $p['size'],
                'size_image' => $p['size_image'], 'is_delete' => 0,
            ]);
        }
    }

    private function seedGachHoaThongGioCt(): void
    {
        $products = [
            [
                'code' => 'GTG-001', 'name' => 'Gạch Hoa Thông Gió Họa Tiết Chữ Thọ',
                'images' => ['assets/images/gach-hoa-01.jpg', 'assets/images/gach-hoa-02.jpg', 'assets/images/gach-hoa-03.jpg'],
                'price' => 25000,
                'des' => [
                    'Họa tiết Chữ Thọ mang ý nghĩa trường tồn, phước lành — biểu tượng truyền thống trong kiến trúc Việt.',
                    'Chất liệu đất sét Bát Tràng nung ở 1200°C đảm bảo độ cứng vượt trội, không nứt vỡ.',
                    'Thông gió tự nhiên, lấy sáng hiệu quả — giải pháp xanh cho công trình hiện đại.',
                    'Thích hợp xây vách ngăn lấy sáng, mặt tiền nhà phố, biệt thự nghỉ dưỡng.',
                ],
                'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
            ],
            [
                'code' => 'GTG-002', 'name' => 'Gạch Hoa Thông Gió Họa Tiết Đồng Tiền',
                'images' => ['assets/images/gach-hoa-03.jpg', 'assets/images/gach-hoa-04.jpg'],
                'price' => 25000,
                'des' => [
                    'Họa tiết Đồng Tiền mang ý nghĩa tài lộc, phú quý, được ưa chuộng trong phong thủy.',
                    'Đất sét Bát Tràng nung 1200°C, chống thấm, chống rêu mốc vĩnh viễn.',
                ],
                'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
            ],
            [
                'code' => 'GTG-003', 'name' => 'Gạch Hoa Thông Gió Họa Tiết Hoa Sen',
                'images' => ['assets/images/gach-hoa-05.jpg', 'assets/images/gach-hoa-06.jpg'],
                'price' => 28000,
                'des' => [
                    'Hoa Sen — quốc hoa Việt Nam, biểu tượng của sự thanh cao, tinh khiết vượt lên bùn lầy.',
                    'Họa tiết tinh xảo được chạm khắc thủ công bởi nghệ nhân Bát Tràng.',
                ],
                'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
            ],
            [
                'code' => 'GTG-004', 'name' => 'Gạch Hoa Thông Gió Họa Tiết Hoa Mai',
                'images' => ['assets/images/gach-hoa-07.jpg', 'assets/images/gach-hoa-08.jpg'],
                'price' => 25000,
                'des' => [
                    'Họa tiết Hoa Mai tinh tế — mang sắc xuân vào không gian sống quanh năm.',
                    'Đất sét Bát Tràng nung 1200°C, phù hợp khí hậu nhiệt đới ẩm Việt Nam.',
                ],
                'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
            ],
            [
                'code' => 'GTG-005', 'name' => 'Gạch Hoa Thông Gió Mẫu Kỷ Hà',
                'images' => ['assets/images/gach-hoa-01.png', 'assets/images/gach-hoa-02.png'],
                'price' => 30000,
                'des' => [
                    'Thiết kế Kỷ Hà độc đáo — lấy cảm hứng từ sóng nước sông Hồng bao quanh làng gốm Bát Tràng.',
                    'Men hỏa biến tạo hiệu ứng màu sắc độc nhất cho từng viên gạch.',
                ],
                'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
            ],
            [
                'code' => 'GTG-006', 'name' => 'Gạch Hoa Thông Gió Họa Tiết Hình Học',
                'images' => ['assets/images/gach-hoa-03.png', 'assets/images/gach-hoa-05.png'],
                'price' => 22000,
                'des' => [
                    'Họa tiết hình học hiện đại, phù hợp công trình phong cách Minimalism và Contemporary.',
                    'Chất liệu đất sét Bát Tràng, nung 1200°C — chống thấm, chống mốc.',
                ],
                'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
            ],
            [
                'code' => 'GTG-007', 'name' => 'Gạch Hoa Thông Gió Họa Tiết Rồng',
                'images' => ['assets/images/gach-hoa-06.png', 'assets/images/gach-hoa-07.png'],
                'price' => 35000,
                'des' => [
                    'Họa tiết Rồng uy nghi — linh vật quyền lực nhất trong văn hóa Á Đông.',
                    'Chạm khắc thủ công tỉ mỉ từng chi tiết, phù hợp công trình tâm linh, đình chùa.',
                ],
                'size' => '20 x 20 x 6 cm', 'size_image' => 'assets/images/gtt-size.png',
            ],
        ];

        foreach ($products as $p) {
            GachHoaThongGioCt::create([
                'code' => $p['code'], 'name' => $p['name'],
                'images' => $p['images'], 'price' => $p['price'],
                'des' => $p['des'], 'size' => $p['size'],
                'size_image' => $p['size_image'], 'is_delete' => 0,
            ]);
        }
    }

    private function seedGachTrangTriCt(): void
    {
        $products = [
            [
                'code' => 'GTT-001', 'name' => 'Gạch Trang Trí Men Lục Cổ Điển',
                'images' => ['assets/images/trang-tri-01.png', 'assets/images/trang-tri-slide-01.jpg'],
                'price' => 32000,
                'des' => [
                    'Men lục cổ điển — màu sắc đặc trưng của gốm Việt truyền thống, đậm chất hoài cổ.',
                    'Đất sét Bát Tràng nguyên chất, nung 1200°C — chịu được điều kiện thời tiết khắc nghiệt.',
                    'Ứng dụng ốp tường, trang trí mặt tiền, quầy bar, khu lễ tân khách sạn.',
                ],
                'size' => '10 x 20 cm', 'size_image' => 'assets/images/gach-detail.png',
            ],
            [
                'code' => 'GTT-002', 'name' => 'Gạch Trang Trí Men Vàng Hoàng Gia',
                'images' => ['assets/images/trang-tri-02.png', 'assets/images/trang-tri-slide-02.jpg'],
                'price' => 38000,
                'des' => [
                    'Sắc vàng ấm áp, sang trọng — nâng tầm không gian kiến trúc cao cấp.',
                    'Men hỏa biến tạo vân màu tự nhiên, mỗi viên gạch là một tác phẩm nghệ thuật.',
                ],
                'size' => '10 x 20 cm', 'size_image' => 'assets/images/gach-detail.png',
            ],
            [
                'code' => 'GTT-003', 'name' => 'Gạch Trang Trí Men Xanh Ngọc',
                'images' => ['assets/images/trang-tri-slide-03.jpg', 'assets/images/trang-tri-slide-04.jpg'],
                'price' => 35000,
                'des' => [
                    'Sắc xanh ngọc mát mắt, tạo điểm nhấn cho công trình hiện đại và tân cổ điển.',
                    'Chất liệu đất sét Bát Tràng, nung 1200°C — chống thấm, chống phai màu.',
                ],
                'size' => '10 x 20 cm', 'size_image' => 'assets/images/gach-detail.png',
            ],
            [
                'code' => 'GTT-004', 'name' => 'Gạch Trang Trí Họa Tiết Chữ Thọ',
                'images' => ['assets/images/trang-tri-slide-01.jpg', 'assets/images/trang-tri-01.png'],
                'price' => 35000,
                'des' => [
                    'Chữ Thọ cách điệu — biểu tượng sức khỏe và trường thọ trong văn hóa Á Đông.',
                    'Đắp nổi thủ công, phù hợp ốp tường rào, cổng, mặt tiền.',
                ],
                'size' => 'Đường kính 30 cm', 'size_image' => 'assets/images/gach-detail.png',
            ],
            [
                'code' => 'GTT-005', 'name' => 'Gạch Trang Trí Họa Tiết Hoa Sen',
                'images' => ['assets/images/trang-tri-slide-02.jpg', 'assets/images/trang-tri-02.png'],
                'price' => 35000,
                'des' => [
                    'Hoa sen tinh xảo — điểm nhấn thanh tao cho tường rào và mặt tiền.',
                    'Đất sét Bát Tràng nung 1200°C, chống phai màu vĩnh viễn.',
                ],
                'size' => 'Đường kính 30 cm', 'size_image' => 'assets/images/gach-detail.png',
            ],
            [
                'code' => 'GTT-006', 'name' => 'Gạch Trang Trí Họa Tiết Rồng Phượng',
                'images' => ['assets/images/trang-tri-slide-03.jpg', 'assets/images/trang-tri-slide-04.jpg'],
                'price' => 42000,
                'des' => [
                    'Rồng Phượng song hành — biểu tượng đỉnh cao của quyền lực và sự cao quý.',
                    'Chế tác thủ công tỉ mỉ, phù hợp công trình tâm linh và biệt thự cao cấp.',
                ],
                'size' => '10 x 20 cm', 'size_image' => 'assets/images/gach-detail.png',
            ],
        ];

        foreach ($products as $p) {
            GachTrangTriCt::create([
                'code' => $p['code'], 'name' => $p['name'],
                'images' => $p['images'], 'price' => $p['price'],
                'des' => $p['des'], 'size' => $p['size'],
                'size_image' => $p['size_image'], 'is_delete' => 0,
            ]);
        }
    }

    private function seedGachCoBatTrangCt(): void
    {
        $products = [
            [
                'code' => 'GCB-001', 'name' => 'Gạch Cổ Bát Tràng Xây Tường',
                'images' => ['assets/images/gach-bat-01.jpg', 'assets/images/gach-bat-detail-1.png'],
                'price' => 15000,
                'des' => [
                    'Gạch cổ tái hiện vẻ đẹp làng nghề Bát Tràng — gam màu đỏ nâu trầm ấm.',
                    'Đất sét nguyên chất, nung 1200°C đảm bảo cường độ chịu lực cao.',
                    'Phù hợp xây tường rào, tường bao, công trình phong cách Indochine và Rustic.',
                ],
                'size' => '10 x 20 x 5 cm', 'size_image' => 'assets/images/gach-bat-size-1.png',
            ],
            [
                'code' => 'GCB-002', 'name' => 'Gạch Cổ Bát Tràng Ốp Vách',
                'images' => ['assets/images/gach-bat-02.jpg', 'assets/images/gach-bat-detail-2.png'],
                'price' => 18000,
                'des' => [
                    'Gạch cổ ốp vách — giải pháp trang trí tường nội ngoại thất đậm chất hoài cổ.',
                    'Bề mặt mộc tự nhiên, không tráng men, giữ trọn vẹn hồn đất Bát Tràng.',
                ],
                'size' => '5 x 20 x 2.5 cm', 'size_image' => 'assets/images/gach-bat-size-1.png',
            ],
            [
                'code' => 'GCB-003', 'name' => 'Gạch Cổ Bát Tràng Lát Nền',
                'images' => ['assets/images/gach-bat-detail-1.png', 'assets/images/gach-bat-01.jpg'],
                'price' => 22000,
                'des' => [
                    'Gạch lát nền mang hơi thở cổ xưa — phù hợp sân vườn, hiên nhà, lối đi.',
                    'Màu sắc tự nhiên của đất nung, không phai, không bạc theo thời gian.',
                ],
                'size' => '20 x 20 cm', 'size_image' => 'assets/images/gach-bat-size-1.png',
            ],
            [
                'code' => 'GCB-004', 'name' => 'Gạch Cổ Bát Tràng Ốp Vách Nhỏ',
                'images' => ['assets/images/gach-bat-detail-2.png', 'assets/images/gach-bat-02.jpg'],
                'price' => 20000,
                'des' => [
                    'Gạch nhỏ ốp vách — tạo hiệu ứng mosaic cổ điển, phù hợp quán cafe, nhà hàng.',
                    'Bề mặt mộc, giữ nguyên màu đất nung Bát Tràng nguyên bản.',
                ],
                'size' => '10 x 10 x 2 cm', 'size_image' => 'assets/images/gach-bat-size-1.png',
            ],
            [
                'code' => 'GCB-005', 'name' => 'Gạch Cổ Bát Tràng Cao Cấp Tráng Men',
                'images' => ['assets/images/gach-bat-01.jpg', 'assets/images/gach-bat-detail-1.png'],
                'price' => 25000,
                'des' => [
                    'Gạch cổ tráng men bóng — kết hợp giữa truyền thống và hiện đại.',
                    'Phù hợp ốp tường phòng khách, phòng thờ, không gian mang phong cách Đông Dương.',
                ],
                'size' => '10 x 20 x 5 cm', 'size_image' => 'assets/images/gach-bat-size-1.png',
            ],
            [
                'code' => 'GCB-006', 'name' => 'Gạch Cổ Bát Tràng Mộc Tự Nhiên',
                'images' => ['assets/images/gach-bat-02.jpg', 'assets/images/gach-bat-detail-2.png'],
                'price' => 16000,
                'des' => [
                    'Giữ nguyên bề mặt mộc thô ráp, không qua xử lý bề mặt — cảm giác chạm vào lịch sử.',
                    'Phù hợp công trình Rustic, Farmstay, Homestay vùng cao.',
                ],
                'size' => '10 x 20 x 5 cm', 'size_image' => 'assets/images/gach-bat-size-1.png',
            ],
        ];

        foreach ($products as $p) {
            GachCoBatTrangCt::create([
                'code' => $p['code'], 'name' => $p['name'],
                'images' => $p['images'], 'price' => $p['price'],
                'des' => $p['des'], 'size' => $p['size'],
                'size_image' => $p['size_image'], 'is_delete' => 0,
            ]);
        }
    }

    private function seedLinhVatPhongThuyCt(): void
    {
        $products = [
            [
                'code' => 'LVP-001', 'name' => 'Đầu Rồng Bờ Nóc - Men Xanh Lục',
                'images' => ['assets/images/dau-rong.png'],
                'price' => 1500000,
                'des' => [
                    'Linh vật Rồng — biểu tượng quyền lực tối cao, bảo vệ công trình khỏi tà khí.',
                    'Đất sét Bát Tràng, chế tác thủ công bởi nghệ nhân 40 năm kinh nghiệm.',
                    'Kích thước lớn, phù hợp đình chùa, nhà thờ họ, biệt phủ.',
                ],
                'size' => 'Cao 60cm x Rộng 35cm',
                'size_des' => [
                    'Sản phẩm đặt làm theo yêu cầu — thời gian chế tác 15-30 ngày.',
                    'Có sẵn 3 màu men: xanh lục, vàng, đỏ nâu.',
                ],
                'size_image' => 'assets/images/linh-vat-banner.png',
            ],
            [
                'code' => 'LVP-002', 'name' => 'Tượng Nghê Chầu - Gốm Men Xanh',
                'images' => ['assets/images/nghe.png'],
                'price' => 1200000,
                'des' => [
                    'Nghê — linh vật canh giữ cổng đình, chùa, biểu tượng lòng trung thành và sức mạnh.',
                    'Chế tác thủ công, mỗi chi tiết vân lông đều được khắc tỉ mỉ.',
                ],
                'size' => 'Cao 45cm x Rộng 25cm',
                'size_des' => [
                    'Có sẵn men xanh lục, men vàng, men đỏ. Đặt hàng theo yêu cầu kích thước.',
                ],
                'size_image' => 'assets/images/linh-vat-banner.png',
            ],
            [
                'code' => 'LVP-003', 'name' => 'Tượng Phượng Hoàng - Gốm Men Đỏ',
                'images' => ['assets/images/phuong.png'],
                'price' => 1800000,
                'des' => [
                    'Phượng Hoàng — biểu tượng tái sinh, sức sống mãnh liệt, vẻ đẹp cao quý.',
                    'Men đỏ hỏa biến tạo hiệu ứng cánh phượng rực rỡ, sống động.',
                ],
                'size' => 'Cao 50cm x Rộng 30cm',
                'size_des' => [
                    'Đặt hàng theo yêu cầu kích thước và màu men.',
                ],
                'size_image' => 'assets/images/linh-vat-banner.png',
            ],
            [
                'code' => 'LVP-004', 'name' => 'Đầu Rồng Mini Trang Trí',
                'images' => ['assets/images/dau-rong.png'],
                'price' => 800000,
                'des' => [
                    'Đầu Rồng Mini — phù hợp trang trí bàn thờ, kệ gỗ, không gian nội thất.',
                    'Chế tác thủ công với kích thước nhỏ gọn nhưng vẫn giữ trọn sự tinh xảo.',
                ],
                'size' => 'Cao 25cm x Rộng 15cm',
                'size_des' => [
                    'Có sẵn 4 màu men. Phù hợp làm quà tặng phong thủy cao cấp.',
                ],
                'size_image' => 'assets/images/linh-vat-banner.png',
            ],
            [
                'code' => 'LVP-005', 'name' => 'Nghê Mini Để Bàn',
                'images' => ['assets/images/nghe.png'],
                'price' => 600000,
                'des' => [
                    'Nghê Mini — linh vật phong thủy để bàn làm việc, thu hút may mắn.',
                    'Kích thước nhỏ gọn, dễ dàng đặt trên bàn, kệ, tủ.',
                ],
                'size' => 'Cao 15cm x Rộng 10cm',
                'size_des' => [
                    'Sản phẩm có sẵn, giao ngay trong ngày.',
                ],
                'size_image' => 'assets/images/linh-vat-banner.png',
            ],
            [
                'code' => 'LVP-006', 'name' => 'Phượng Hoàng Mini Trang Trí',
                'images' => ['assets/images/phuong.png'],
                'price' => 700000,
                'des' => [
                    'Phượng Hoàng Mini — biểu tượng sắc đẹp và sự tái sinh trong không gian sống.',
                    'Thích hợp đặt phòng khách, đại sảnh, làm quà tặng tân gia.',
                ],
                'size' => 'Cao 20cm x Rộng 12cm',
                'size_des' => [
                    'Có sẵn men xanh, đỏ, vàng.',
                ],
                'size_image' => 'assets/images/linh-vat-banner.png',
            ],
        ];

        foreach ($products as $p) {
            LinhVatPhongThuyCt::create([
                'code' => $p['code'], 'name' => $p['name'],
                'images' => $p['images'], 'price' => $p['price'],
                'des' => $p['des'], 'size' => $p['size'],
                'size_des' => $p['size_des'],
                'size_image' => $p['size_image'], 'is_delete' => 0,
            ]);
        }
    }

    private function seedNgoiHaiCoCt(): void
    {
        $colorVariants = [
            ['name' => 'Men Xanh Lục', 'image' => 'assets/images/ngoi-01.jpg', 'price' => 22000],
            ['name' => 'Men Xanh Ngọc', 'image' => 'assets/images/ngoi-02.jpg', 'price' => 22000],
            ['name' => 'Men Vàng', 'image' => 'assets/images/ngoi-03.jpg', 'price' => 25000],
            ['name' => 'Men Đỏ Nâu', 'image' => 'assets/images/ngoi-04.jpg', 'price' => 20000],
            ['name' => 'Đất Nung Tự Nhiên', 'image' => 'assets/images/ngoi-05.jpg', 'price' => 16000],
            ['name' => 'Men Xanh Dương', 'image' => 'assets/images/ngoi-06.jpg', 'price' => 24000],
            ['name' => 'Men Trắng Sứ', 'image' => 'assets/images/ngoi-07.jpg', 'price' => 26000],
        ];

        $tileDefs = [
            ['name' => 'Ngói Hài Cổ - Men Xanh Lục', 'images' => ['assets/images/ngoi-hai-01.png', 'assets/images/ngoi-hai-02.png'], 'des' => ['Ngói Hài Cổ tráng men xanh lục truyền thống — phù hợp lợp mái nhà thờ họ, đình chùa.', 'Đất sét Bát Tràng nguyên chất, nung 1200°C — chống thấm tuyệt đối.'], 'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png'],
            ['name' => 'Ngói Hài Cổ - Men Vàng', 'images' => ['assets/images/ngoi-hai-02.png', 'assets/images/ngoi-hai-03.png'], 'des' => ['Ngói Hài Cổ tráng men vàng hoàng gia — màu sắc sang trọng, quyền quý.', 'Nung ở 1200°C — độ bền trên 50 năm, không phai màu.'], 'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png'],
            ['name' => 'Ngói Hài Cổ - Men Đỏ Nâu', 'images' => ['assets/images/ngoi-hai-03.png', 'assets/images/ngoi-hai-detail.png'], 'des' => ['Ngói Hài Cổ men đỏ nâu cổ điển — đậm chất truyền thống miền Bắc.', 'Chống rêu mốc, chống thấm nước vĩnh viễn.'], 'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png'],
            ['name' => 'Ngói Hài Cổ - Men Xanh Ngọc', 'images' => ['assets/images/ngoi-hai-detail.png', 'assets/images/ngoi-hai-01.png'], 'des' => ['Ngói Hài Cổ men xanh ngọc — sắc màu thanh bình, mang đến cảm giác thư thái.', 'Phù hợp biệt thự nghỉ dưỡng, khu du lịch sinh thái.'], 'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png'],
            ['name' => 'Ngói Hài Cổ - Đất Nung Tự Nhiên', 'images' => ['assets/images/ngoi-hai-01.png', 'assets/images/ngoi-hai-03.png'], 'des' => ['Ngói Hài Cổ không tráng men — vẻ đẹp mộc mạc, tự nhiên của đất nung Bát Tràng.', 'Phù hợp công trình Rustic và phong cách Wabi-sabi Nhật Bản.'], 'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png'],
        ];

        foreach ($tileDefs as $idx => $tile) {
            $product = NgoiHaiCoCt::create([
                'name' => $tile['name'],
                'images' => $tile['images'],
                'des' => $tile['des'],
                'size' => $tile['size'],
                'size_image' => $tile['size_image'],
                'is_delete' => 0,
            ]);

            $selectedColors = array_slice($colorVariants, ($idx * 2) % count($colorVariants), 3);
            if (count($selectedColors) < 2) {
                $selectedColors = array_slice($colorVariants, 0, 3);
            }

            foreach ($selectedColors as $j => $color) {
                $code = 'NHC-' . str_pad($product->ngoi_hai_co_ct_id, 3, '0', STR_PAD_LEFT) . '-C' . ($j + 1);
                MauSacNgoiHaiCoCt::create([
                    'name' => $color['name'],
                    'image' => $color['image'],
                    'code' => $code,
                    'price' => $color['price'],
                    'ngoi_hai_co_ct_id' => $product->ngoi_hai_co_ct_id,
                    'is_delete' => 0,
                ]);
            }
        }
    }

    private function seedNgoiHaiVanMieuCt(): void
    {
        $products = [
            [
                'name' => 'Ngói Hài Văn Miếu - Men Xanh Lục',
                'images' => ['assets/images/ngoi-hai-01.png', 'assets/images/ngoi-hai-02.png'],
                'price' => 25000,
                'des' => [
                    'Ngói Văn Miếu tráng men xanh lục — đậm chất kiến trúc cổ Việt Nam.',
                    'Đất sét Bát Tràng, nung 1200°C — chống nóng, chống thấm tuyệt đối.',
                    'Phù hợp công trình tâm linh, di tích lịch sử, nhà thờ họ.',
                ],
                'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png',
            ],
            [
                'name' => 'Ngói Hài Văn Miếu - Men Vàng',
                'images' => ['assets/images/ngoi-hai-02.png', 'assets/images/ngoi-hai-03.png'],
                'price' => 28000,
                'des' => [
                    'Sắc vàng hoàng gia trên dáng ngói Văn Miếu — biểu tượng của sự cao quý.',
                    'Chất liệu đất sét nung 1200°C, đảm bảo độ bền vượt thời gian.',
                ],
                'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png',
            ],
            [
                'name' => 'Ngói Hài Văn Miếu - Men Đỏ Nâu',
                'images' => ['assets/images/ngoi-hai-03.png', 'assets/images/ngoi-hai-detail.png'],
                'price' => 22000,
                'des' => [
                    'Men đỏ nâu trầm ấm — phù hợp kiến trúc đình chùa miền Bắc truyền thống.',
                    'Không phai màu, chống rêu mốc vĩnh viễn.',
                ],
                'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png',
            ],
            [
                'name' => 'Ngói Hài Văn Miếu - Men Xanh Ngọc',
                'images' => ['assets/images/ngoi-hai-detail.png', 'assets/images/ngoi-hai-01.png'],
                'price' => 30000,
                'des' => [
                    'Sắc xanh ngọc độc đáo — điểm nhấn ấn tượng cho mái công trình.',
                    'Men hỏa biến tạo vân màu tự nhiên, mỗi viên ngói một vẻ đẹp riêng.',
                ],
                'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png',
            ],
            [
                'name' => 'Ngói Hài Văn Miếu - Đất Nung Tự Nhiên',
                'images' => ['assets/images/ngoi-hai-01.png', 'assets/images/ngoi-hai-03.png'],
                'price' => 18000,
                'des' => [
                    'Màu đất nung nguyên bản — lựa chọn tiết kiệm nhưng vẫn đảm bảo chất lượng.',
                    'Không tráng men, độ bền đến từ chất lượng đất sét và nhiệt độ nung.',
                ],
                'size' => 'Kích thước tiêu chuẩn', 'size_image' => 'assets/images/ngoi-hai-size.png',
            ],
        ];

        $colorVariants = [
            ['name' => 'Men Xanh Lục', 'image' => 'assets/images/ngoi-01.jpg', 'price' => 25000],
            ['name' => 'Men Vàng', 'image' => 'assets/images/ngoi-03.jpg', 'price' => 28000],
            ['name' => 'Men Đỏ Nâu', 'image' => 'assets/images/ngoi-04.jpg', 'price' => 22000],
            ['name' => 'Men Xanh Ngọc', 'image' => 'assets/images/ngoi-02.jpg', 'price' => 30000],
            ['name' => 'Đất Nung Tự Nhiên', 'image' => 'assets/images/ngoi-05.jpg', 'price' => 18000],
        ];

        foreach ($products as $idx => $p) {
            $product = NgoiHaiVanMieuCt::create([
                'name' => $p['name'],
                'images' => $p['images'],
                'price' => 0,
                'des' => $p['des'],
                'mau_sac_id' => 0,
                'size' => $p['size'],
                'size_image' => $p['size_image'],
                'is_delete' => 0,
            ]);

            $selectedColors = array_slice($colorVariants, $idx % count($colorVariants), 3);
            if (count($selectedColors) < 2) {
                $selectedColors = [$colorVariants[0], $colorVariants[1]];
            }

            foreach ($selectedColors as $j => $color) {
                $code = 'NHVM-' . str_pad($product->ngoi_hai_van_mieu_ct_id, 3, '0', STR_PAD_LEFT) . '-C' . ($j + 1);
                MauSacNgoiHaiVanMieuCt::create([
                    'name' => $color['name'],
                    'image' => $color['image'],
                    'code' => $code,
                    'price' => $color['price'],
                    'ngoi_hai_van_mieu_ct_id' => $product->ngoi_hai_van_mieu_ct_id,
                    'is_delete' => 0,
                ]);
            }
        }
    }

    private function seedNgoiBoNocCt(): void
    {
        $products = [
            [
                'name' => 'Ngói Bò Nóc - Tráng Men Lục',
                'images' => ['assets/images/bo-noc.png'],
                'des' => [
                    'Ngói bò nóc tráng men lục — điểm nhấn hoàn hảo cho đường nóc mái.',
                    'Đất sét Bát Tràng nguyên chất, nung 1200°C — chịu lực tốt, không nứt vỡ.',
                ],
                'size' => 'Tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Sản phẩm chuyên dụng cho nóc mái ngói âm dương.'],
            ],
            [
                'name' => 'Ngói Bò Nóc - Men Vàng',
                'images' => ['assets/images/chu-van-1.png'],
                'des' => [
                    'Ngói bò nóc men vàng — tôn vinh vẻ đẹp quyền quý của công trình.',
                    'Đất sét Bát Tràng nung 1200°C, bền màu vĩnh cửu.',
                ],
                'size' => 'Tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Sản phẩm chuyên dụng cho nóc mái ngói âm dương.'],
            ],
            [
                'name' => 'Ngói Bò Nóc - Men Đỏ Nâu',
                'images' => ['assets/images/chu-van-2.png'],
                'des' => [
                    'Ngói bò nóc men đỏ nâu — hoàn thiện mái ngói với vẻ đẹp cổ điển.',
                    'Phù hợp với hệ mái ngói âm dương đỏ nâu và đất nung tự nhiên.',
                ],
                'size' => 'Tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Sản phẩm chuyên dụng cho nóc mái ngói âm dương.'],
            ],
            [
                'name' => 'Ngói Bò Nóc - Men Trắng Sứ',
                'images' => ['assets/images/bo-noc.png'],
                'des' => [
                    'Ngói bò nóc trắng sứ — tương phản ấn tượng với mái ngói tối màu.',
                    'Phù hợp công trình kiến trúc hiện đại pha truyền thống.',
                ],
                'size' => 'Tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Sản phẩm chuyên dụng cho nóc mái ngói âm dương.'],
            ],
            [
                'name' => 'Ngói Bò Nóc - Đất Nung Tự Nhiên',
                'images' => ['assets/images/chu-van-3.png'],
                'des' => [
                    'Ngói bò nóc không men — phù hợp hệ mái đất nung, tạo tổng thể hài hòa.',
                    'Chất liệu đất sét nung 1200°C, độ bền cao dù không tráng men.',
                ],
                'size' => 'Tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Sản phẩm chuyên dụng cho nóc mái ngói âm dương.'],
            ],
            [
                'name' => 'Ngói Bò Nóc - Men Xanh Ngọc',
                'images' => ['assets/images/chu-van-1.png'],
                'des' => [
                    'Ngói bò nóc xanh ngọc — tạo điểm nhấn màu sắc nổi bật trên đường nóc.',
                    'Men hỏa biến cao cấp, mỗi sản phẩm một vẻ đẹp riêng.',
                ],
                'size' => 'Tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Sản phẩm chuyên dụng cho nóc mái ngói âm dương.'],
            ],
        ];

        foreach ($products as $p) {
            $product = NgoiBoNocCt::create([
                'name' => $p['name'],
                'images' => $p['images'],
                'des' => $p['des'],
                'size' => $p['size'],
                'size_image' => $p['size_image'],
                'size_des' => $p['size_des'],
                'is_delete' => 0,
            ]);

            $productName = $p['name'];
            PhanLoaiNgoiBoNocCt::create([
                'name' => $productName . ' - Loại tiêu chuẩn',
                'code' => 'NBN-' . str_pad($product->ngoi_bo_noc_ct_id, 3, '0', STR_PAD_LEFT) . '-STD',
                'price' => 45000,
                'ngoi_bo_noc_ct_id' => $product->ngoi_bo_noc_ct_id,
                'is_delete' => 0,
            ]);
            PhanLoaiNgoiBoNocCt::create([
                'name' => $productName . ' - Loại cao cấp (men hỏa biến)',
                'code' => 'NBN-' . str_pad($product->ngoi_bo_noc_ct_id, 3, '0', STR_PAD_LEFT) . '-PRE',
                'price' => 65000,
                'ngoi_bo_noc_ct_id' => $product->ngoi_bo_noc_ct_id,
                'is_delete' => 0,
            ]);
        }
    }

    private function seedBoNocChuVanCt(): void
    {
        $phanLoais = [
            ['name' => 'Men Vàng Đồng', 'price' => 55000],
            ['name' => 'Men Xanh Lục', 'price' => 60000],
            ['name' => 'Men Nâu Đất', 'price' => 45000],
            ['name' => 'Đất Nung Tự Nhiên', 'price' => 35000],
        ];

        $products = [
            [
                'name' => 'Bộ Nóc Chữ Vạn - Men Vàng Đồng',
                'images' => ['assets/images/chu-van-1.png', 'assets/images/chu-van-2.png'],
                'des' => [
                    'Họa tiết chữ Vạn mang ý nghĩa tốt lành, trường thọ.',
                    'Trang trí bờ nóc đình chùa, nhà thờ họ, biệt thự cổ.',
                ],
                'size' => 'Khổ tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Dài 35cm, Dày 2.5cm'],
            ],
            [
                'name' => 'Bộ Nóc Chữ Vạn - Men Xanh Lục',
                'images' => ['assets/images/chu-van-2.png', 'assets/images/chu-van-3.png'],
                'des' => [
                    'Chữ Vạn tráng men xanh lục — điểm nhấn tinh tế trên đường nóc.',
                    'Chống thấm nước tuyệt đối, không bám rêu mốc.',
                ],
                'size' => 'Khổ tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Dài 30cm, Dày 2cm'],
            ],
            [
                'name' => 'Bộ Nóc Chữ Vạn - Men Nâu Đất',
                'images' => ['assets/images/chu-van-3.png', 'assets/images/chu-van-1.png'],
                'des' => [
                    'Tông nâu đất trầm ấm — hài hòa với mọi hệ mái ngói truyền thống.',
                    'Đất sét nung già, độ bền vượt trội trước thời tiết khắc nghiệt.',
                ],
                'size' => 'Khổ tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Dài 40cm, Dày 3cm'],
            ],
            [
                'name' => 'Bộ Nóc Chữ Vạn - Đất Nung Tự Nhiên',
                'images' => ['assets/images/chu-van-1.png', 'assets/images/chu-van-3.png'],
                'des' => [
                    'Giữ nguyên sắc đất nung mộc mạc — phù hợp công trình Rustic và truyền thống.',
                    'Không tráng men nhưng vẫn đảm bảo độ bền nhờ nung 1200°C.',
                ],
                'size' => 'Khổ tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Dài 25cm, Dày 1.5cm'],
            ],
            [
                'name' => 'Bộ Nóc Chữ Vạn - Men Hỏa Biến',
                'images' => ['assets/images/chu-van-2.png', 'assets/images/chu-van-1.png'],
                'des' => [
                    'Men hỏa biến độc quyền — mỗi bộ nóc là một kiệt tác không thể sao chép.',
                    'Cao cấp nhất trong dòng bộ nóc, dành cho công trình đẳng cấp.',
                ],
                'size' => 'Khổ tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Dài 35cm, Dày 2.5cm'],
            ],
            [
                'name' => 'Bộ Nóc Chữ Vạn - Men Trắng Sứ',
                'images' => ['assets/images/chu-van-3.png', 'assets/images/chu-van-2.png'],
                'des' => [
                    'Men trắng sứ tinh khiết — tạo nét chấm phá độc đáo trên mái ngói.',
                    'Bề mặt nhẵn bóng, dễ vệ sinh, luôn sáng đẹp như mới.',
                ],
                'size' => 'Khổ tiêu chuẩn', 'size_image' => 'assets/images/ngoi-am-duong-size.png',
                'size_des' => ['Dài 30cm, Dày 2.5cm'],
            ],
        ];

        foreach ($products as $idx => $p) {
            $product = BoNocChuVanCt::create([
                'name' => $p['name'],
                'images' => $p['images'],
                'des' => $p['des'],
                'size' => $p['size'],
                'size_image' => $p['size_image'],
                'size_des' => $p['size_des'],
                'is_delete' => 0,
            ]);

            PhanLoaiBoNocChuVanCt::create([
                'name' => $p['name'] . ' - Loại tiêu chuẩn',
                'code' => 'BNC-' . str_pad($product->bo_noc_chu_van_ct_id, 3, '0', STR_PAD_LEFT) . '-STD',
                'price' => $phanLoais[$idx % count($phanLoais)]['price'],
                'bo_noc_chu_van_ct_id' => $product->bo_noc_chu_van_ct_id,
                'is_delete' => 0,
            ]);
            PhanLoaiBoNocChuVanCt::create([
                'name' => $p['name'] . ' - Loại cao cấp (men hỏa biến)',
                'code' => 'BNC-' . str_pad($product->bo_noc_chu_van_ct_id, 3, '0', STR_PAD_LEFT) . '-PRE',
                'price' => $phanLoais[$idx % count($phanLoais)]['price'] + 20000,
                'bo_noc_chu_van_ct_id' => $product->bo_noc_chu_van_ct_id,
                'is_delete' => 0,
            ]);
        }
    }
}
