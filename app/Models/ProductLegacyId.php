<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLegacyId extends Model
{
    public $timestamps = false;

    protected $fillable = ['source_table', 'source_id', 'product_id'];
}
