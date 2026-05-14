<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GachTrangTriCt extends Model
{
    protected $table = 'gach_trang_tri_ct';

    protected $primaryKey = 'gach_trang_tri_ct_id';

    protected $fillable = [
        'code',
        'name',
        'color',
        'images',
        'price',
        'des',
        'size',
        'size_image',
        'is_delete',
    ];

    protected $attributes = [
        'color' => 'Tự chọn',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'des' => 'array',
        ];
    }
}
