<?php

namespace App\Domains\Catalog\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FengShuiCreature extends Model
{
    protected $table = 'linh_vat_phong_thuy';

    protected $primaryKey = 'linh_vat_phong_thuy_id';

    protected $fillable = [
        'thumbnail_main',
        'video',
    ];

    public function linhVat(): HasMany
    {
        return $this->hasMany(FengShuiCreatureLegacy::class, 'linh_vat_phong_thuy_id', 'linh_vat_phong_thuy_id');
    }

    public function anh(): HasMany
    {
        return $this->hasMany(FengShuiCreatureImage::class, 'linh_vat_phong_thuy_id', 'linh_vat_phong_thuy_id');
    }
}
