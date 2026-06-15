<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrangDuAn extends Model
{
    protected $table = 'trang_du_an';

    protected $primaryKey = 'trang_du_an_id';

    protected $fillable = [
        'promo_title',
        'promo_image',
        'promo_cta_label',
        'promo_cta_url',
        'promo_enabled',
    ];

    protected function casts(): array
    {
        return [
            'promo_enabled' => 'boolean',
        ];
    }
}
