<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiaTriGachHoaThongGio extends Model
{
    protected $table = 'gia_tri_gach_hoa_thong_gio';

    protected $primaryKey = 'gia_tri_gach_hoa_thong_gio_id';

    protected $fillable =[
        'background',
        'image',
        'title',
        'desscription',
        'gach_hoa_thong_gio_id',
    ];

    public function gachHoaThongGio(): BelongsTo
    {
        return $this->belongsTo(GachHoaThongGio::class, 'gach_hoa_thong_gio_id', 'gach_hoa_thong_gio_id');
    }
}