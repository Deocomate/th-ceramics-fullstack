<?php

namespace App\Services;

use App\Models\DenGomSu;
use App\Models\DenVuonGomSuCt;
use App\Models\GachCoBatTrangCt;
use App\Models\GachHoaThongGioCt;
use App\Models\GachTrangTriCt;
use App\Models\LanCanGomSuCt;
use App\Models\LanCanGomXu;
use App\Models\LinhVatPhongThuyCt;
use App\Models\MauSacNgoiAmDuongCt;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiHaiCoCt;
use App\Models\NgoiHaiVanMieuCt;
use App\Models\PhuKienNgoiCt;
use App\Support\AssetPath;
use App\Support\ClientProductType;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductCartOptionsService
{
    public function getOptions(string $productType, int $productId): array
    {
        return match ($productType) {
            'ngoi_am_duong_ct' => $this->buildNgoiAmDuongOptions($productId),
            'ngoi_hai_van_mieu_ct' => $this->buildVariantOptions($productType, NgoiHaiVanMieuCt::class, $productId, 'Màu sắc', false),
            'ngoi_hai_co_ct' => $this->buildVariantOptions($productType, NgoiHaiCoCt::class, $productId, 'Màu sắc', true),
            'gach_hoa_thong_gio_ct' => $this->buildSimpleOptions('gach_hoa_thong_gio_ct', GachHoaThongGioCt::class, $productId),
            'gach_trang_tri_ct' => $this->buildSimpleOptions('gach_trang_tri_ct', GachTrangTriCt::class, $productId),
            'gach_co_bat_trang_ct' => $this->buildSimpleOptions('gach_co_bat_trang_ct', GachCoBatTrangCt::class, $productId),
            'linh_vat_phong_thuy_ct' => $this->buildSimpleOptions('linh_vat_phong_thuy_ct', LinhVatPhongThuyCt::class, $productId),
            'lan_can_gom_xu' => $this->buildContactOnlyOptions('lan_can_gom_xu', LanCanGomXu::class, $productId, 'thumbnail_main'),
            'den_gom_su' => $this->buildContactOnlyOptions('den_gom_su', DenGomSu::class, $productId, 'thumbnail_main'),
            'den_vuon_gom_su_ct' => $this->buildSoftDeleteVariantOptions($productType, DenVuonGomSuCt::class, $productId, 'Phân loại'),
            'phu_kien_ngoi_ct' => $this->buildSoftDeleteVariantOptions($productType, PhuKienNgoiCt::class, $productId, 'Phân loại'),
            'lan_can_gom_su_ct' => $this->buildSoftDeleteVariantOptions($productType, LanCanGomSuCt::class, $productId, 'Phân loại'),
            default => throw new Exception('Loại sản phẩm không hợp lệ.'),
        };
    }

    /** @param class-string<Model> $modelClass */
    private function buildSimpleOptions(string $productType, string $modelClass, int $productId): array
    {
        $product = $modelClass::findOrFail($productId);
        $price = (int) ($product->price ?? 0);
        $image = $this->firstImage($product->images ?? null);

        return $this->basePayload(
            productType: $productType,
            productId: $productId,
            name: (string) $product->name,
            imageUrl: AssetPath::url($image),
            requiresVariant: false,
            variantLabel: null,
            variants: [],
            defaultVariantId: null,
            unitPrice: $price,
            contactOnly: $price <= 0,
        );
    }

    /** @param class-string<Model> $modelClass */
    private function buildContactOnlyOptions(string $productType, string $modelClass, int $productId, string $imageField): array
    {
        $product = $modelClass::findOrFail($productId);

        return $this->basePayload(
            productType: $productType,
            productId: $productId,
            name: (string) ($product->name ?? $product->title ?? ''),
            imageUrl: AssetPath::url($product->{$imageField} ?? null),
            requiresVariant: false,
            variantLabel: null,
            variants: [],
            defaultVariantId: null,
            unitPrice: 0,
            contactOnly: true,
        );
    }

    private function buildNgoiAmDuongOptions(int $productId): array
    {
        $product = NgoiAmDuongCt::findOrFail($productId);
        $variants = MauSacNgoiAmDuongCt::query()
            ->orderBy('mau_sac_ngoi_am_duong_ct_id')
            ->get()
            ->map(fn ($variant) => [
                'id' => (int) $variant->mau_sac_ngoi_am_duong_ct_id,
                'name' => (string) $variant->name,
                'sku' => (string) ($product->code ?? ''),
                'price' => (int) $product->price,
                'price_formatted' => $this->formatVnd((int) $product->price),
                'image_url' => AssetPath::url($variant->image ?? $this->firstImage($product->images)),
            ])
            ->values()
            ->all();

        $price = (int) $product->price;
        $defaultVariantId = $variants[0]['id'] ?? null;

        return $this->basePayload(
            productType: 'ngoi_am_duong_ct',
            productId: $productId,
            name: (string) $product->name,
            imageUrl: AssetPath::url($this->firstImage($product->images)),
            requiresVariant: false,
            variantLabel: $variants !== [] ? 'Màu sắc' : null,
            variants: $variants,
            defaultVariantId: $defaultVariantId,
            unitPrice: $price,
            contactOnly: $price <= 0,
        );
    }

    /** @param class-string<Model> $modelClass */
    private function buildVariantOptions(
        string $productType,
        string $modelClass,
        int $productId,
        string $variantLabel,
        bool $requiresVariant,
    ): array {
        $product = $modelClass::findOrFail($productId);
        $config = ClientProductType::variantRelationConfig($productType);
        $variants = $this->mapRelationVariants($product, $config['relation'], $config['pk'], $product);
        $defaultVariant = $variants->sortBy('price')->first();
        $unitPrice = $defaultVariant['price'] ?? (int) ($product->price ?? 0);

        if (! $requiresVariant && $variants->isEmpty()) {
            $unitPrice = (int) ($product->price ?? 0);
        }

        return $this->basePayload(
            productType: $productType,
            productId: $productId,
            name: (string) $product->name,
            imageUrl: AssetPath::url($defaultVariant['image_url'] ?? $this->firstImage($product->images)),
            requiresVariant: $requiresVariant && $variants->isNotEmpty(),
            variantLabel: $variants->isNotEmpty() ? $variantLabel : null,
            variants: $variants->values()->all(),
            defaultVariantId: $defaultVariant['id'] ?? null,
            unitPrice: $unitPrice,
            contactOnly: $unitPrice <= 0 && $variants->isEmpty(),
        );
    }

    /** @param class-string<Model> $modelClass */
    private function buildSoftDeleteVariantOptions(
        string $productType,
        string $modelClass,
        int $productId,
        string $variantLabel,
    ): array {
        $product = $modelClass::query()
            ->where('is_delete', 0)
            ->findOrFail($productId);

        $config = ClientProductType::variantRelationConfig($productType);
        $variants = $this->mapRelationVariants($product, $config['relation'], $config['pk'], $product, true);
        $defaultVariant = $variants->sortBy('price')->first();
        $unitPrice = $defaultVariant['price'] ?? 0;

        return $this->basePayload(
            productType: $productType,
            productId: $productId,
            name: (string) $product->name,
            imageUrl: AssetPath::url($defaultVariant['image_url'] ?? $this->firstImage($product->images)),
            requiresVariant: ClientProductType::requiresVariantSelection($productType) && $variants->isNotEmpty(),
            variantLabel: $variants->isNotEmpty() ? $variantLabel : null,
            variants: $variants->values()->all(),
            defaultVariantId: $defaultVariant['id'] ?? null,
            unitPrice: $unitPrice,
            contactOnly: $unitPrice <= 0,
        );
    }

    /** @return Collection<int, array{id: int, name: string, sku: string, price: int, price_formatted: string, image_url: string}> */
    private function mapRelationVariants(
        Model $product,
        string $relation,
        string $pk,
        Model $parent,
        bool $activeOnly = false,
    ): Collection {
        $query = $product->{$relation}();

        if ($activeOnly) {
            $query->where('is_delete', 0);
        }

        return $query->get()->map(function ($variant) use ($pk, $parent) {
            $price = (int) ($variant->price ?? 0);
            $image = $variant->image ?? $this->firstImage($parent->images ?? null);

            return [
                'id' => (int) $variant->{$pk},
                'name' => (string) $variant->name,
                'sku' => (string) ($variant->code ?? ''),
                'price' => $price,
                'price_formatted' => $this->formatVnd($price),
                'image_url' => AssetPath::url($image),
            ];
        });
    }

    /**
     * @param  array<int, array{id: int, name: string, sku: string, price: int, price_formatted: string, image_url: string}>  $variants
     */
    private function basePayload(
        string $productType,
        int $productId,
        string $name,
        string $imageUrl,
        bool $requiresVariant,
        ?string $variantLabel,
        array $variants,
        ?int $defaultVariantId,
        int $unitPrice,
        bool $contactOnly,
    ): array {
        return [
            'product_type' => $productType,
            'product_id' => $productId,
            'name' => $name,
            'image_url' => $imageUrl,
            'requires_variant' => $requiresVariant,
            'variant_label' => $variantLabel,
            'variants' => $variants,
            'default_variant_id' => $defaultVariantId,
            'unit_price' => $unitPrice,
            'unit_price_formatted' => $contactOnly ? 'Liên hệ' : $this->formatVnd($unitPrice),
            'contact_only' => $contactOnly,
        ];
    }

    private function formatVnd(int $amount): string
    {
        return number_format($amount, 0, ',', '.').' đ';
    }

    private function firstImage(mixed $images): ?string
    {
        if (is_string($images)) {
            $decoded = json_decode($images, true);

            return is_array($decoded) ? ($decoded[0] ?? null) : $images;
        }

        if (is_array($images)) {
            return $images[0] ?? null;
        }

        return null;
    }
}
