<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\ProductTypeRegistry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'name', 'sku', 'price', 'image', 'is_default', 'is_delete'];

    protected function casts(): array
    {
        return ['is_default' => 'boolean', 'is_delete' => 'boolean', 'price' => 'integer'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function publicId(): HasOne
    {
        return $this->hasOne(VariantPublicId::class);
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
        return $this->sku;
    }

    public function setCodeAttribute(?string $value): void
    {
        $this->attributes['sku'] = $value;
    }

    public function getAttribute($key)
    {
        if ($key === 'code') {
            return $this->sku;
        }
        if ($key === 'public_id' || $key === 'variant_id') {
            return $this->getPublicIdAttribute();
        }
        if (str_ends_with($key, '_ct_id')) {
            if (str_starts_with($key, 'phan_loai_') || str_starts_with($key, 'mau_sac_')) {
                return $this->getPublicIdAttribute();
            }

            $product = $this->relationLoaded('product')
                ? $this->getRelation('product')
                : $this->product()->with('publicId')->first();

            return $product && $key === (ProductTypeRegistry::get($product->type_key)['pk'] ?? null)
                ? $product->public_id
                : null;
        }

        return parent::getAttribute($key);
    }
}
