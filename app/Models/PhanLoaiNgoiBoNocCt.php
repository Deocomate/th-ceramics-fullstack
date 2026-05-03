<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhanLoaiNgoiBoNocCt extends Model
{
    protected $table = 'phan_loai_ngoi_bo_noc_ct';
    protected $primaryKey = 'phan_loai_ngoi_bo_noc_ct_id';

    protected $fillable =[
        'name', 'code', 'price', 'ngoi_bo_noc_ct_id', 'is_delete'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(NgoiBoNocCt::class, 'ngoi_bo_noc_ct_id', 'ngoi_bo_noc_ct_id');
    }
}