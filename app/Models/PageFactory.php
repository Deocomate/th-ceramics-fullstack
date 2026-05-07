<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageFactory extends Model
{
    protected $table = 'page_factory';

    protected $primaryKey = 'page_factory_id';

    protected $fillable = [
        'hero_banner_desktop',
        'hero_banner_mobile',
        'intro_title',
        'intro_subtitle',
        'intro_description',
        'gallery_1',
        'process_title',
        'process_description',
        'process_slider',
        'process_bottom_title',
        'process_bottom_desc',
        'process_bottom_image',
        'material_slider',
        'material_steps',
    ];

    protected function casts(): array
    {
        return [
            'gallery_1' => 'array',
            'process_slider' => 'array',
            'material_slider' => 'array',
            'material_steps' => 'array',
        ];
    }
}
