<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhanLoaiDenVuonGomSuCt extends Model
{
    protected $table = 'phan_loai_den_vuon_gom_su_ct';

    protected $primaryKey = 'phan_loai_den_vuon_gom_su_ct_id';

    protected $fillable = ['name', 'code', 'price', 'den_vuon_gom_su_ct_id', 'is_delete'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(DenVuonGomSuCt::class, 'den_vuon_gom_su_ct_id', 'den_vuon_gom_su_ct_id');
    }
}
