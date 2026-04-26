<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhuKienNgoi extends Model
{
    protected $table = 'phu_kien_ngoi';

    protected $primaryKey = 'phu_kien_ngoi_id';

    protected $fillable =[
        'thumbnail_main',
        'images',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
        ];
    }
}