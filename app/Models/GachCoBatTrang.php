<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GachCoBatTrang extends Model
{
    protected $table = 'gach_co_bat_trang';

    protected $primaryKey = 'gach_co_bat_trang_id';

    protected $fillable =[
        'thumbnail_main',
        'video',
    ];

    public function anh(): HasMany
    {
        return $this->hasMany(GachCoBatTrangAnh::class, 'gach_co_bat_trang_id', 'gach_co_bat_trang_id');
    }
}