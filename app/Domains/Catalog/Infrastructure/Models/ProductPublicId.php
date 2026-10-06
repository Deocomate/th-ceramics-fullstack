<?php

namespace App\Domains\Catalog\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPublicId extends Model
{
    public $timestamps = false;

    protected $fillable = ['type_key', 'public_id', 'product_id'];
}
