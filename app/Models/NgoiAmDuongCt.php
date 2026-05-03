<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgoiAmDuongCt extends Model
{
    protected $table = 'ngoi_am_duong_ct';
    protected $primaryKey = 'ngoi_am_duong_ct_id';

    protected $fillable =[
        'code',
        'name',
        'images',
        'price',
        'des',
        'size',
        'size_image',
        'is_delete',
    ];

    protected function casts(): array
    {
        return[
            'images' => 'array',
            'des'    => 'array',
        ];
    }
}