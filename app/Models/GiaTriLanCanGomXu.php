<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiaTriLanCanGomXu extends Model
{
    protected $table = 'gia_tri_lan_can_gom_xu';

    protected $primaryKey = 'gia_tri_lan_can_gom_xu_id';

    protected $fillable =[
        'image',
        'title',
        'desscription',
        'lan_can_gom_xu_id',
    ];

    public function lanCanGomXu(): BelongsTo
    {
        return $this->belongsTo(LanCanGomXu::class, 'lan_can_gom_xu_id', 'lan_can_gom_xu_id');
    }
}