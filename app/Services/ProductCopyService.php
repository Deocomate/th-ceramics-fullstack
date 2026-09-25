<?php

namespace App\Services;

use App\Models\DenVuonGomSuCt;
use App\Models\GachCoBatTrangCt;
use App\Models\GachHoaThongGioCt;
use App\Models\GachTrangTriCt;
use App\Models\LanCanGomSuCt;
use App\Models\LinhVatPhongThuyCt;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiHaiCoCt;
use App\Models\NgoiHaiVanMieuCt;
use App\Models\PhuKienNgoiCt;
use App\Support\AssetPath;
use App\Support\ProductGallery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ProductCopyService
{
    /**
     * Cấu hình chi tiết cho 10 loại sản phẩm trong hệ thống
     *
     * @var array<string, array{model: class-string<Model>, pk: string, label: string, has_code: bool, has_price: bool}>
     */
    protected array $typeConfigs = [
        'ngoi-am-duong-ct' => [
            'model' => NgoiAmDuongCt::class,
            'pk' => 'ngoi_am_duong_ct_id',
            'label' => 'Ngói Âm Dương',
            'has_code' => true,
            'has_price' => true,
        ],
        'ngoi-hai-co-ct' => [
            'model' => NgoiHaiCoCt::class,
            'pk' => 'ngoi_hai_co_ct_id',
            'label' => 'Ngói Hài Cổ',
            'has_code' => false,
            'has_price' => false,
            'with' => ['mauSacs'],
        ],
        'ngoi-hai-van-mieu-ct' => [
            'model' => NgoiHaiVanMieuCt::class,
            'pk' => 'ngoi_hai_van_mieu_ct_id',
            'label' => 'Ngói Hài Văn Miếu',
            'has_code' => false,
            'has_price' => false,
            'with' => ['mauSacs'],
        ],
        'gach-hoa-thong-gio-ct' => [
            'model' => GachHoaThongGioCt::class,
            'pk' => 'gach_hoa_thong_gio_ct_id',
            'label' => 'Gạch Hoa Thông Gió',
            'has_code' => true,
            'has_price' => true,
        ],
        'gach-trang-tri-ct' => [
            'model' => GachTrangTriCt::class,
            'pk' => 'gach_trang_tri_ct_id',
            'label' => 'Gạch Trang Trí',
            'has_code' => true,
            'has_price' => true,
        ],
        'phu-kien-ngoi-ct' => [
            'model' => PhuKienNgoiCt::class,
            'pk' => 'phu_kien_ngoi_ct_id',
            'label' => 'Phụ Kiện Ngói',
            'has_code' => false,
            'has_price' => false,
            'with' => ['phanLoais'],
        ],
        'gach-co-bat-trang-ct' => [
            'model' => GachCoBatTrangCt::class,
            'pk' => 'gach_co_bat_trang_ct_id',
            'label' => 'Gạch Cổ Bát Tràng',
            'has_code' => true,
            'has_price' => true,
        ],
        'linh-vat-phong-thuy-ct' => [
            'model' => LinhVatPhongThuyCt::class,
            'pk' => 'linh_vat_phong_thuy_ct_id',
            'label' => 'Linh Vật Phong Thủy',
            'has_code' => true,
            'has_price' => true,
        ],
        'lan-can-gom-su-ct' => [
            'model' => LanCanGomSuCt::class,
            'pk' => 'lan_can_gom_su_ct_id',
            'label' => 'Lan Can Gốm Sứ',
            'has_code' => false,
            'has_price' => false,
            'with' => ['phanLoais'],
        ],
        'den-vuon-gom-su-ct' => [
            'model' => DenVuonGomSuCt::class,
            'pk' => 'den_vuon_gom_su_ct_id',
            'label' => 'Đèn Vườn Gốm Sứ',
            'has_code' => false,
            'has_price' => false,
            'with' => ['phanLoais'],
        ],
    ];

    /**
     * Lấy danh sách các loại sản phẩm được hỗ trợ kèm nhãn
     *
     * @return array<int, array{key: string, label: string}>
     */
    public function getSupportedTypes(): array
    {
        $result = [];
        foreach ($this->typeConfigs as $key => $config) {
            $result[] = [
                'key' => $key,
                'label' => $config['label'],
            ];
        }

        return $result;
    }

    /**
     * Lấy cấu hình của một loại sản phẩm
     */
    public function getTypeConfig(string $type): ?array
    {
        return $this->typeConfigs[$type] ?? null;
    }

    /**
     * Tìm kiếm danh sách sản phẩm theo loại và từ khóa
     *
     * @return array<int, array{id: int, name: string, code: string, price: float|int, formatted_price: string, color: string, size: string, thumbnail_url: string, des_count: int, category_type: ?string}>
     */
    public function searchProducts(string $type, ?string $keyword = null, int $limit = 30): array
    {
        $config = $this->getTypeConfig($type);
        if (! $config) {
            throw new InvalidArgumentException("Loại sản phẩm không hợp lệ: {$type}");
        }

        /** @var class-string<Model> $modelClass */
        $modelClass = $config['model'];
        $pk = $config['pk'];

        /** @var Builder $query */
        $query = $modelClass::query()->where('is_delete', 0);

        if (! empty($config['with'])) {
            $query->with($config['with']);
        }

        if ($keyword !== null && trim($keyword) !== '') {
            $kw = '%'.trim($keyword).'%';
            $query->where(function (Builder $q) use ($kw, $config) {
                $q->where('name', 'like', $kw);

                if ($config['has_code']) {
                    $q->orWhere('code', 'like', $kw);
                }

                // Tìm kiếm mở rộng nếu có biến thể màu / phân loại
                if (in_array($config['model'], [NgoiHaiCoCt::class, NgoiHaiVanMieuCt::class], true)) {
                    $q->orWhereHas('mauSacs', function (Builder $sub) use ($kw) {
                        $sub->where('is_delete', 0)->where(function (Builder $s) use ($kw) {
                            $s->where('code', 'like', $kw)->orWhere('name', 'like', $kw);
                        });
                    });
                } elseif (in_array($config['model'], [PhuKienNgoiCt::class, LanCanGomSuCt::class, DenVuonGomSuCt::class], true)) {
                    $q->orWhereHas('phanLoais', function (Builder $sub) use ($kw) {
                        $sub->where('is_delete', 0)->where(function (Builder $s) use ($kw) {
                            $s->where('code', 'like', $kw)->orWhere('name', 'like', $kw);
                        });
                    });
                }
            });
        }

        $items = $query->latest($pk)->limit($limit)->get();

        return $items->map(function ($item) use ($pk, $config) {
            return $this->formatForListItem($item, $pk, $config);
        })->values()->all();
    }

    /**
     * Lấy chi tiết thông tin sản phẩm chuẩn hóa để đưa vào form tạo mới
     */
    public function getProductDetailForCopy(string $type, int $id): ?array
    {
        $config = $this->getTypeConfig($type);
        if (! $config) {
            return null;
        }

        /** @var class-string<Model> $modelClass */
        $modelClass = $config['model'];
        $pk = $config['pk'];

        $query = $modelClass::query()->where($pk, $id)->where('is_delete', 0);
        if (! empty($config['with'])) {
            $query->with($config['with']);
        }

        $product = $query->first();
        if (! $product) {
            return null;
        }

        // Lấy thông tin mã và giá
        $code = $config['has_code'] ? ($product->code ?? '') : ($product->code ?? $product->display_code ?? '');
        if ($code === 'Đang cập nhật' || $code === 'N/A') {
            $code = '';
        }
        $price = $config['has_price'] ? ($product->price ?? 0) : ($product->price ?? 0);

        // Chuẩn hóa mảng des (mô tả / thông số)
        $desList = [];
        if (is_array($product->des)) {
            $desList = array_values(array_filter(array_map('trim', $product->des)));
        }

        // Chuẩn hóa mảng size_des (kích thước chi tiết)
        $sizeDesList = [];
        if (isset($product->size_des) && is_array($product->size_des)) {
            $sizeDesList = array_values(array_filter(array_map('trim', $product->size_des)));
        }

        $firstImage = ProductGallery::firstImagePath($product->images ?? []);
        $thumbnailUrl = $firstImage ? AssetPath::url($firstImage) : null;

        return [
            'id' => $product->{$pk},
            'type' => $type,
            'name' => $product->name ?? '',
            'code' => $code,
            'suggested_code' => $code ? ($code.'-COPY') : '',
            'price' => (float) $price,
            'color' => $product->color ?? 'Tự chọn',
            'size' => $product->size ?? '',
            'category_type' => $product->category_type ?? null,
            'dinh_muc' => $product->dinh_muc ?? null,
            'weight' => $product->weight ?? null,
            'des' => $desList,
            'size_des' => $sizeDesList,
            'video' => $product->video ?? null,
            'thumbnail_url' => $thumbnailUrl,
            'size_image_url' => ! empty($product->size_image) ? AssetPath::url($product->size_image) : null,
        ];
    }

    /**
     * Định dạng sản phẩm cho danh sách trong Modal
     */
    protected function formatForListItem(Model $item, string $pk, array $config): array
    {
        $code = $config['has_code']
            ? ($item->code ?? 'N/A')
            : ($item->code ?? $item->display_code ?? 'N/A');

        $price = $config['has_price']
            ? ($item->price ?? 0)
            : ($item->price ?? 0);

        $firstImage = ProductGallery::firstImagePath($item->images ?? []);
        $thumbnailUrl = $firstImage ? AssetPath::url($firstImage) : null;

        $desCount = is_array($item->des) ? count(array_filter($item->des)) : 0;

        return [
            'id' => $item->{$pk},
            'name' => (string) ($item->name ?? ''),
            'code' => (string) ($code ?: 'N/A'),
            'price' => (float) $price,
            'formatted_price' => $price > 0 ? number_format($price, 0, ',', '.').' VNĐ' : 'Liên hệ / Theo phân loại',
            'color' => (string) ($item->color ?? 'Tự chọn'),
            'size' => (string) ($item->size ?? 'N/A'),
            'thumbnail_url' => $thumbnailUrl,
            'des_count' => $desCount,
            'category_type' => $item->category_type ?? null,
        ];
    }
}
