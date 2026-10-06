<?php

namespace App\Domains\Catalog\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMedia extends Model
{
    protected $table = 'product_media';

    protected $fillable = ['product_id', 'kind', 'path', 'sort_order', 'is_cover'];

    protected function casts(): array
    {
        return ['is_cover' => 'boolean'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
