<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GachCoBatTrangAnh extends Model
{
    protected $table = 'gach_co_bat_trang_anh';

    protected $primaryKey = 'gach_co_bat_trang_anh_id';

    protected $fillable = [
        'image',
        'gach_co_bat_trang_id',
    ];

    public function gachCoBatTrang(): BelongsTo
    {
        return $this->belongsTo(GachCoBatTrang::class, 'gach_co_bat_trang_id', 'gach_co_bat_trang_id');
    }
}
