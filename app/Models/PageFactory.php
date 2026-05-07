<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    private function decodeJsonSafe($value): array
    {
        if (empty($value)) {
            return [];
        }
        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode((string) $value, true);

        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        return is_array($decoded) ? $decoded : [];
    }

    protected function gallery1(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->decodeJsonSafe($value),
            set: fn ($value) => is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE),
        );
    }

    protected function processSlider(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->decodeJsonSafe($value),
            set: fn ($value) => is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE),
        );
    }

    protected function materialSlider(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->decodeJsonSafe($value),
            set: fn ($value) => is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE),
        );
    }

    protected function materialSteps(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->decodeJsonSafe($value),
            set: fn ($value) => is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE),
        );
    }
}
