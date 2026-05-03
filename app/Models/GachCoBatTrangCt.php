<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GachCoBatTrangCt extends Model
{
    protected $table = 'gach_co_bat_trang_ct';
    protected $primaryKey = 'gach_co_bat_trang_ct_id';

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