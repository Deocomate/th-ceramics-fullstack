<?php

namespace App\Domains\Content\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPageConfig extends Model
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
