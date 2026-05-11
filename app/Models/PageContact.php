<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageContact extends Model
{
    protected $table = 'page_contact';

    protected $primaryKey = 'page_contact_id';

    protected $fillable = [
        'map_image',
        'hotline',
        'zalo_link',
        'form_title',
    ];

    protected static function booted(): void
    {
        static::saved(static function (): void {
            Cache::forget('global_contact');
        });

        static::deleted(static function (): void {
            Cache::forget('global_contact');
        });
    }
}
