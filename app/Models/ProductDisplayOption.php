<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDisplayOption extends Model
{
    protected $fillable = ['type_key', 'legacy_id', 'product_id', 'name', 'image', 'sort_order'];
}
