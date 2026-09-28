<?php

namespace App\Services;

use App\Models\DenVuonGomSuCt;
use App\Models\GachCoBatTrangCt;
use App\Models\GachHoaThongGioCt;
use App\Models\GachTrangTriCt;
use App\Models\LanCanGomSuCt;
use App\Models\LinhVatPhongThuyCt;
use App\Models\MauSacNgoiHaiCoCt;
use App\Models\MauSacNgoiHaiVanMieuCt;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiHaiCoCt;
use App\Models\NgoiHaiVanMieuCt;
use App\Models\PhanLoaiDenVuonGomSuCt;
use App\Models\PhanLoaiLanCanGomSuCt;
use App\Models\PhanLoaiPhuKienNgoiCt;
use App\Models\PhuKienNgoiCt;
use App\Models\Product;
use App\Products\ProductTypeRegistry;
use App\Support\AssetPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UnifiedProductCatalog
{
    private const LEGACY_MODELS = [
        'ngoi_am_duong_ct' => NgoiAmDuongCt::class,
        'ngoi_hai_co_ct' => NgoiHaiCoCt::class,
        'ngoi_hai_van_mieu_ct' => NgoiHaiVanMieuCt::class,
        'gach_hoa_thong_gio_ct' => GachHoaThongGioCt::class,
        'gach_trang_tri_ct' => GachTrangTriCt::class,
        'gach_co_bat_trang_ct' => GachCoBatTrangCt::class,
        'linh_vat_phong_thuy_ct' => LinhVatPhongThuyCt::class,
        'phu_kien_ngoi_ct' => PhuKienNgoiCt::class,
        'lan_can_gom_su_ct' => LanCanGomSuCt::class,
        'den_vuon_gom_su_ct' => DenVuonGomSuCt::class,
    ];

    private const VARIANT_MODELS = [
        'mau_sac_ngoi_hai_co_ct' => MauSacNgoiHaiCoCt::class,
        'mau_sac_ngoi_hai_van_mieu_ct' => MauSacNgoiHaiVanMieuCt::class,
        'phan_loai_phu_kien_ngoi_ct' => PhanLoaiPhuKienNgoiCt::class,
        'phan_loai_lan_can_gom_su_ct' => PhanLoaiLanCanGomSuCt::class,
        'phan_loai_den_vuon_gom_su_ct' => PhanLoaiDenVuonGomSuCt::class,
    ];

    /** @return Collection<int, Model> */
    public function all(string $type, string $status = 'active', ?string $categoryType = null): Collection
    {
        $query = Product::query()->with(['variants.legacyIds', 'media', 'legacyIds'])->where('type_key', $type);
        if ($status === 'active') {
            $query->where('is_delete', false);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', true);
        }
        if ($categoryType !== null) {
            $query->where('category_type', $categoryType);
        }

        return $query->orderByDesc('priority')->orderByDesc('id')->get()
            ->map(fn (Product $product) => $this->project($product));
    }

    public function find(string $type, int $legacyId): Model
    {
        $product = Product::query()->with(['variants.legacyIds', 'media', 'legacyIds'])
            ->where('type_key', $type)
            ->whereHas('legacyIds', fn ($query) => $query->where('source_table', $type)->where('source_id', $legacyId))
            ->firstOrFail();

        return $this->project($product);
    }

    public function paginate(string $type, array $filters, int $perPage = 8, ?string $categoryType = null, string $pageName = 'page'): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['variants.legacyIds', 'media', 'legacyIds'])
            ->withMin(['variants as min_active_price' => fn ($q) => $q->where('is_delete', false)], 'price')
            ->where('type_key', $type)
            ->where('is_delete', false);
        if ($categoryType !== null) {
            $query->where('category_type', $categoryType);
        }
        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $like = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $search).'%';
                $q->where('name', 'like', $like)
                    ->orWhere('size', 'like', $like)
                    ->orWhereHas('variants', fn ($v) => $v->where('sku', 'like', $like));
            });
        }
        switch ($filters['sort'] ?? '') {
            case 'price_asc':
                $query->orderByRaw('min_active_price IS NULL')->orderBy('min_active_price');
                break;
            case 'price_desc':
                $query->orderByDesc('min_active_price');
                break;
            case 'name_asc':
                $query->orderBy('name');
                break;
            default:
                $query->orderByDesc('priority');
        }

        return $query->orderByDesc('id')->paginate($perPage, ['*'], $pageName)->withQueryString()
            ->through(fn (Product $product) => $this->project($product));
    }

    /** @return Collection<int, Model> */
    public function related(string $type, int $legacyId, ?string $categoryType, int $limit): Collection
    {
        return Product::query()->with(['variants.legacyIds', 'media', 'legacyIds'])
            ->where('type_key', $type)
            ->where('is_delete', false)
            ->when($categoryType !== null, fn ($q) => $q->where('category_type', $categoryType))
            ->whereDoesntHave('legacyIds', fn ($q) => $q->where('source_table', $type)->where('source_id', $legacyId))
            ->orderByDesc('priority')->orderByDesc('id')->limit($limit)->get()
            ->map(fn (Product $product) => $this->project($product));
    }

    /** @return Collection<int, array<string, mixed>> */
    public function search(string $keyword, int $limit = 8): Collection
    {
        $like = '%'.str_replace(['%', '_'], ['\\%', '\\_'], trim($keyword)).'%';

        return Product::query()->with(['variants.legacyIds', 'media', 'legacyIds'])
            ->where('is_delete', false)
            ->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhereHas('variants', fn ($v) => $v->where('is_delete', false)->where('sku', 'like', $like));
            })
            ->orderByDesc('priority')->orderByDesc('id')->limit($limit)->get()
            ->map(function (Product $product) {
                $config = ProductTypeRegistry::get($product->type_key);
                $id = (int) $product->legacyIds->firstWhere('source_table', $product->type_key)?->source_id;
                $variant = $product->variants->where('is_delete', false)->sortBy('price')->first();
                $image = $variant?->image ?: $product->media->firstWhere('kind', 'image')?->path;
                $price = (int) ($variant?->price ?? 0);

                return [
                    'id' => $id,
                    'name' => $product->name,
                    'code' => $variant?->sku ?? '',
                    'category' => $config['label'],
                    'image' => AssetPath::url($image, 'assets/images/logo.png'),
                    'url' => route(ProductTypeRegistry::detailRoute($product->type_key, $product->category_type), $id),
                    'price' => $price,
                    'price_formatted' => number_format($price, 0, ',', '.').'đ',
                ];
            });
    }

    /** @return array{name: string, variant_name: ?string, sku: ?string, price: int, image: ?string} */
    public function cartDetails(string $type, int $legacyId, ?int $legacyVariantId): array
    {
        $product = $this->findProduct($type, $legacyId);
        $config = ProductTypeRegistry::get($type);
        $default = $product->variants->firstWhere('is_default', true);
        $variant = null;
        $option = null;
        if ($legacyVariantId !== null && $type === 'ngoi_am_duong_ct') {
            $option = \App\Models\ProductDisplayOption::query()
                ->where('type_key', $type)->where('legacy_id', $legacyVariantId)->firstOrFail();
        } elseif ($legacyVariantId !== null && $config['variant_table']) {
            $variant = $product->variants->first(fn ($item) => ! $item->is_delete
                && $item->legacyIds->contains(fn ($id) => $id->source_table === $config['variant_table']
                    && (int) $id->source_id === $legacyVariantId));
            if (! $variant) {
                throw (new ModelNotFoundException)->setModel(\App\Models\ProductVariant::class, [$legacyVariantId]);
            }
        }
        if ($config['requires_variant'] && $variant === null) {
            throw new \InvalidArgumentException('Vui lòng chọn phân loại sản phẩm.');
        }
        $sellable = $variant ?: $default;
        $image = $option?->image ?: $sellable?->image ?: $product->media->firstWhere('kind', 'image')?->path;

        return [
            'name' => $product->name,
            'variant_name' => $option?->name ?? $variant?->name,
            'sku' => $sellable?->sku ?? $default?->sku,
            'price' => (int) ($sellable?->price ?? 0),
            'image' => $image,
        ];
    }

    public function cartOptions(string $type, int $legacyId): array
    {
        $product = $this->findProduct($type, $legacyId);
        $config = ProductTypeRegistry::get($type);
        $cover = $product->media->firstWhere('kind', 'image')?->path;
        $default = $product->variants->firstWhere('is_default', true);
        if ($type === 'ngoi_am_duong_ct') {
            $variants = \App\Models\ProductDisplayOption::query()->where('type_key', $type)->orderBy('sort_order')->get()
                ->map(fn ($item) => [
                    'id' => (int) $item->legacy_id,
                    'name' => $item->name,
                    'sku' => (string) ($default?->sku ?? ''),
                    'price' => (int) ($default?->price ?? 0),
                    'price_formatted' => number_format((int) ($default?->price ?? 0), 0, ',', '.').' đ',
                    'image_url' => AssetPath::url($item->image ?: $cover),
                ])->all();
        } else {
            $variants = $product->variants->where('is_default', false)->where('is_delete', false)
                ->map(function ($item) use ($config, $cover) {
                    $id = $item->legacyIds->firstWhere('source_table', $config['variant_table'])?->source_id;

                    return [
                        'id' => (int) $id,
                        'name' => $item->name,
                        'sku' => (string) ($item->sku ?? ''),
                        'price' => (int) ($item->price ?? 0),
                        'price_formatted' => number_format((int) ($item->price ?? 0), 0, ',', '.').' đ',
                        'image_url' => AssetPath::url($item->image ?: $cover),
                    ];
                })->sortBy('price')->values()->all();
        }
        $first = $variants[0] ?? null;
        $price = (int) ($first['price'] ?? $default?->price ?? 0);
        $requires = $config['requires_variant'] && $variants !== [];

        return [
            'product_type' => $type,
            'product_id' => $legacyId,
            'name' => $product->name,
            'image_url' => AssetPath::url($first['image_url'] ?? $cover),
            'requires_variant' => $requires,
            'variant_label' => $variants === [] ? null : ($type === 'ngoi_am_duong_ct' || str_starts_with($type, 'ngoi_hai_') ? 'Màu sắc' : 'Phân loại'),
            'variants' => $variants,
            'default_variant_id' => $first['id'] ?? null,
            'unit_price' => $price,
            'unit_price_formatted' => $price <= 0 ? 'Liên hệ' : number_format($price, 0, ',', '.').' đ',
            'contact_only' => $price <= 0,
        ];
    }

    private function findProduct(string $type, int $legacyId): Product
    {
        return Product::query()->with(['variants.legacyIds', 'media', 'legacyIds'])
            ->where('type_key', $type)
            ->where('is_delete', false)
            ->whereHas('legacyIds', fn ($query) => $query->where('source_table', $type)->where('source_id', $legacyId))
            ->firstOrFail();
    }

    public function project(Product $product): Model
    {
        $type = $product->type_key;
        $config = ProductTypeRegistry::get($type);
        $legacyClass = self::LEGACY_MODELS[$type];
        $legacyId = $product->legacyIds->firstWhere('source_table', $type)?->source_id;
        $default = $product->variants->firstWhere('is_default', true);
        $images = $product->media->map(function ($item) {
            if ($item->kind === 'image') {
                return $item->path;
            }

            return str_starts_with($item->path, 'http')
                ? ['type' => 'video', 'url' => $item->path]
                : ['type' => 'video', 'source' => 'file', 'path' => $item->path];
        })->all();
        $attributes = [
            $config['pk'] => $legacyId,
            'name' => $product->name,
            'color' => $product->color,
            'category_type' => $product->category_type,
            'legacy_type' => $product->legacy_type,
            'legacy_id' => $product->legacy_id,
            'images' => json_encode($images, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'code' => $default?->sku,
            'price' => $default?->price,
            'des' => $product->getRawOriginal('des'),
            'size' => $product->size,
            'size_image' => $product->size_image,
            'size_des' => $product->getRawOriginal('size_des'),
            'video' => $product->video,
            'dinh_muc' => $product->dinh_muc,
            'weight' => $product->weight,
            'priority' => $product->priority,
            'is_delete' => (int) $product->is_delete,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];
        /** @var Model $legacy */
        $legacy = new $legacyClass;
        $legacy->setRawAttributes($attributes, true);
        $legacy->exists = true;

        if ($config['variant_table']) {
            $variantClass = self::VARIANT_MODELS[$config['variant_table']];
            $variants = $product->variants->where('is_default', false)->map(function ($variant) use ($product, $config, $variantClass) {
                $legacyId = $variant->legacyIds->firstWhere('source_table', $config['variant_table'])?->source_id;
                $record = new $variantClass;
                $record->setRawAttributes([
                    $config['variant_pk'] => $legacyId,
                    $config['variant_fk'] => $product->legacyIds->firstWhere('source_table', $product->type_key)?->source_id,
                    'name' => $variant->name,
                    'code' => $variant->sku,
                    'price' => $variant->price,
                    'image' => $variant->image,
                    'is_delete' => (int) $variant->is_delete,
                ], true);
                $record->exists = true;

                return $record;
            })->values();
            $legacy->setRelation($config['relation'], $variants);
            $legacy->setAttribute('phan_loais_count', $variants->where('is_delete', 0)->count());
            $legacy->setAttribute('min_price', $variants->where('is_delete', 0)->min('price'));
        }

        // These aggregate values are display-only; keep them out of legacy UPDATE queries.
        $legacy->syncOriginal();

        return $legacy;
    }
}
