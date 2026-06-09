<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinhMucNgoiAmDuong extends Model
{
    protected $table = 'dinh_muc_ngoi_am_duong';

    protected $primaryKey = 'dinh_muc_ngoi_am_duong_id';

    protected $fillable = [
        'roof_type',
        'tile_type',
        'ngoi_am',
        'ngoi_duong',
        'diem',
    ];
}
