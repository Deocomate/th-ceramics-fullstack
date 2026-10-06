<?php

namespace App\Domains\Content\Infrastructure\View;

use App\Domains\Content\Infrastructure\Models\ContactPageConfig;
use App\Domains\Content\Infrastructure\Models\CoreValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class ContentViewComposer
{
    public static function boot(): void
    {
        // 1. Share globalContact with caching
        if (! Schema::hasTable('page_contact')) {
            View::share('globalContact', null);
        } else {
            $globalContact = Cache::rememberForever('global_contact', static function () {
                $record = ContactPageConfig::query()->first();
                if (! $record) {
                    return null;
                }

                // Return model instance (which also has backwards-compatible alias)
                return $record;
            });

            View::share('globalContact', $globalContact);
        }

        // 2. Share giaTriVuotTroi
        if (Schema::hasTable('gia_tri_vuot_troi')) {
            View::share('giaTriVuotTroi', CoreValue::query()->orderBy('gia_tri_vuot_troi_id')->get());
        } else {
            View::share('giaTriVuotTroi', new Collection);
        }
    }
}
