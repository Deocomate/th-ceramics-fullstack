<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhuKienNgoi extends Model
{
    protected $table = 'phu_kien_ngoi';

    protected $primaryKey = 'phu_kien_ngoi_id';

    protected $fillable = [
        'thumbnail_main',
        'banner_text_1',
        'banner_text_2',
        'sec1_title',
        'sec1_image',
        'sec2_title',
        'sec2_image',
        'images',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
        ];
    }
}
