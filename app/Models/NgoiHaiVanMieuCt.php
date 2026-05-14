<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NgoiHaiVanMieuCt extends Model
{
    protected $table = 'ngoi_hai_van_mieu_ct';

    protected $primaryKey = 'ngoi_hai_van_mieu_ct_id';

    protected $fillable = [
        'name', 'color', 'images', 'price', 'des', 'mau_sac_id', 'size', 'size_image', 'is_delete',
    ];

    protected $attributes = [
        'color' => 'Tự chọn',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'des' => 'array',
        ];
    }

    public function mauSacs(): HasMany
    {
        return $this->hasMany(MauSacNgoiHaiVanMieuCt::class, 'ngoi_hai_van_mieu_ct_id', 'ngoi_hai_van_mieu_ct_id');
    }
}
