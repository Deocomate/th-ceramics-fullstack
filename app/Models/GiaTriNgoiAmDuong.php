<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiaTriNgoiAmDuong extends Model
{
    protected $table = 'gia_tri_ngoi_am_duong';

    protected $primaryKey = 'gia_tri_ngoi_am_duong_id';

    protected $fillable =[
        'title',
        'desscription', // Lưu ý: Giữ đúng tên cột có 2 chữ s như trong migration
        'image',
        'ngoi_am_duong_id',
    ];

    public function ngoiAmDuong(): BelongsTo
    {
        return $this->belongsTo(NgoiAmDuong::class, 'ngoi_am_duong_id', 'ngoi_am_duong_id');
    }
}