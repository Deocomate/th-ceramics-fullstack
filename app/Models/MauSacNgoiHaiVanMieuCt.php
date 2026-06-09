<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MauSacNgoiHaiVanMieuCt extends Model
{
    protected $table = 'mau_sac_ngoi_hai_van_mieu_ct';

    protected $primaryKey = 'mau_sac_ngoi_hai_van_mieu_ct_id';

    protected $fillable = [
        'name', 'image', 'code', 'price', 'ngoi_hai_van_mieu_ct_id', 'is_delete',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(NgoiHaiVanMieuCt::class, 'ngoi_hai_van_mieu_ct_id', 'ngoi_hai_van_mieu_ct_id');
    }
}
