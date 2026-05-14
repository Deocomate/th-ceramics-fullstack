<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinhVatPhongThuyCt extends Model
{
    protected $table = 'linh_vat_phong_thuy_ct';

    protected $primaryKey = 'linh_vat_phong_thuy_ct_id';

    protected $fillable = [
        'code',
        'name',
        'color',
        'images',
        'price',
        'des',
        'size',
        'size_image',
        'size_des', // Cột đặc biệt của Linh Vật
        'is_delete',
    ];

    protected $attributes = [
        'color' => 'Tự chọn',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'des' => 'array',
            'size_des' => 'array',
        ];
    }
}
