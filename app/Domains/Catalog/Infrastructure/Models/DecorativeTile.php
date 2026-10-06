<?php

namespace App\Domains\Catalog\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class DecorativeTile extends Model
{
    protected $table = 'gach_trang_tri';

    protected $primaryKey = 'gach_trang_tri_id';

    protected $fillable = [
        'thumbnail_main',
        'video',
        'images',
        'ung_dung_da_dang',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'ung_dung_da_dang' => 'array',
        ];
    }
}
