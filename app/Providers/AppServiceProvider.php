<?php

namespace App\Providers;

use App\Models\PageContact;
use App\Services\CartService;
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

            return;
        }

        $globalContact = Cache::rememberForever('global_contact', static function () {
            return PageContact::first();
        });

        View::share('globalContact', $globalContact);
    }
}
