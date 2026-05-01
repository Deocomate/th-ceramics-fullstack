<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DauAnGachTrangTri extends Model
{
    protected $table = 'dau_an_gach_trang_tri';

    protected $primaryKey = 'dau_an_gach_trang_tri_id';

    protected $fillable =[
        'background',
        'title',
        'location',
        'description',
        'gach_trang_tri_id',
    ];

    public function gachTrangTri(): BelongsTo
    {
        return $this->belongsTo(GachTrangTri::class, 'gach_trang_tri_id', 'gach_trang_tri_id');
    }
}