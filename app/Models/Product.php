<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }

    public function displayOptions(): HasMany
    {
        return $this->hasMany(ProductDisplayOption::class);
    }

    public function legacyIds(): HasMany
    {
        return $this->hasMany(ProductLegacyId::class);
    }
}
