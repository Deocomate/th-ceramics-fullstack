<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantLegacyId extends Model
{
    public $timestamps = false;

    protected $fillable = ['source_table', 'source_id', 'product_variant_id'];
}
