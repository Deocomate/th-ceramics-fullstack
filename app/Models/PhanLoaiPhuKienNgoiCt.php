<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhanLoaiPhuKienNgoiCt extends Model
{
    protected $table = 'phan_loai_phu_kien_ngoi_ct';

    protected $primaryKey = 'phan_loai_phu_kien_ngoi_ct_id';

    protected $fillable = [
        'name',
        'code',
        'price',
        'phu_kien_ngoi_ct_id',
        'legacy_type',
        'legacy_id',
        'is_delete',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(PhuKienNgoiCt::class, 'phu_kien_ngoi_ct_id', 'phu_kien_ngoi_ct_id');
    }
}
