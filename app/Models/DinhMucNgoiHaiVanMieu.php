<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinhMucNgoiHaiVanMieu extends Model
{
    protected $table = 'dinh_muc_ngoi_hai_van_mieu';
    protected $primaryKey = 'dinh_muc_ngoi_hai_van_mieu_id';

    protected $fillable =[
        'roof_type', 'ngoi_tren_mai_go', 'ngoi_tren_mai_be_tong',
    ];
}