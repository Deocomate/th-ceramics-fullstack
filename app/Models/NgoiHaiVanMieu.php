<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgoiHaiVanMieu extends Model
{
    protected $table = 'ngoi_hai_van_mieu';

    protected $primaryKey = 'ngoi_hai_van_mieu_id';

    protected $fillable =[
        'thumbnail_main',
        'title1',
        'thumbnail1',
        'title2',
        'thumbnail2',
        'title3',
        'thumbnail3',
        'video',
    ];
}