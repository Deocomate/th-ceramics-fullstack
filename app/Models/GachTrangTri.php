<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GachTrangTri extends Model
{
    protected $table = 'gach_trang_tri';

    protected $primaryKey = 'gach_trang_tri_id';

    protected $fillable =[
        'thumbnail_main',
        'video',
        'images',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
        ];
    }

    public function dauAn(): HasMany
    {
        return $this->hasMany(DauAnGachTrangTri::class, 'gach_trang_tri_id', 'gach_trang_tri_id');
    }
}