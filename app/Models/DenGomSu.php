<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DenGomSu extends Model
{
    protected $table = 'den_gom_su';

    protected $primaryKey = 'den_gom_su_id';

    protected $fillable = [
        'thumbnail_main',
        'video',
        'image1',
        'image2',
        'title2',
        'image3',
        'title3',
        'image4',
    ];

    public function anh(): HasMany
    {
        return $this->hasMany(DenGomSuAnh::class, 'den_gom_su_id', 'den_gom_su_id');
    }
}
