<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LanCanGomXu extends Model
{
    protected $table = 'lan_can_gom_xu';

    protected $primaryKey = 'lan_can_gom_xu_id';

    protected $fillable = [
        'thumbnail_main',
        'video',
    ];

    public function giaTri(): HasMany
    {
        return $this->hasMany(GiaTriLanCanGomXu::class, 'lan_can_gom_xu_id', 'lan_can_gom_xu_id');
    }
}