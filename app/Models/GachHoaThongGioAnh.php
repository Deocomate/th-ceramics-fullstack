<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GachHoaThongGioAnh extends Model
{
    protected $table = 'gach_hoa_thong_gio_anh';

    protected $primaryKey = 'gach_hoa_thong_gio_anh_id';

    protected $fillable = [
        'image',
        'gach_hoa_thong_gio_id',
    ];

    public function gachHoaThongGio(): BelongsTo
    {
        return $this->belongsTo(GachHoaThongGio::class, 'gach_hoa_thong_gio_id', 'gach_hoa_thong_gio_id');
    }
}
