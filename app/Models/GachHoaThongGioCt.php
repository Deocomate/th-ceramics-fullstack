<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GachHoaThongGioCt extends Model
{
    protected $table = 'gach_hoa_thong_gio_ct';
    protected $primaryKey = 'gach_hoa_thong_gio_ct_id';

    protected $fillable =[
        'code',
        'name',
        'images',
        'price',
        'des',
        'size',
        'size_image',
        'is_delete',
    ];

    protected function casts(): array
    {
        return[
            'images' => 'array',
            'des'    => 'array',
        ];
    }
}