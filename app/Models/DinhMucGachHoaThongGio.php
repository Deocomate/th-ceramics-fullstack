<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinhMucGachHoaThongGio extends Model
{
    protected $table = 'dinh_muc_gach_hoa_thong_gio';
    protected $primaryKey = 'dinh_muc_gach_hoa_thong_gio_id';

    protected $fillable =[
        'brick_type',
        'value',
    ];
}