<?php

namespace App\Domains\Catalog\Http\Client;

use App\Domains\Catalog\Domain\ProductJourneyVideo;
use App\Domains\Catalog\Infrastructure\Models\LinhVatPhongThuy;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Domains\Catalog\Infrastructure\Services\LinhVatPhongThuyService;
use App\Http\Controllers\Controller;
use App\Infrastructure\ViewHistoryService;
use Illuminate\Http\Request;

class LinhVatPhongThuyController extends Controller
{
    public function __construct(
        private readonly LinhVatPhongThuyService $linhVatPhongThuyService,
        private readonly CatalogQueryService $catalogQuery,
    ) {}

    public function index(Request $request)
    {
        $config = $this->linhVatPhongThuyService->getFirstRecord();
        $products = $this->catalogQuery->paginate('linh_vat_phong_thuy_ct', $request->only(['search', 'sort']), 8);

        return view('clients.catalog.products.linh-vat-phong-thuy.index', compact(
            'config', 'products'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->catalogQuery->findActive('linh_vat_phong_thuy_ct', (int) $id);
        $historyService->trackProduct('linh_vat_phong_thuy_ct', (int) $product->public_id);

        $relatedProducts = $this->catalogQuery->related('linh_vat_phong_thuy_ct', (int) $product->public_id, null, 4);

        $config = LinhVatPhongThuy::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.catalog.products.linh-vat-phong-thuy.detail', compact(
            'product', 'relatedProducts', 'config', 'journeyVideo'
        ));
    }
}
