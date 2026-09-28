<?php

namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

class VariantPublicId extends Model
{
    public $timestamps = false;

    protected $fillable = ['type_key', 'public_id', 'product_variant_id'];
}
