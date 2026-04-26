<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NgoiAmDuong extends Model
{
    protected $table = 'ngoi_am_duong';

    protected $primaryKey = 'ngoi_am_duong_id';

    protected $fillable =[
        'thumbnail_main',
        'thumbnail1',
        'thumbnail2',
        'video',
    ];

    public function giaTri(): HasMany
    {
        return $this->hasMany(GiaTriNgoiAmDuong::class, 'ngoi_am_duong_id', 'ngoi_am_duong_id');
    }
}