<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Domains\Catalog\Services\CatalogQueryService;
use App\Http\Controllers\Controller;
use App\Models\LanCanGomXu;
use App\Services\LanCanGomXuService;
use App\Services\ViewHistoryService;
use App\Support\ProductJourneyVideo;

class LanCanGomSuController extends Controller
{
    public function __construct(
        private readonly LanCanGomXuService $lanCanGomXuService,
        private readonly CatalogQueryService $catalogQuery,
    ) {}

    public function index()
    {
        $config = $this->lanCanGomXuService->getFirstRecord();
        $products = $this->catalogQuery->all('lan_can_gom_su_ct', 'active');

        return view('clients.products.lan-can-gom-su.index', compact('config', 'products'));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->catalogQuery->findActive('lan_can_gom_su_ct', (int) $id);
        $historyService->trackProduct('lan_can_gom_su_ct', (int) $product->public_id);
        $relatedProducts = $this->catalogQuery->related('lan_can_gom_su_ct', (int) $product->public_id, null, 6);

        $config = LanCanGomXu::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.products.lan-can-gom-su.detail', compact('product', 'config', 'journeyVideo', 'relatedProducts'));
    }
}
