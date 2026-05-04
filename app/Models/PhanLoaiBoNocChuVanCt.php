<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhanLoaiBoNocChuVanCt extends Model
{
    protected $table = 'phan_loai_bo_noc_chu_van_ct';
    protected $primaryKey = 'phan_loai_bo_noc_chu_van_ct_id';

    protected $fillable =[
        'name', 'code', 'price', 'bo_noc_chu_van_ct_id', 'is_delete'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(BoNocChuVanCt::class, 'bo_noc_chu_van_ct_id', 'bo_noc_chu_van_ct_id');
    }
}