<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaiThuongThanhTuu extends Model
{
    protected $table = 'giai_thuong_thanh_tuu';
    protected $primaryKey = 'giai_thuong_thanh_tuu_id';
    
    protected $fillable = [
        'image',
        'des',
    ];
}