<?php

namespace App\Domains\Protection\Infrastructure;

use App\Domains\Content\Infrastructure\Models\HomePageConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ProtectionSettings
{
    public const CACHE_KEY = 'site_content_protection';

    private const DISABLED = ['deterrence' => false, 'devtools_guard' => false];

    public function deterrenceEnabled(): bool
    {
        return $this->flags()['deterrence'];
    }

    public function devtoolsGuardEnabled(): bool
    {
        return $this->flags()['devtools_guard'];
    }

    /** @return array{deterrence: bool, devtools_guard: bool} */
    private function flags(): array
    {
        $request = app()->bound('request') ? request() : null;
        if ($request?->attributes->has(self::CACHE_KEY)) {
            return $request->attributes->get(self::CACHE_KEY);
        }

        $flags = self::DISABLED;
        if (Schema::hasTable('trang_chu') && Schema::hasColumn('trang_chu', 'is_content_protection_enabled')) {
            $flags = Cache::rememberForever(self::CACHE_KEY, static function (): array {
                $config = HomePageConfig::query()->first(['is_content_protection_enabled', 'is_devtools_guard_enabled']);

                return [
                    'deterrence' => (bool) $config?->is_content_protection_enabled,
                    'devtools_guard' => (bool) $config?->is_devtools_guard_enabled,
                ];
            });
        }
        $request?->attributes->set(self::CACHE_KEY, $flags);

        return $flags;
    }
}
