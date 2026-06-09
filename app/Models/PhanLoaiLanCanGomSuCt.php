<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhanLoaiLanCanGomSuCt extends Model
{
    protected $table = 'phan_loai_lan_can_gom_su_ct';

    protected $primaryKey = 'phan_loai_lan_can_gom_su_ct_id';

    protected $fillable = ['name', 'code', 'price', 'lan_can_gom_su_ct_id', 'is_delete'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(LanCanGomSuCt::class, 'lan_can_gom_su_ct_id', 'lan_can_gom_su_ct_id');
    }
}
