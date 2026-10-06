<?php

namespace App\Domains\Catalog\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FengShuiCreatureImage extends Model
{
    protected $table = 'linh_vat_phong_thuy_anh';

    protected $primaryKey = 'linh_vat_phong_thuy_anh_id';

    protected $fillable = [
        'image',
        'linh_vat_phong_thuy_id',
    ];

    public function linhVatPhongThuy(): BelongsTo
    {
        return $this->belongsTo(FengShuiCreature::class, 'linh_vat_phong_thuy_id', 'linh_vat_phong_thuy_id');
    }
}
