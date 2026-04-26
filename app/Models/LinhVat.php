<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinhVat extends Model
{
    protected $table = 'linh_vat';

    protected $primaryKey = 'linh_vat_id';

    protected $fillable =[
        'image',
        'title',
        'description',
        'linh_vat_phong_thuy_id',
    ];

    public function linhVatPhongThuy(): BelongsTo
    {
        return $this->belongsTo(LinhVatPhongThuy::class, 'linh_vat_phong_thuy_id', 'linh_vat_phong_thuy_id');
    }
}