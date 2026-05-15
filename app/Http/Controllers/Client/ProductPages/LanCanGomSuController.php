<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\LanCanGomSuCtService;
use App\Services\LanCanGomXuService;
use App\Services\ViewHistoryService;

class LanCanGomSuController extends Controller
{
    public function __construct(
        private readonly LanCanGomXuService $lanCanGomXuService,
        private readonly LanCanGomSuCtService $lanCanGomSuCtService,
    ) {}

    public function index()
    {
        $config = $this->lanCanGomXuService->getFirstRecord();
        $products = $this->lanCanGomSuCtService->getAll('active');

        return view('clients.products.lan-can-gom-su.index', compact('config', 'products'));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->lanCanGomSuCtService->findById($id);

        if ($product->is_delete == 1) {
            abort(404, 'Sản phẩm không tồn tại hoặc đã bị gỡ.');
        }

        $historyService->trackProduct('lan_can_gom_su_ct', (int) $product->lan_can_gom_su_ct_id);

        return view('clients.products.lan-can-gom-su.detail', compact('product'));
    }
}
