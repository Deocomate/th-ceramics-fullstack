<?php

namespace App\Domains\Content\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class CoreValue extends Model
{
    protected $table = 'gia_tri_vuot_troi';

    protected $primaryKey = 'gia_tri_vuot_troi_id';

    protected $fillable = [
        'title',
        'desscription',
        'image',
    ];
}
