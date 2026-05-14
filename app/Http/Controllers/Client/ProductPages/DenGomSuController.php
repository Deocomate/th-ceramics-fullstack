<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Models\DenVuonGomSuCt;
use App\Services\DenGomSuService;
use App\Services\DenVuonGomSuCtService;
use App\Services\ViewHistoryService;
use Illuminate\Http\Request;

class DenGomSuController extends Controller
{
    public function __construct(
        private readonly DenGomSuService $denGomSuService,
        private readonly DenVuonGomSuCtService $denVuonGomSuCtService,
    ) {}

    public function index(Request $request, ViewHistoryService $historyService)
    {
        $config = $this->denGomSuService->getFirstRecord();
        $filters = $request->only(['search', 'sort']);
        $denGomProducts = $this->denVuonGomSuCtService->paginatedForClient(
            DenVuonGomSuCt::CATEGORY_DEN_GOM,
            $filters,
            'gom_page'
        );
        $denSuProducts = $this->denVuonGomSuCtService->paginatedForClient(
            DenVuonGomSuCt::CATEGORY_DEN_SU,
            $filters,
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
        $product = $this->denVuonGomSuCtService->findActiveForClient((int) $id);
        $historyService->trackProduct('den_vuon_gom_su_ct', (int) $product->den_vuon_gom_su_ct_id);
        $relatedProducts = $this->denVuonGomSuCtService->relatedForClient(
            (int) $product->den_vuon_gom_su_ct_id,
            $product->category_type,
            4
        );

        return view('clients.products.den-gom-su.detail', compact('product', 'relatedProducts'));
    }
}
