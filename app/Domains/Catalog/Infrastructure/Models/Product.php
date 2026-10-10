<?php

namespace App\Domains\Catalog\Infrastructure\Models;

use App\Domains\Catalog\Domain\ProductTypeRegistry;
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

    /** @return array<string, mixed> */
    private function typeConfig(): array
    {
        return ProductTypeRegistry::get((string) $this->type_key) ?? ProductTypeRegistry::defaults();
    }

    public function getDisplayPriceAttribute(): string
    {
        $config = $this->typeConfig();
        $price = match ($config['price_source']) {
            'min' => $this->min_price ?? $this->display_variant?->price ?? $this->price,
            'variant' => $this->display_variant?->price ?? $this->price,
            default => $this->price,
        };

        return ($price !== null && $price > 0)
            ? sprintf($config['price_format'], number_format((float) $price, 0, ',', '.'))
            : $config['price_empty'];
    }

    public function getDisplayCodeAttribute(): string
    {
        $config = $this->typeConfig();
        $code = $config['code_source'] === 'variant' ? $this->display_variant?->sku : $this->code;
        if (filled($code)) {
            return (string) $code;
        }

        if ($config['code_falls_back_to_default'] && filled($this->code)) {
            return (string) $this->code;
        }

        $prefix = $config['code_prefixes'][$this->category_type] ?? $config['code_prefixes']['default'] ?? null;

        return $prefix !== null ? $prefix.$this->public_id : $config['code_empty'];
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
