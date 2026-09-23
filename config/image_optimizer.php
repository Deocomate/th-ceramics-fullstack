<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Optimization Enabled
    |--------------------------------------------------------------------------
    |
    | Global switch to enable or disable automatic image resizing and conversion.
    | When disabled, FileUploadHelper falls back to standard file storing.
    |
    */
    'enabled' => env('IMAGE_OPTIMIZE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Target Output Format
    |--------------------------------------------------------------------------
    |
    | Options:
    |   - 'webp': Convert to high-quality .webp format (Recommended for SEO & speed)
    |   - 'original': Keep original format (e.g. JPG stays JPG, PNG stays PNG) but resize and compress
    |
    */
    'format' => env('IMAGE_OPTIMIZE_FORMAT', 'webp'),

    /*
    |--------------------------------------------------------------------------
    | Default Compression Quality (High Fidelity for Architectural Ceramics)
    |--------------------------------------------------------------------------
    |
    | Quality level from 1 to 100.
    | For architectural ceramics (gốm sứ kiến trúc: ngói, gạch hoa, phù điêu men rạn),
    | quality 92 provides near-lossless clarity, sharp edges, and zero pixelation.
    |
    */
    'quality' => (int) env('IMAGE_OPTIMIZE_QUALITY', 92),

    /*
    |--------------------------------------------------------------------------
    | EXIF Handling
    |--------------------------------------------------------------------------
    |
    | auto_orient: Read EXIF orientation tags from smartphone/camera and rotate
    | strip_exif: Remove metadata (GPS, camera info) to save bytes and protect privacy
    |
    */
    'auto_orient' => true,
    'strip_exif' => true,

    /*
    |--------------------------------------------------------------------------
    | Directory-Specific Presets
    |--------------------------------------------------------------------------
    |
    | High-resolution presets tailored for high-end ceramics showcase.
    | Ensures sharp zoom capability on Retina / 2K / 4K displays.
    |
    */
    'presets' => [
        'avatar' => [
            'matches' => ['users/avatars', 'avatars', 'user'],
            'max_width' => 500,
            'max_height' => 500,
            'quality' => 92,
        ],
        'banner' => [
            'matches' => ['ve_chung_toi/banner', 'trang_chu/banner', 'banners', 'hero'],
            'max_width' => 2560,
            'max_height' => 1440,
            'quality' => 92,
        ],
        'product' => [
            'matches' => [
                'ngoi_am_duong',
                'ngoi_hai_co',
                'ngoi_hai_van_mieu',
                'gach_hoa_thong_gio',
                'gach_trang_tri',
                'gach_co_bat_trang',
                'linh_vat_phong_thuy',
                'lan_can_gom_su',
                'den_vuon_gom_su',
                'phu_kien_ngoi',
                'gallery',
                'products',
            ],
            'max_width' => 2000,
            'max_height' => 2000,
            'quality' => 92,
        ],
        'article' => [
            'matches' => ['tin_tuc/images', 'tin_tuc/blocks', 'tin_tuc', 'articles', 'posts'],
            'max_width' => 1600,
            'max_height' => 1600,
            'quality' => 90,
        ],
        'default' => [
            'max_width' => 2000,
            'max_height' => 2000,
            'quality' => 92,
        ],
    ],
];
