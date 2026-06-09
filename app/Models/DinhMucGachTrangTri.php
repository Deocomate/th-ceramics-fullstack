<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinhMucGachTrangTri extends Model
{
    protected $table = 'dinh_muc_gach_trang_tri';

    protected $primaryKey = 'dinh_muc_gach_trang_tri_id';

    protected $fillable = [
        'brick_type',
        'value',
    ];
}
