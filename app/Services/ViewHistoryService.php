<?php

namespace App\Services;

use App\Models\BoNocChuVanCt;
use App\Models\DenGomSu;
use App\Models\GachCoBatTrangCt;
use App\Models\GachHoaThongGioCt;
use App\Models\GachTrangTriCt;
use App\Models\LanCanGomXu;
use App\Models\LinhVatPhongThuyCt;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiBoNocCt;
use App\Models\NgoiHaiCoCt;
use App\Models\NgoiHaiVanMieuCt;
use App\Models\TinTuc;
use App\Support\AssetPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class ViewHistoryService
{
    private const RECENT_NEWS_KEY = 'th_recent_news';

    private const RECENT_PRODUCTS_KEY = 'th_recent_products';

    private const MAX_ITEMS = 12;

    public function trackArticle(int $articleId): void
    {
        $history = array_values(array_filter(
            session(self::RECENT_NEWS_KEY, []),
            fn ($id) => (int) $id !== $articleId
        ));

        array_unshift($history, $articleId);

        session()->put(self::RECENT_NEWS_KEY, array_slice($history, 0, self::MAX_ITEMS));
    }

    public function recentArticles(int $limit = 3): Collection
    {
        $ids = array_slice(session(self::RECENT_NEWS_KEY, []), 0, self::MAX_ITEMS);

        if (empty($ids)) {
            return collect();
        }

        $position = array_flip(array_map('intval', $ids));

        return TinTuc::query()
            ->with('danhMuc')
            ->whereIn('tin_tuc_id', $ids)
            ->whereIn('trang_thai', ['published', 'active'])
            ->whereHas('danhMuc', fn ($query) => $query->where('is_delete', false))
            ->get()
            ->sortBy(fn (TinTuc $article) => $position[$article->tin_tuc_id] ?? PHP_INT_MAX)
            ->take($limit)
            ->values();
    }

    public function trackProduct(string $type, int $id, array $snapshot = []): void
    {
        $history = array_values(array_filter(
            session(self::RECENT_PRODUCTS_KEY, []),
            fn ($item) => ($item['type'] ?? null) !== $type || (int) ($item['id'] ?? 0) !== $id
        ));

        array_unshift($history, array_merge($snapshot, [
            'type' => $type,
            'id' => $id,
        ]));

        session()->put(self::RECENT_PRODUCTS_KEY, array_slice($history, 0, self::MAX_ITEMS));
    }

    public function recentProducts(int $limit = 4): Collection
    {
        $history = array_slice(session(self::RECENT_PRODUCTS_KEY, []), 0, $limit);

        if (empty($history)) {
            return collect();
        }

        return collect($history)
            ->map(fn (array $item) => $this->resolveProductItem($item))
            ->filter()
            ->values();
    }

    private function resolveProductItem(array $item): ?object
    {
        $type = (string) ($item['type'] ?? '');
        $id = (int) ($item['id'] ?? 0);

        if ($type === '' || $id <= 0) {
            return null;
        }

        if ($type === 'phu_kien_ngoi') {
            return $this->resolveAccessoryProduct($id, $item);
        }

        $map = $this->productTypeMap();

        if (! isset($map[$type])) {
            return null;
        }

        $config = $map[$type];
        $model = $config['model']::query()
            ->when($config['has_delete'], fn ($query) => $query->where('is_delete', false))
            ->find($id);

        if (! $model instanceof Model) {
            return null;
        }

        return $this->makeProductDto(
            type: $type,
            id: $id,
            name: $this->productName($model, $config['fallback_name']),
            price: (float) data_get($model, 'price', 0),
            image: $this->productImage($model, $config['image_field']),
            routeName: $config['route'],
        );
    }

    private function resolveAccessoryProduct(int $id, array $item): ?object
    {
        $accessoryType = $item['accessory_type'] ?? null;

        if ($accessoryType === 'bo_noc') {
            $product = NgoiBoNocCt::query()->where('is_delete', false)->find($id);
        } elseif ($accessoryType === 'chu_van') {
            $product = BoNocChuVanCt::query()->where('is_delete', false)->find($id);
        } else {
            $product = NgoiBoNocCt::query()->where('is_delete', false)->find($id)
                ?: BoNocChuVanCt::query()->where('is_delete', false)->find($id);
        }

        if (! $product instanceof Model) {
            return null;
        }

        return $this->makeProductDto(
            type: 'phu_kien_ngoi',
            id: $id,
            name: $this->productName($product, $item['name'] ?? 'Phụ kiện ngói'),
            price: (float) ($item['price'] ?? 0),
            image: $this->productImage($product, 'images'),
            routeName: 'client.products.phu-kien-ngoi.detail',
            routeParams: $accessoryType ? ['id' => $id, 'type' => $accessoryType] : null,
        );
    }

    private function makeProductDto(
        string $type,
        int $id,
        string $name,
        float $price,
        ?string $image,
        string $routeName,
        ?array $routeParams = null
    ): object {
        return (object) [
            'type' => $type,
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'image' => AssetPath::url($image, 'assets/images/ngoi-01.jpg'),
            'url' => Route::has($routeName) ? route($routeName, $routeParams ?? $id) : '#',
        ];
    }

    private function productName(Model $product, string $fallback): string
    {
        return (string) (data_get($product, 'name') ?: data_get($product, 'title') ?: $fallback);
    }

    private function productImage(Model $product, string $field): ?string
    {
        $value = data_get($product, $field);

        if (is_array($value)) {
            return $value[0] ?? null;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? ($decoded[0] ?? null) : $value;
        }

        return null;
    }

    private function productTypeMap(): array
    {
        return [
            'ngoi_am_duong_ct' => [
                'model' => NgoiAmDuongCt::class,
                'route' => 'client.products.ngoi-am-duong.detail',
                'image_field' => 'images',
                'fallback_name' => 'Ngói âm dương',
                'has_delete' => true,
            ],
            'ngoi_hai_van_mieu_ct' => [
                'model' => NgoiHaiVanMieuCt::class,
                'route' => 'client.products.ngoi-hai-van-mieu.detail',
                'image_field' => 'images',
                'fallback_name' => 'Ngói hài văn miếu',
                'has_delete' => true,
            ],
            'ngoi_hai_co_ct' => [
                'model' => NgoiHaiCoCt::class,
                'route' => 'client.products.ngoi-hai-co.detail',
                'image_field' => 'images',
                'fallback_name' => 'Ngói hài cổ',
                'has_delete' => true,
            ],
            'gach_hoa_thong_gio_ct' => [
                'model' => GachHoaThongGioCt::class,
                'route' => 'client.products.gach-hoa-thong-gio.detail',
                'image_field' => 'images',
                'fallback_name' => 'Gạch hoa thông gió',
                'has_delete' => true,
            ],
            'gach_trang_tri_ct' => [
                'model' => GachTrangTriCt::class,
                'route' => 'client.products.gach-trang-tri.detail',
                'image_field' => 'images',
                'fallback_name' => 'Gạch trang trí',
                'has_delete' => true,
            ],
            'gach_co_bat_trang_ct' => [
                'model' => GachCoBatTrangCt::class,
                'route' => 'client.products.gach-co-bat-trang.detail',
                'image_field' => 'images',
                'fallback_name' => 'Gạch cổ Bát Tràng',
                'has_delete' => true,
            ],
            'linh_vat_phong_thuy_ct' => [
                'model' => LinhVatPhongThuyCt::class,
                'route' => 'client.products.linh-vat-phong-thuy.detail',
                'image_field' => 'images',
                'fallback_name' => 'Linh vật phong thủy',
                'has_delete' => true,
            ],
            'lan_can_gom_xu' => [
                'model' => LanCanGomXu::class,
                'route' => 'client.products.lan-can-gom-su.detail',
                'image_field' => 'thumbnail_main',
                'fallback_name' => 'Lan can gốm sứ',
                'has_delete' => false,
            ],
            'den_gom_su' => [
                'model' => DenGomSu::class,
                'route' => 'client.products.den-gom-su.detail',
                'image_field' => 'thumbnail_main',
                'fallback_name' => 'Đèn gốm sứ',
                'has_delete' => false,
            ],
        ];
    }
}
