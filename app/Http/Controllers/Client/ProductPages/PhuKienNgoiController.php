<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\BoNocChuVanCtService;
use App\Services\NgoiBoNocCtService;
use App\Services\PhuKienNgoiService;
use App\Services\ViewHistoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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

    public function detail($id, Request $request, ViewHistoryService $historyService)
    {
        $requestedType = $request->query('type');

        try {
            if ($requestedType === 'bo_noc') {
                $product = $this->ngoiBoNocCtService->findById($id);
                $type = 'bo_noc';
            } elseif ($requestedType === 'chu_van') {
                $product = $this->boNocChuVanCtService->findById($id);
                $type = 'chu_van';
            } else {
                $product = $this->ngoiBoNocCtService->findById($id);
                $type = 'bo_noc';
            }
        } catch (ModelNotFoundException $e) {
            if ($requestedType === 'bo_noc' || $requestedType === 'chu_van') {
                abort(404);
            }

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
        $historyService->trackProduct('phu_kien_ngoi', (int) $productId, ['accessory_type' => $type]);

        $phanLoais = $product->phanLoais;

        $relatedBoNoc = $this->ngoiBoNocCtService->getAll('active')
            ->where('ngoi_bo_noc_ct_id', '!=', $type === 'bo_noc' ? $id : 0)
            ->take(2);

        $relatedChuVan = $this->boNocChuVanCtService->getAll('active')
            ->where('bo_noc_chu_van_ct_id', '!=', $type === 'chu_van' ? $id : 0)
            ->take(2);

        $relatedProducts = $relatedBoNoc->concat($relatedChuVan);

        return view('clients.products.phu-kien-ngoi.detail', compact(
            'product', 'type', 'phanLoais', 'relatedProducts'
        ));
    }
}
