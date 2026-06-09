<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TinTuc extends Model
{
    protected $table = 'tin_tuc';

    protected $primaryKey = 'tin_tuc_id';

    protected $fillable = [
        'danh_muc_tin_tuc_id',
        'tieu_de',
        'slug',
        'anh_dai_dien',
        'mo_ta_ngan',
        'the_loai',
        'noi_dung_blocks',
        'trang_thai',
        'ngay_dang',
    ];

    protected function casts(): array
    {
        return [
            'noi_dung_blocks' => 'array',
            'ngay_dang' => 'datetime',
        ];
    }

    public function danhMuc(): BelongsTo
    {
        return $this->belongsTo(DanhMucTinTuc::class, 'danh_muc_tin_tuc_id', 'danh_muc_tin_tuc_id');
    }
}
