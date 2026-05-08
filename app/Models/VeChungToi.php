<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeChungToi extends Model
{
    protected $table = 've_chung_toi';
    protected $primaryKey = 've_chung_toi_id';

    protected $fillable =[
        'banner', 'header_banner', 'body_banner',
        'gs_head', 'gs_gia_tri', 'gs_hanh_trinh',
        'gs_nguoi_sang_lap_anh', 'gs_nguoi_sang_lap_noi_dung', 'gs_giai_thuong',
        'nt_head', 'nt_body', 'nt_ngon_ngu',
        'nt_che_tac_head', 'nt_che_tac_body', 'nt_che_tac_anh',
        'nt_luyen_dat_head', 'nt_luyen_dat_body', 'nt_luyen_dat_item',
        'nt_dun_lo_head', 'nt_dun_lo_body', 'nt_dun_lo_anh',
    ];

    protected function casts(): array
    {
        return[
            'gs_head' => 'array',
            'gs_gia_tri' => 'array',
            'gs_hanh_trinh' => 'array',
            'gs_giai_thuong' => 'array',
            'nt_ngon_ngu' => 'array',
            'nt_che_tac_anh' => 'array',
            'nt_luyen_dat_item' => 'array',
            'nt_dun_lo_anh' => 'array',
        ];
    }
}