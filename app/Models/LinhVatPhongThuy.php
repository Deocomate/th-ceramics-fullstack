<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LinhVatPhongThuy extends Model
{
    protected $table = 'linh_vat_phong_thuy';

    protected $primaryKey = 'linh_vat_phong_thuy_id';

    protected $fillable = [
        'thumbnail_main',
        'video',
    ];

    public function linhVat(): HasMany
    {
        return $this->hasMany(LinhVat::class, 'linh_vat_phong_thuy_id', 'linh_vat_phong_thuy_id');
    }

    public function anh(): HasMany
    {
        return $this->hasMany(LinhVatPhongThuyAnh::class, 'linh_vat_phong_thuy_id', 'linh_vat_phong_thuy_id');
    }
}
