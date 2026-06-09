<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DenGomSuAnh extends Model
{
    protected $table = 'den_gom_su_anh';

    protected $primaryKey = 'den_gom_su_anh_id';

    protected $fillable = [
        'image',
        'den_gom_su_id',
    ];

    public function denGomSu(): BelongsTo
    {
        return $this->belongsTo(DenGomSu::class, 'den_gom_su_id', 'den_gom_su_id');
    }
}
