<?php

namespace App\Providers;

use App\Console\Commands\ContentArchiveCommand;
use App\Console\Commands\ContentWritesCommand;
use App\Domains\Archive\Application\Ports\CatalogArchivePort;
use App\Domains\Archive\Infrastructure\Adapters\CatalogArchiveAdapter;
use App\Domains\Catalog\Application\Ports\CatalogQueryPort;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Domains\Commerce\Application\CartService;
use App\Domains\Commerce\Application\Ports\CartSessionPort;
use App\Domains\Commerce\Application\Ports\CatalogCartProductPort;
use App\Domains\Commerce\Application\Ports\ConsultationRequestRepositoryPort;
use App\Domains\Commerce\Application\Ports\ConsultationSubmissionPort;
use App\Domains\Commerce\Application\Ports\CouponRepositoryPort;
use App\Domains\Commerce\Application\Ports\EcommerceStatusPort;
use App\Domains\Commerce\Application\Ports\OrderAdminRepositoryPort;
use App\Domains\Commerce\Application\Ports\OrderCheckoutPort;
use App\Domains\Commerce\Application\Ports\OrderStatusMailPort;
use App\Domains\Commerce\Application\Ports\ProductCartOptionsPort;
use App\Domains\Commerce\Application\Ports\SellableProductTypesPort;
use App\Domains\Commerce\Infrastructure\Catalog\CatalogCartProductAdapter;
use App\Domains\Commerce\Infrastructure\Catalog\CatalogProductCartOptionsAdapter;
use App\Domains\Commerce\Infrastructure\Catalog\CatalogSellableProductTypesAdapter;
use App\Domains\Commerce\Infrastructure\Content\ContentEcommerceStatusAdapter;
use App\Domains\Commerce\Infrastructure\Coupons\EloquentCouponRepository;
use App\Domains\Commerce\Infrastructure\Mail\EloquentOrderStatusMailAdapter;
use App\Domains\Commerce\Infrastructure\Models\ConsultationRequest;
use App\Domains\Commerce\Infrastructure\Persistence\EloquentConsultationAdapter;
use App\Domains\Commerce\Infrastructure\Persistence\EloquentOrderAdminAdapter;
use App\Domains\Commerce\Infrastructure\Persistence\EloquentOrderCheckoutAdapter;
use App\Domains\Commerce\Infrastructure\Session\LaravelCartSessionAdapter;
use App\Domains\Content\Infrastructure\View\ContentViewComposer;
use App\Domains\Media\Console\CleanStagedImagesCommand;
use App\Domains\Media\Console\OptimizeMediaCommand;
use App\View\Components\Client\Shared\OutstandingValue;
use App\View\Components\Client\Shared\ProductCard;
use App\View\Components\Client\Shared\ProductDetailContainer;
use App\View\Components\Client\Shared\Recommendations;
use App\View\Components\Client\Shared\Works;
use App\View\Components\Client\Shared\WorksSimple;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CatalogQueryPort::class,
            CatalogQueryService::class,
        );
        $this->app->bind(CartSessionPort::class, LaravelCartSessionAdapter::class);
        $this->app->bind(CatalogCartProductPort::class, CatalogCartProductAdapter::class);
        $this->app->bind(ProductCartOptionsPort::class, CatalogProductCartOptionsAdapter::class);
        $this->app->bind(SellableProductTypesPort::class, CatalogSellableProductTypesAdapter::class);
        $this->app->bind(EcommerceStatusPort::class, ContentEcommerceStatusAdapter::class);
        $this->app->bind(CouponRepositoryPort::class, EloquentCouponRepository::class);
        $this->app->bind(OrderCheckoutPort::class, EloquentOrderCheckoutAdapter::class);
        $this->app->bind(ConsultationSubmissionPort::class, EloquentConsultationAdapter::class);
        $this->app->bind(ConsultationRequestRepositoryPort::class, EloquentConsultationAdapter::class);
        $this->app->bind(OrderAdminRepositoryPort::class, EloquentOrderAdminAdapter::class);
        $this->app->bind(OrderStatusMailPort::class, EloquentOrderStatusMailAdapter::class);
        $this->app->bind(
            CatalogArchivePort::class,
            CatalogArchiveAdapter::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Component class aliases to preserve class constructors after tag renaming
        Blade::component(ProductCard::class, 'client.catalog.shared.product-card');
        Blade::component(ProductCard::class, 'client.shared.product-card');
        Blade::component(ProductDetailContainer::class, 'client.catalog.shared.product-detail-container');
        Blade::component(ProductDetailContainer::class, 'client.shared.product-detail-container');
        Blade::component(Recommendations::class, 'client.catalog.shared.recommendations');
        Blade::component(Recommendations::class, 'client.shared.recommendations');
        Blade::component(OutstandingValue::class, 'client.content.shared.outstanding-value');
        Blade::component(OutstandingValue::class, 'client.shared.outstanding-value');
        Blade::component(Works::class, 'client.content.shared.works');
        Blade::component(Works::class, 'client.shared.works');
        Blade::component(WorksSimple::class, 'client.content.shared.works-simple');
        Blade::component(WorksSimple::class, 'client.shared.works-simple');

        Blade::anonymousComponentPath(resource_path('views/components/client/catalog/shared'), 'client.shared');
        Blade::anonymousComponentPath(resource_path('views/components/client/catalog/products'), 'client.products');

        Blade::anonymousComponentPath(resource_path('views/components/client/content/about'), 'client.about');
        Blade::anonymousComponentPath(resource_path('views/components/client/content/factory'), 'client.factory');
        Blade::anonymousComponentPath(resource_path('views/components/client/content/home'), 'client.home');
        Blade::anonymousComponentPath(resource_path('views/components/client/content/news'), 'client.news');
        Blade::anonymousComponentPath(resource_path('views/components/client/content/projects'), 'client.projects');
        Blade::anonymousComponentPath(resource_path('views/components/client/content/showroom'), 'client.showroom');
        Blade::anonymousComponentPath(resource_path('views/components/client/content/customer-service'), 'client.customer-service');
        Blade::anonymousComponentPath(resource_path('views/components/client/content/shared'), 'client.shared');

        Blade::anonymousComponentPath(resource_path('views/components/client/commerce/shared'), 'client.shared');
        Blade::anonymousComponentPath(resource_path('views/components/client/commerce/customer-service'), 'client.customer-service');

        Blade::anonymousComponentPath(resource_path('views/components/client/identity/customer-service'), 'client.customer-service');

        View::composer('components.client.layouts.header', function ($view) {
            $view->with('cartCount', app(CartService::class)->getCount());
        });

        View::composer('components.admin.layouts.sidebar', function ($view): void {
            $view->with(
                'pendingConsult',
                ConsultationRequest::where('status', 'pending')->count()
            );
        });

        ContentViewComposer::boot();

        View::composer('*', function ($view): void {
            $view->with('isEcommerceEnabled', app(EcommerceStatusPort::class)->enabled());
        });

        // Keyed by the connection address only: crawler User-Agents are trivially
        // spoofed and no trusted proxy is configured, so neither earns an exemption.
        RateLimiter::for('client-pages', static fn (Request $request) => Limit::perMinute(
            max(1, (int) config('content_protection.page_rate_limit', 120))
        )->by($request->ip()));

        if ($this->app->runningInConsole()) {
            $this->commands([
                CleanStagedImagesCommand::class,
                OptimizeMediaCommand::class,
                ContentArchiveCommand::class,
                ContentWritesCommand::class,
            ]);
        }
    }
}
