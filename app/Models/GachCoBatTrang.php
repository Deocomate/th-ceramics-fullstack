<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GachCoBatTrang extends Model
{
    protected $table = 'gach_co_bat_trang';

    protected $primaryKey = 'gach_co_bat_trang_id';

    protected $fillable = [
        'thumbnail_main',
        'video',
        'images',
        'section_bat',
        'section_that',
        'section_the',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'section_bat' => 'array',
            'section_that' => 'array',
            'section_the' => 'array',
        ];
    }

    public function anh(): HasMany
    {
        return $this->hasMany(GachCoBatTrangAnh::class, 'gach_co_bat_trang_id', 'gach_co_bat_trang_id');
    }
}
