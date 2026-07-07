<?php

namespace App\Providers;

use App\Models\PageContact;
use App\Models\TrangChu;
use App\Services\CartService;
use App\Services\GiaTriVuotTroiService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public static function resolveIsEcommerceEnabled(): bool
    {
        if (! Schema::hasTable('trang_chu') || ! Schema::hasColumn('trang_chu', 'is_ecommerce_enabled')) {
            return true;
        }

        return (bool) Cache::rememberForever(
            'site_ecommerce_enabled',
            static fn () => (bool) (TrangChu::query()->value('is_ecommerce_enabled') ?? true),
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.client.layouts.header', function ($view) {
            $view->with('cartCount', app(CartService::class)->getCount());
        });

        if (! Schema::hasTable('page_contact')) {
            View::share('globalContact', null);
        } else {
            $globalContact = Cache::rememberForever('global_contact', static function () {
                return PageContact::first();
            });

            View::share('globalContact', $globalContact);
        }

        View::composer('*', function ($view): void {
            $view->with('isEcommerceEnabled', static::resolveIsEcommerceEnabled());
        });

        if (Schema::hasTable('gia_tri_vuot_troi')) {
            View::share('giaTriVuotTroi', app(GiaTriVuotTroiService::class)->getAll());
        } else {
            View::share('giaTriVuotTroi', collect());
        }
    }
}
