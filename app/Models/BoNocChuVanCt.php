<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoNocChuVanCt extends Model
{
    protected $table = 'bo_noc_chu_van_ct';
    protected $primaryKey = 'bo_noc_chu_van_ct_id';

    protected $fillable =[
        'name', 'images', 'des', 'size', 'size_image', 'size_des', 'is_delete',
    ];

    protected function casts(): array
    {
        return[
            'images'   => 'array',
            'des'      => 'array',
            'size_des' => 'array',
        ];
    }

    public function phanLoais(): HasMany
    {
        return $this->hasMany(PhanLoaiBoNocChuVanCt::class, 'bo_noc_chu_van_ct_id', 'bo_noc_chu_van_ct_id');
    }
}