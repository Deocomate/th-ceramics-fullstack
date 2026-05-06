<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinhMucGachCoBatTrang extends Model
{
    protected $table = 'dinh_muc_gach_co_bat_trang';
    protected $primaryKey = 'dinh_muc_gach_co_bat_trang_id';

    protected $fillable =[
        'brick_type',
        'value',
    ];
}