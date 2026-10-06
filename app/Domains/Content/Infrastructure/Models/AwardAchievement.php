<?php

namespace App\Domains\Content\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class AwardAchievement extends Model
{
    protected $table = 'giai_thuong_thanh_tuu';

    protected $primaryKey = 'giai_thuong_thanh_tuu_id';

    protected $fillable = [
        'image',
        'des',
    ];
}
