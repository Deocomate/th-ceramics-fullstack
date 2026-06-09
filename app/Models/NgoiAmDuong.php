<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgoiAmDuong extends Model
{
    protected $table = 'ngoi_am_duong';

    protected $primaryKey = 'ngoi_am_duong_id';

    protected $fillable = [
        'thumbnail_main',
        'thumbnail1',
        'thumbnail2',
        'video',
    ];
}
