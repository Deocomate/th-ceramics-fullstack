<?php

namespace App\Domains\Catalog\Http\Client;

use App\Domains\Catalog\Domain\ProductJourneyVideo;
use App\Domains\Catalog\Infrastructure\Models\LanCanGomSu;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Domains\Catalog\Infrastructure\Services\LanCanGomSuService;
use App\Http\Controllers\Controller;
use App\Infrastructure\ViewHistoryService;

class LanCanGomSuController extends Controller
{
    public function __construct(
        private readonly LanCanGomSuService $lanCanGomXuService,
        private readonly CatalogQueryService $catalogQuery,
    ) {}

    public function index()
    {
        $config = $this->lanCanGomXuService->getFirstRecord();
        $products = $this->catalogQuery->all('lan_can_gom_su_ct', 'active');

        return view('clients.catalog.products.lan-can-gom-su.index', compact('config', 'products'));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->catalogQuery->findActive('lan_can_gom_su_ct', (int) $id);
        $historyService->trackProduct('lan_can_gom_su_ct', (int) $product->public_id);
        $relatedProducts = $this->catalogQuery->related('lan_can_gom_su_ct', (int) $product->public_id, null, 6);

        $config = LanCanGomSu::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.catalog.products.lan-can-gom-su.detail', compact('product', 'config', 'journeyVideo', 'relatedProducts'));
    }
}
