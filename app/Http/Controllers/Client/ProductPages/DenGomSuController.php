<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Domains\Catalog\Services\CatalogQueryService;
use App\Http\Controllers\Controller;
use App\Models\DenGomSu;
use App\Services\DenGomSuService;
use App\Services\ViewHistoryService;
use App\Support\ProductJourneyVideo;
use Illuminate\Http\Request;

class DenGomSuController extends Controller
{
    public function __construct(
        private readonly DenGomSuService $denGomSuService,
        private readonly CatalogQueryService $catalogQuery,
    ) {}

    public function index(Request $request, ViewHistoryService $historyService)
    {
        $config = $this->denGomSuService->getFirstRecord();
        $filters = $request->only(['search', 'sort']);
        $denGomProducts = $this->catalogQuery->paginate(
            'den_vuon_gom_su_ct',
            $filters,
            8,
            'den_gom',
            'gom_page'
        );
        $denSuProducts = $this->catalogQuery->paginate(
            'den_vuon_gom_su_ct',
            $filters,
            8,
            'den_su',
            'su_page'
        );
        $relatedProducts = $historyService->recentProducts(6);

        return view('clients.products.den-gom-su.index', compact(
            'config',
            'denGomProducts',
            'denSuProducts',
            'relatedProducts'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->catalogQuery->findActive('den_vuon_gom_su_ct', (int) $id);
        $historyService->trackProduct('den_vuon_gom_su_ct', (int) $product->public_id);
        $relatedProducts = $this->catalogQuery->related(
            'den_vuon_gom_su_ct',
            (int) $product->public_id,
            $product->category_type,
            4
        );
        $config = DenGomSu::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.products.den-gom-su.detail', compact('product', 'relatedProducts', 'config', 'journeyVideo'));
    }
}
