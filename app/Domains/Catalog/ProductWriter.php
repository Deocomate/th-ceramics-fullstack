<?php

namespace App\Domains\Catalog;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductDisplayOption;
use App\Domains\Catalog\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductWriter
{
    /** @var list<string> */
    private const PRODUCT_FIELDS = [
        'category_type', 'legacy_type', 'legacy_id', 'name', 'color', 'des',
        'size', 'size_image', 'size_des', 'video', 'dinh_muc', 'weight',
        'priority', 'is_delete',
    ];

    public function create(string $type, array $attributes): Product
    {
        $this->validateType($type);
        $data = $this->validatedAttributes($attributes, true);
        $this->validateDefaultVariantFields($attributes);

        return DB::transaction(function () use ($type, $data, $attributes): Product {
            $product = Product::create(['type_key' => $type, ...$data]);
            app(PublicIdAllocator::class)->product($product);

            // Default variant for standalone or initial pricing
            $code = $attributes['code'] ?? null;
            $price = isset($attributes['price']) ? (int) $attributes['price'] : null;
            if ($code !== null || $price !== null) {
                $variant = $product->variants()->create([
                    'sku' => $code,
                    'price' => $price,
                    'is_default' => true,
                    'is_delete' => false,
                ]);
                app(PublicIdAllocator::class)->variant($variant->setRelation('product', $product));
            }

            if (isset($attributes['images']) && is_array($attributes['images'])) {
                $this->syncImagesArray($product, $attributes['images']);
            }

            return $product->refresh()->load(['publicId', 'variants.publicId', 'media']);
        });
    }

    public function update(Product $product, array $attributes): Product
    {
        $data = $this->validatedAttributes($attributes, false);
        $this->validateDefaultVariantFields(
            $attributes,
            $product->variants()->where('is_default', true)->value('id')
        );

        return DB::transaction(function () use ($product, $data, $attributes): Product {
            $product->update($data);

            if (array_key_exists('code', $attributes) || array_key_exists('price', $attributes)) {
                $defaultVariant = $product->variants()->where('is_default', true)->first();
                $variantData = [];
                if (array_key_exists('code', $attributes)) {
                    $variantData['sku'] = $attributes['code'];
                }
                if (array_key_exists('price', $attributes)) {
                    $variantData['price'] = $attributes['price'] !== null ? (int) $attributes['price'] : null;
                }

                if ($defaultVariant) {
                    $defaultVariant->update($variantData);
                } elseif ($variantData !== []) {
                    $variant = $product->variants()->create([
                        ...$variantData,
                        'is_default' => true,
                        'is_delete' => false,
                    ]);
                    app(PublicIdAllocator::class)->variant($variant->setRelation('product', $product));
                }
            }

            if (isset($attributes['images']) && is_array($attributes['images'])) {
                $this->syncImagesArray($product, $attributes['images']);
            }

            return $product->refresh()->load(['publicId', 'variants.publicId', 'media']);
        });
    }

    public function setHidden(Product $product, bool $hidden): Product
    {
        $product->update(['is_delete' => $hidden]);

        return $product->refresh()->load(['publicId', 'variants.publicId', 'media']);
    }

    public function softDelete(Product $product): Product
    {
        return $this->setHidden($product, true);
    }

    public function restore(Product $product): Product
    {
        return $this->setHidden($product, false);
    }

    public function saveVariant(Product $product, array $attributes, ?ProductVariant $variant = null): ProductVariant
    {
        if (array_key_exists('code', $attributes) && ! array_key_exists('sku', $attributes)) {
            $attributes['sku'] = $attributes['code'];
        }

        $data = Validator::make($attributes, [
            'name' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('product_variants', 'sku')->ignore($variant?->id)],
            'price' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
            'is_default' => ['sometimes', 'boolean'],
            'is_delete' => ['sometimes', 'boolean'],
        ])->validate();

        if ($variant && $variant->product_id !== $product->id) {
            throw new \InvalidArgumentException('Biến thể không thuộc sản phẩm.');
        }

        return DB::transaction(function () use ($product, $variant, $data): ProductVariant {
            if ($variant) {
                $variant->update($data);
            } else {
                $variant = $product->variants()->create($data);
                app(PublicIdAllocator::class)->variant($variant->setRelation('product', $product));
            }

            return $variant->refresh()->load('publicId');
        });
    }

    public function deleteVariant(ProductVariant $variant): void
    {
        $variant->update(['is_delete' => true]);
    }

    /** @param list<array{kind: string, path: string, is_cover?: bool}> $media */
    public function replaceMedia(Product $product, array $media): Product
    {
        $rows = Validator::make(['media' => $media], [
            'media' => ['array'],
            'media.*.kind' => ['required', Rule::in(['image', 'video'])],
            'media.*.path' => ['required', 'string', 'max:1000'],
            'media.*.is_cover' => ['sometimes', 'boolean'],
        ])->validate()['media'];

        $coverIndex = null;
        foreach ($rows as $index => $row) {
            if (($row['is_cover'] ?? false) && $row['kind'] === 'image') {
                if ($coverIndex !== null) {
                    throw new \InvalidArgumentException('Gallery chỉ được có một ảnh bìa.');
                }
                $coverIndex = $index;
            }
        }
        if ($coverIndex === null) {
            foreach ($rows as $index => $row) {
                if ($row['kind'] === 'image') {
                    $coverIndex = $index;
                    break;
                }
            }
        }

        return DB::transaction(function () use ($product, $rows, $coverIndex): Product {
            $product->media()->delete();
            foreach ($rows as $index => $row) {
                $product->media()->create([
                    'kind' => $row['kind'],
                    'path' => $row['path'],
                    'sort_order' => $index,
                    'is_cover' => $index === $coverIndex,
                ]);
            }

            return $product->refresh()->load(['publicId', 'variants.publicId', 'media']);
        });
    }

    public function addMedia(Product $product, string $path, string $kind = 'image', bool $isCover = false): Product
    {
        $nextSort = (int) $product->media()->max('sort_order') + 1;
        $hasCover = $product->media()->where('is_cover', true)->exists();

        $product->media()->create([
            'kind' => $kind,
            'path' => $path,
            'sort_order' => $nextSort,
            'is_cover' => $isCover || (! $hasCover && $kind === 'image'),
        ]);

        return $product->refresh()->load(['publicId', 'variants.publicId', 'media']);
    }

    public function removeMedia(Product $product, string $path): Product
    {
        $media = $product->media()->where('path', $path)->first();
        if ($media) {
            $wasCover = $media->is_cover;
            $media->delete();

            if ($wasCover) {
                $firstImage = $product->media()->where('kind', 'image')->orderBy('sort_order')->first();
                $firstImage?->update(['is_cover' => true]);
            }
        }

        return $product->refresh()->load(['publicId', 'variants.publicId', 'media']);
    }

    public function reorderMedia(Product $product, array $orderedPaths): Product
    {
        return DB::transaction(function () use ($product, $orderedPaths): Product {
            $product->media()->increment('sort_order', 10000);

            $firstImagePath = null;
            foreach ($orderedPaths as $index => $raw) {
                $isImage = str_starts_with($raw, 'image:');
                $path = preg_replace('/^(image|video):/', '', $raw);
                if ($isImage && $firstImagePath === null) {
                    $firstImagePath = $path;
                }
                $product->media()->where('path', $path)->update(['sort_order' => $index]);
            }

            if ($firstImagePath !== null) {
                $product->media()->update(['is_cover' => false]);
                $product->media()->where('path', $firstImagePath)->where('kind', 'image')->update(['is_cover' => true]);
            }

            return $product->refresh()->load(['publicId', 'variants.publicId', 'media']);
        });
    }

    public function saveDisplayOption(string $type, array $attributes, ?ProductDisplayOption $option = null): ProductDisplayOption
    {
        $data = Validator::make($attributes, [
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ])->validate();

        return DB::transaction(function () use ($type, $data, $option): ProductDisplayOption {
            if ($option) {
                $option->update($data);
                return $option->refresh();
            }

            $nextSort = isset($data['sort_order'])
                ? $data['sort_order']
                : ((int) ProductDisplayOption::where('type_key', $type)->max('sort_order') + 1);
            $nextPublicId = max(
                (int) ProductDisplayOption::where('type_key', $type)->max('legacy_id'),
                (int) ProductDisplayOption::where('type_key', $type)->max('id')
            ) + 1;

            return ProductDisplayOption::create([
                'type_key' => $type,
                'legacy_id' => $nextPublicId,
                'sort_order' => $nextSort,
                ...$data,
            ]);
        });
    }

    public function deleteDisplayOption(ProductDisplayOption $option): void
    {
        $option->delete();
    }

    private function syncImagesArray(Product $product, array $images): void
    {
        $media = [];
        foreach ($images as $img) {
            if (is_string($img)) {
                $media[] = ['kind' => 'image', 'path' => $img];
            } elseif (is_array($img)) {
                $kind = ($img['type'] ?? '') === 'video' ? 'video' : 'image';
                $path = $img['path'] ?? $img['url'] ?? '';
                if ($path !== '') {
                    $media[] = ['kind' => $kind, 'path' => $path];
                }
            }
        }
        $this->replaceMedia($product, $media);
    }

    private function validateType(string $type): void
    {
        if (ProductTypeRegistry::get($type) === null) {
            throw new \InvalidArgumentException('Loại sản phẩm không hợp lệ.');
        }
    }

    private function validateDefaultVariantFields(array $attributes, ?int $variantId = null): void
    {
        Validator::make($attributes, [
            'code' => ['sometimes', 'nullable', 'string', 'max:50', Rule::unique('product_variants', 'sku')->ignore($variantId)],
            'price' => ['sometimes', 'nullable', 'integer', 'min:0'],
        ])->validate();
    }

    private function validatedAttributes(array $attributes, bool $creating): array
    {
        $data = array_intersect_key($attributes, array_flip(self::PRODUCT_FIELDS));
        $rules = [
            'name' => [$creating ? 'required' : 'sometimes', 'string', 'max:255'],
            'category_type' => ['nullable', 'string', 'max:30'],
            'legacy_type' => ['nullable', 'string', 'max:30'],
            'legacy_id' => ['nullable', 'integer', 'min:1'],
            'color' => ['nullable', 'string', 'max:100'],
            'des' => ['nullable'],
            'size' => ['nullable', 'string', 'max:255'],
            'size_image' => ['nullable', 'string', 'max:255'],
            'size_des' => ['nullable'],
            'video' => ['nullable', 'string', 'max:500'],
            'dinh_muc' => ['nullable', 'string', 'max:50'],
            'weight' => ['nullable', 'string', 'max:50'],
            'priority' => ['sometimes', 'integer', 'min:0'],
            'is_delete' => ['sometimes', 'boolean'],
        ];

        return Validator::make($data, $rules)->validate();
    }
}
