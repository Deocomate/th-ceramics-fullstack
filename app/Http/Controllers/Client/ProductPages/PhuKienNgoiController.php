<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\BoNocChuVanCtService;
use App\Services\NgoiBoNocCtService;
use App\Services\PhuKienNgoiService;
use App\Services\ViewHistoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PhuKienNgoiController extends Controller
{
    public function __construct(
        private readonly PhuKienNgoiService $phuKienNgoiService,
        private readonly NgoiBoNocCtService $ngoiBoNocCtService,
        private readonly BoNocChuVanCtService $boNocChuVanCtService,
    ) {}

    public function index()
    {
        $config = $this->phuKienNgoiService->getFirstRecord();
        $ngoiBoNocProducts = $this->ngoiBoNocCtService->getAll('active');
        $boNocChuVanProducts = $this->boNocChuVanCtService->getAll('active');

        return view('clients.products.phu-kien-ngoi.index', compact(
            'config', 'ngoiBoNocProducts', 'boNocChuVanProducts'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        try {
            $product = $this->ngoiBoNocCtService->findById($id);
            $type = 'bo_noc';
        } catch (ModelNotFoundException $e) {
            try {
                $product = $this->boNocChuVanCtService->findById($id);
                $type = 'chu_van';
            } catch (ModelNotFoundException $e2) {
                abort(404);
            }
        }

        if ($product->is_delete == 1) {
            abort(404);
        }

        $productId = $product->ngoi_bo_noc_ct_id ?? $product->bo_noc_chu_van_ct_id ?? (int) $id;
        $historyService->trackProduct('phu_kien_ngoi', (int) $productId);

        $phanLoais = $product->phanLoais;

        $relatedBoNoc = $this->ngoiBoNocCtService->getAll('active')
            ->where('ngoi_bo_noc_ct_id', '!=', $id)
            ->take(2);

        $relatedChuVan = $this->boNocChuVanCtService->getAll('active')
            ->where('bo_noc_chu_van_ct_id', '!=', $id)
            ->take(2);

        $relatedProducts = $relatedBoNoc->concat($relatedChuVan);

        return view('clients.products.phu-kien-ngoi.detail', compact(
            'product', 'type', 'phanLoais', 'relatedProducts'
        ));
    }
}
