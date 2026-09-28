<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function legacyIds(): HasMany
    {
        return $this->hasMany(VariantLegacyId::class);
    }
}
