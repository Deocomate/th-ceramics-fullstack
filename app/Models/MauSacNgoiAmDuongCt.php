<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MauSacNgoiAmDuongCt extends Model
{
    protected $table = 'mau_sac_ngoi_am_duong_ct';
    protected $primaryKey = 'mau_sac_ngoi_am_duong_ct_id';

    protected $fillable = [
        'name',
        'image',
    ];
}