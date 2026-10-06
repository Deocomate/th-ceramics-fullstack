<?php

namespace App\Domains\Catalog\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDisplayOption extends Model
{
    protected $fillable = ['type_key', 'legacy_id', 'product_id', 'name', 'image', 'sort_order'];

    public static function findByPublicId(string $type, int $publicId): ?self
    {
        return self::query()->where('type_key', $type)->where('legacy_id', $publicId)->first()
            ?? self::query()->where('type_key', $type)->whereNull('legacy_id')->whereKey($publicId)->first();
    }

    public function getRouteKey()
    {
        return $this->legacy_id ?? $this->id;
    }

    public function getAttribute($key)
    {
        if (str_ends_with($key, '_ct_id') || $key === 'public_id' || $key === 'option_id') {
            return $this->legacy_id ?? $this->id;
        }

        return parent::getAttribute($key);
    }
}
