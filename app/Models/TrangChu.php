<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TrangChu extends Model
{
    protected $table = 'trang_chu';

    protected $primaryKey = 'trang_chu_id';

    protected $fillable = [
        'banner',
        'khach_hang_doi_tac',
        'loi_tri_an',
        'loi_tri_an_anh',
        've_chung_toi_logo',
        'video',
        'nhung_con_so',
        'showroom_images',
        'showroom_noidung',
        'is_ecommerce_enabled',
    ];

    protected static function booted(): void
    {
        static::saved(static function (): void {
            Cache::forget('site_ecommerce_enabled');
        });

        static::deleted(static function (): void {
            Cache::forget('site_ecommerce_enabled');
        });
    }

    protected function casts(): array
    {
        return [
            'is_ecommerce_enabled' => 'boolean',
            'banner' => 'array',
            'khach_hang_doi_tac' => 'array',
            'loi_tri_an' => 'array',
            've_chung_toi_logo' => 'array',
            'nhung_con_so' => 'array',
            'showroom_images' => 'array',
        ];
    }
}
