<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TacGia extends Model
{
    protected $table = 'tac_gia';

    protected $primaryKey = 'tac_gia_id';

    protected $fillable =[
        'ten_tac_gia',
        'link_fb',
        'link_linkedin',
        'link_tele',
        'link_sky',
        'mo_ta',
        'anh_dai_dien',
    ];
}