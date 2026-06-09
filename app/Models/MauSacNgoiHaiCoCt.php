<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MauSacNgoiHaiCoCt extends Model
{
    protected $table = 'mau_sac_ngoi_hai_co_ct';

    protected $primaryKey = 'mau_sac_ngoi_hai_co_ct_id';

    protected $fillable = [
        'name', 'image', 'code', 'price', 'ngoi_hai_co_ct_id', 'is_delete',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(NgoiHaiCoCt::class, 'ngoi_hai_co_ct_id', 'ngoi_hai_co_ct_id');
    }
}
