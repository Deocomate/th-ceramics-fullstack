<?php

namespace App\Domains\Commerce\Infrastructure\Content;

use App\Domains\Commerce\Application\Ports\EcommerceStatusPort;
use App\Domains\Content\Models\TrangChu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ContentEcommerceStatusAdapter implements EcommerceStatusPort
{
    public function enabled(): bool
    {
        $request = app()->bound('request') ? request() : null;
        if ($request?->attributes->has('site_ecommerce_enabled')) {
            return (bool) $request->attributes->get('site_ecommerce_enabled');
        }

        $enabled = true;
        if (Schema::hasTable('trang_chu') && Schema::hasColumn('trang_chu', 'is_ecommerce_enabled')) {
            $enabled = (bool) Cache::rememberForever(
                'site_ecommerce_enabled',
                static fn () => (bool) (TrangChu::query()->value('is_ecommerce_enabled') ?? true),
            );
        }
        $request?->attributes->set('site_ecommerce_enabled', $enabled);

        return $enabled;
    }
}
