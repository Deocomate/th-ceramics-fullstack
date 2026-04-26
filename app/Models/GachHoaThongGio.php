<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GachHoaThongGio extends Model
{
    protected $table = 'gach_hoa_thong_gio';

    protected $primaryKey = 'gach_hoa_thong_gio_id';

    protected $fillable =[
        'image',
        'video',
    ];

    public function anh(): HasMany
    {
        return $this->hasMany(GachHoaThongGioAnh::class, 'gach_hoa_thong_gio_id', 'gach_hoa_thong_gio_id');
    }

    public function giaTri(): HasMany
    {
        return $this->hasMany(GiaTriGachHoaThongGio::class, 'gach_hoa_thong_gio_id', 'gach_hoa_thong_gio_id');
    }
}