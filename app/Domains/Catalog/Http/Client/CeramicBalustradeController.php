<?php

namespace App\Domains\Catalog\Http\Client;

use App\Domains\Catalog\Domain\ProductJourneyVideo;
use App\Domains\Catalog\Infrastructure\Models\CeramicBalustrade;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Domains\Catalog\Infrastructure\Services\CeramicBalustradeService;
use App\Http\Controllers\Controller;
use App\Infrastructure\ViewHistoryService;

class CeramicBalustradeController extends Controller
{
    public function __construct(
        private readonly CeramicBalustradeService $lanCanGomXuService,
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

        $config = CeramicBalustrade::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.catalog.products.lan-can-gom-su.detail', compact('product', 'config', 'journeyVideo', 'relatedProducts'));
    }
}
