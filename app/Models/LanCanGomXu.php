<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanCanGomXu extends Model
{
    protected $table = 'lan_can_gom_xu';

    protected $primaryKey = 'lan_can_gom_xu_id';

    protected $fillable = [
        'thumbnail_main',
        'video',
        'section_1_image',
        'section_1_title',
        'section_1_products',
        'section_2_image',
        'section_2_title',
        'section_2_products',
    ];

    protected function casts(): array
    {
        return [
            'section_1_products' => 'array',
            'section_2_products' => 'array',
        ];
    }
}
