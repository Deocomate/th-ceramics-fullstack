<?php

namespace App\Domains\Catalog\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'type_key', 'category_type', 'legacy_type', 'legacy_id', 'name', 'color', 'des', 'size', 'size_image',
        'size_des', 'video', 'dinh_muc', 'weight', 'priority', 'is_delete',
    ];

    protected function casts(): array
    {
        return ['des' => 'array', 'size_des' => 'array', 'is_delete' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $model): void {
            if ($model->priority === null || (int) $model->priority === 0) {
                $query = static::query()->where('type_key', $model->type_key);
                if (filled($model->category_type)) {
                    $query->where('category_type', $model->category_type);
                }
                $model->priority = ((int) $query->max('priority')) + 1;
            }
        });
    }

    public function scopeOrderedByPriority($query)
    {
        return $query
            ->orderByDesc($this->qualifyColumn('priority'))
            ->orderByDesc($this->getQualifiedKeyName());
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function phanLoais(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_default', false);
    }

    public function mauSacs(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_default', false);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }

    public function displayOptions(): HasMany
    {
        return $this->hasMany(ProductDisplayOption::class)->orderBy('sort_order');
    }

    public function publicId(): HasOne
    {
        return $this->hasOne(ProductPublicId::class);
    }

    public function getPublicIdAttribute(): int
    {
        if ($this->relationLoaded('publicId') && $this->getRelation('publicId')) {
            return (int) $this->getRelation('publicId')->public_id;
        }

        return (int) ($this->publicId()->value('public_id') ?? $this->id);
    }

    public function getRouteKey()
    {
        return $this->public_id;
    }

    public function getCodeAttribute(): ?string
    {
        $default = $this->relationLoaded('variants')
            ? ($this->variants->firstWhere('is_default', true) ?? $this->variants->first())
            : ($this->variants()->where('is_default', true)->first() ?? $this->variants()->first());

        return $default?->sku;
    }

    public function getPriceAttribute(): ?int
    {
        $default = $this->relationLoaded('variants')
            ? ($this->variants->firstWhere('is_default', true) ?? $this->variants->first())
            : ($this->variants()->where('is_default', true)->first() ?? $this->variants()->first());

        return $default?->price !== null ? (int) $default->price : null;
    }

    public function getImagesAttribute(): array
    {
        $items = $this->relationLoaded('media') ? $this->media : $this->media()->get();

        return $items->map(function ($item) {
            if ($item->kind === 'image') {
                return $item->path;
            }

            return str_starts_with($item->path, 'http')
                ? ['type' => 'video', 'url' => $item->path]
                : ['type' => 'video', 'source' => 'file', 'path' => $item->path];
        })->values()->all();
    }

    public function getDisplayVariantAttribute(): ?ProductVariant
    {
        $variants = $this->relationLoaded('phanLoais')
            ? $this->phanLoais
            : ($this->relationLoaded('variants') ? $this->variants : $this->variants()->get());

        return $variants->where('is_delete', false)->sortBy('price')->first();
    }

    public function getMinPriceAttribute(): ?int
    {
        if (isset($this->attributes['min_price'])) {
            return (int) $this->attributes['min_price'];
        }
        $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->get();
        $activeVariants = $variants->where('is_delete', false);

        return $activeVariants->isEmpty() ? null : (int) $activeVariants->min('price');
    }

    public function getDisplayPriceAttribute(): string
    {
        if ($this->type_key === 'den_vuon_gom_su_ct') {
            $price = $this->min_price ?? $this->display_variant?->price ?? $this->price;

            return ($price !== null && $price > 0) ? 'Từ '.number_format((float) $price, 0, ',', '.').' đ' : 'Liên hệ';
        }

        if ($this->type_key === 'lan_can_gom_su_ct') {
            $price = $this->display_variant?->price ?? $this->price;

            return ($price !== null && $price > 0) ? 'Giá: '.number_format((float) $price, 0, ',', '.').' đ/m²' : 'Liên hệ';
        }

        if ($this->type_key === 'phu_kien_ngoi_ct') {
            $price = $this->display_variant?->price ?? $this->price;

            return ($price !== null && $price > 0) ? 'Giá: '.number_format((float) $price, 0, ',', '.').' đ/m²' : 'Giá: Liên hệ';
        }

        $price = $this->price;

        return ($price === null || $price <= 0) ? 'Liên hệ' : number_format($price, 0, ',', '.').' đ';
    }

    public function getDisplayCodeAttribute(): string
    {
        if ($this->type_key === 'den_vuon_gom_su_ct') {
            return (string) ($this->display_variant?->sku ?? $this->code ?? '');
        }

        if ($this->type_key === 'lan_can_gom_su_ct') {
            return (string) ($this->display_variant?->sku ?? 'Đang cập nhật');
        }

        if ($this->type_key === 'phu_kien_ngoi_ct') {
            $code = $this->display_variant?->sku;
            if ($code) {
                return $code;
            }

            return match ($this->category_type) {
                'chu_van' => 'PKN-CV'.$this->public_id,
                default => 'PKN-BN'.$this->public_id,
            };
        }

        return (string) ($this->code ?? '');
    }

    public function getAttribute($key)
    {
        if ($key === 'code') {
            return $this->getCodeAttribute();
        }
        if ($key === 'price') {
            return $this->getPriceAttribute();
        }
        if ($key === 'images') {
            return $this->getImagesAttribute();
        }
        if ($key === 'public_id') {
            return $this->getPublicIdAttribute();
        }
        if ($key === 'display_variant') {
            return $this->getDisplayVariantAttribute();
        }
        if ($key === 'min_price') {
            return $this->getMinPriceAttribute();
        }
        if ($key === 'display_price') {
            return $this->getDisplayPriceAttribute();
        }
        if ($key === 'display_code') {
            return $this->getDisplayCodeAttribute();
        }

        if (str_ends_with($key, '_ct_id') || in_array($key, ['product_id', 'legacy_ct_id'], true)) {
            return $this->getPublicIdAttribute();
        }

        return parent::getAttribute($key);
    }
}
