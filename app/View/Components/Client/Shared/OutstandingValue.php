<?php

namespace App\View\Components\Client\Shared;

use App\Services\GiaTriVuotTroiService;
use App\Support\AssetPath;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class OutstandingValue extends Component
{
    public Collection $values;

    public array $fallbackImages = ['value-01.png', 'value-02.png', 'value-03.png', 'value-04.png'];

    public function __construct(
        GiaTriVuotTroiService $giaTriVuotTroiService,
        mixed $giaTriVuotTroi = null,
    ) {
        $values = $giaTriVuotTroi !== null
            ? collect($giaTriVuotTroi)->filter()->values()
            : $giaTriVuotTroiService->getAll();

        if ($values->isEmpty()) {
            $values = collect([
                (object) ['title' => 'CỐT ĐẤT LUYỆN LỬA', 'desscription' => 'Được tôi luyện ở nhiệt độ 1300°C tạo nên cốt gốm đanh chắc, thách thức mọi khắc nghiệt từ sương muối đến bão giông. Đây là sự đầu tư một lần cho giá trị truyền đời, chẳng ngại dấu vết thời gian', 'image' => null],
                (object) ['title' => 'KIẾN TRÚC TRƯỜNG TỒN', 'desscription' => 'Vẻ đẹp truyền thống kết hợp cùng công nghệ sản xuất hiện đại, tạo nên mái nhà vững chãi, che chở vạn vật qua bao thăng trầm, mang lại sự bình yên và hưng thịnh cho gia chủ.', 'image' => null],
                (object) ['title' => 'NGHỆ THUẬT GỐM SỨ', 'desscription' => 'Từng viên ngói là một tác phẩm nghệ thuật, kết tinh từ bàn tay khéo léo của các nghệ nhân làng gốm Bát Tràng, mang trong mình tâm hồn và sức sống di sản Việt.', 'image' => null],
                (object) ['title' => 'SẮC MEN ĐỘC BẢN', 'desscription' => 'Lớp men hỏa biến biến ảo trong lò nung, tạo nên những sắc thái màu đặc trưng không viên nào giống viên nào, tạo nên dấu ấn riêng biệt cho ngôi nhà của bạn.', 'image' => null],
            ]);
        }

        $this->values = $values->map(function ($item, int $index) {
            $fallback = 'assets/images/'.($this->fallbackImages[$index] ?? $this->fallbackImages[0]);

            return (object) [
                'title' => data_get($item, 'title', ''),
                'desscription' => data_get($item, 'desscription', ''),
                'image_url' => AssetPath::url(data_get($item, 'image'), $fallback),
            ];
        });
    }

    public function render(): View
    {
        return view('components.client.shared.outstanding-value');
    }
}
