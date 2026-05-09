<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThiCong extends Model
{
    protected $table = 'thi_cong';

    protected $primaryKey = 'thi_cong';

    protected $fillable =[
        'tieu_de',
        'anh',
        'link_youtube',
    ];
}