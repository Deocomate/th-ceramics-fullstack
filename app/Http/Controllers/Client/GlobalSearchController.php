<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GachCoBatTrangCt;
use App\Models\GachHoaThongGioCt;
use App\Models\GachTrangTriCt;
use App\Models\LinhVatPhongThuyCt;
use App\Models\MauSacNgoiHaiCoCt;
use App\Models\MauSacNgoiHaiVanMieuCt;
use App\Models\NgoiAmDuongCt;
use App\Models\PhanLoaiBoNocChuVanCt;
use App\Models\PhanLoaiNgoiBoNocCt;
use App\Support\AssetPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GlobalSearchController extends Controller
{
    /**
     * Handle quick product search requests from the header.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $keyword = trim((string) $request->query('q', ''));

        if (mb_strlen($keyword) < 2) {
            return response()->json(['products' => []]);
        }

        $limit = 8;

        $products = collect()
            ->concat($this->searchDirect(NgoiAmDuongCt::class, 'ngoi_am_duong_ct_id', 'Ngói Âm Dương', 'client.products.ngoi-am-duong.detail', $keyword, $limit))
            ->concat($this->searchDirect(GachHoaThongGioCt::class, 'gach_hoa_thong_gio_ct_id', 'Gạch Hoa Thông Gió', 'client.products.gach-hoa-thong-gio.detail', $keyword, $limit))
            ->concat($this->searchDirect(GachTrangTriCt::class, 'gach_trang_tri_ct_id', 'Gạch Trang Trí', 'client.products.gach-trang-tri.detail', $keyword, $limit))
            ->concat($this->searchDirect(GachCoBatTrangCt::class, 'gach_co_bat_trang_ct_id', 'Gạch Cổ Bát Tràng', 'client.products.gach-co-bat-trang.detail', $keyword, $limit))
            ->concat($this->searchDirect(LinhVatPhongThuyCt::class, 'linh_vat_phong_thuy_ct_id', 'Linh Vật Phong Thủy', 'client.products.linh-vat-phong-thuy.detail', $keyword, $limit))
            ->concat($this->searchColorVariants(
                MauSacNgoiHaiVanMieuCt::class,
                'ngoi_hai_van_mieu_ct_id',
                'Ngói Hài Văn Miếu',
                'client.products.ngoi-hai-van-mieu.detail',
                $keyword,
                $limit
            ))
            ->concat($this->searchColorVariants(
                MauSacNgoiHaiCoCt::class,
                'ngoi_hai_co_ct_id',
                'Ngói Hài Cổ',
                'client.products.ngoi-hai-co.detail',
                $keyword,
                $limit
            ))
            ->concat($this->searchAccessoryVariants(
                PhanLoaiNgoiBoNocCt::class,
                'ngoi_bo_noc_ct_id',
                'Ngói Bờ Nóc',
                'bo_noc',
                $keyword,
                $limit
            ))
            ->concat($this->searchAccessoryVariants(
                PhanLoaiBoNocChuVanCt::class,
                'bo_noc_chu_van_ct_id',
                'Bờ Nóc Chữ Vạn',
                'chu_van',
                $keyword,
                $limit
            ))
            ->take($limit)
            ->values();

        return response()->json(['products' => $products]);
    }

    /**
     * @param  class-string  $modelClass
     */
    private function searchDirect(
        string $modelClass,
        string $primaryKey,
        string $category,
        string $routeName,
        string $keyword,
        int $limit
    ): Collection {
        $like = $this->likeKeyword($keyword);

        return $modelClass::query()
            ->select([$primaryKey, 'name', 'code', 'images', 'price'])
            ->where('is_delete', 0)
            ->where(function (Builder $query) use ($like) {
                $query->where('name', 'like', $like)
                    ->orWhere('code', 'like', $like);
            })
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn ($product) => $this->formatProduct(
                (int) $product->{$primaryKey},
                (string) $product->name,
                (string) $product->code,
                $category,
                $this->firstImage($product->images),
                route($routeName, $product->{$primaryKey}),
                (int) $product->price
            ));
    }

    /**
     * @param  class-string  $variantClass
     */
    private function searchColorVariants(
        string $variantClass,
        string $foreignKey,
        string $category,
        string $routeName,
        string $keyword,
        int $limit
    ): Collection {
        $like = $this->likeKeyword($keyword);

        return $variantClass::query()
            ->select([$variantClass::query()->getModel()->getKeyName(), 'name', 'image', 'code', 'price', $foreignKey])
            ->with(['product' => function ($query) use ($foreignKey) {
                $query->select([$foreignKey, 'name', 'images', 'is_delete']);
            }])
            ->where('is_delete', 0)
            ->where(function (Builder $query) use ($like) {
                $query->where('name', 'like', $like)
                    ->orWhere('code', 'like', $like)
                    ->orWhereHas('product', fn (Builder $productQuery) => $productQuery->where('name', 'like', $like));
            })
            ->latest()
            ->limit($limit)
            ->get()
            ->filter(fn ($variant) => $variant->product && (int) $variant->product->is_delete === 0)
            ->map(fn ($variant) => $this->formatVariantProduct($variant, $foreignKey, $category, $routeName));
    }

    /**
     * @param  class-string  $variantClass
     */
    private function searchAccessoryVariants(
        string $variantClass,
        string $foreignKey,
        string $category,
        string $type,
        string $keyword,
        int $limit
    ): Collection {
        $like = $this->likeKeyword($keyword);

        return $variantClass::query()
            ->select([$variantClass::query()->getModel()->getKeyName(), 'name', 'code', 'price', $foreignKey])
            ->with(['product' => function ($query) use ($foreignKey) {
                $query->select([$foreignKey, 'name', 'images', 'is_delete']);
            }])
            ->where('is_delete', 0)
            ->where(function (Builder $query) use ($like) {
                $query->where('name', 'like', $like)
                    ->orWhere('code', 'like', $like)
                    ->orWhereHas('product', fn (Builder $productQuery) => $productQuery->where('name', 'like', $like));
            })
            ->latest()
            ->limit($limit)
            ->get()
            ->filter(fn ($variant) => $variant->product && (int) $variant->product->is_delete === 0)
            ->map(fn ($variant) => $this->formatProduct(
                (int) $variant->{$foreignKey},
                trim($variant->product->name.' - '.$variant->name),
                (string) $variant->code,
                $category,
                $this->firstImage($variant->product->images),
                route('client.products.phu-kien-ngoi.detail', ['id' => $variant->{$foreignKey}, 'type' => $type]),
                (int) $variant->price
            ));
    }

    private function formatVariantProduct($variant, string $foreignKey, string $category, string $routeName): array
    {
        $name = trim($variant->product->name.' - '.$variant->name);

        return $this->formatProduct(
            (int) $variant->{$foreignKey},
            $name,
            (string) $variant->code,
            $category,
            AssetPath::url($variant->image ?: $this->imageCandidate($variant->product->images), 'assets/images/logo.png'),
            route($routeName, $variant->{$foreignKey}),
            (int) $variant->price
        );
    }

    private function formatProduct(
        int $id,
        string $name,
        string $code,
        string $category,
        string $image,
        string $url,
        int $price
    ): array {
        return [
            'id' => $id,
            'name' => $name,
            'code' => $code,
            'category' => $category,
            'image' => $image,
            'url' => $url,
            'price' => $price,
            'price_formatted' => number_format($price, 0, ',', '.').'đ',
        ];
    }

    private function firstImage($images): string
    {
        return AssetPath::url($this->imageCandidate($images), 'assets/images/logo.png');
    }

    private function imageCandidate($images): ?string
    {
        if (is_string($images)) {
            $decoded = json_decode($images, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $images = $decoded;
            } else {
                return $images;
            }
        }

        if ($images instanceof EloquentCollection) {
            $images = $images->all();
        }

        if (is_array($images)) {
            return collect($images)
                ->filter(fn ($image) => is_string($image) && trim($image) !== '')
                ->first();
        }

        return null;
    }

    private function likeKeyword(string $keyword): string
    {
        return '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $keyword).'%';
    }
}
