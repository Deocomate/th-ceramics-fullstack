<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\DinhMucGachCoBatTrangService;
use App\Services\GachCoBatTrangCtService;
use App\Services\GachCoBatTrangService;
use App\Services\GiaTriVuotTroiService;
use App\Services\ViewHistoryService;

class GachCoBatTrangController extends Controller
{
    public function __construct(
        private readonly GachCoBatTrangService $gachCoBatTrangService,
        private readonly GachCoBatTrangCtService $gachCoBatTrangCtService,
        private readonly DinhMucGachCoBatTrangService $dinhMucService,
        private readonly GiaTriVuotTroiService $giaTriVuotTroiService,
    ) {}

    public function index()
    {
        $config = $this->gachCoBatTrangService->getFirstRecord();
        $products = $this->gachCoBatTrangCtService->getAll('active');
        $giaTriVuotTroi = $this->giaTriVuotTroiService->getAll();

        return view('clients.products.gach-co-bat-trang.index', compact(
            'config', 'products', 'giaTriVuotTroi'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->gachCoBatTrangCtService->findById($id);

        if ($product->is_delete == 1) {
            abort(404);
        }

        $historyService->trackProduct('gach_co_bat_trang_ct', (int) $product->gach_co_bat_trang_ct_id);

        $dinhMuc = $this->dinhMucService->getAll();

        $relatedProducts = $this->gachCoBatTrangCtService->getAll('active')
            ->where('gach_co_bat_trang_ct_id', '!=', $id)
            ->take(4);

        return view('clients.products.gach-co-bat-trang.detail', compact(
            'product', 'dinhMuc', 'relatedProducts'
        ));
    }
}
