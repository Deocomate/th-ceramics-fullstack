<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuAn extends Model
{
    protected $table = 'du_an';
    protected $primaryKey = 'du_an_id';

    protected $fillable =[
        'ten_du_an',
        'dia_diem',
        'san_pham',
        'nam',
        'images',
        'danh_muc_du_an_id',
        'slug',
    ];

    protected function casts(): array
    {
        return[
            'images' => 'array',
            'nam' => 'integer',
        ];
    }

    public function danhMuc(): BelongsTo
    {
        return $this->belongsTo(DanhMucDuAn::class, 'danh_muc_du_an_id', 'danh_muc_du_an_id');
    }
}