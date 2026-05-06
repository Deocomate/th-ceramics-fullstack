<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\DinhMucGachTrangTriService;
use App\Services\GachTrangTriCtService;
use App\Services\GachTrangTriService;

class GachTrangTriController extends Controller
{
    public function __construct(
        private readonly GachTrangTriService $gachTrangTriService,
        private readonly GachTrangTriCtService $gachTrangTriCtService,
        private readonly DinhMucGachTrangTriService $dinhMucService,
    ) {}

    public function index()
    {
        $config = $this->gachTrangTriService->getFirstRecord();
        $products = $this->gachTrangTriCtService->getAll('active');

        return view('clients.products.gach-trang-tri.index', compact(
            'config', 'products'
        ));
    }

    public function detail($id)
    {
        $product = $this->gachTrangTriCtService->findById($id);

        if ($product->is_delete == 1) {
            abort(404);
        }

        $dinhMuc = $this->dinhMucService->getAll();

        $relatedProducts = $this->gachTrangTriCtService->getAll('active')
            ->where('gach_trang_tri_ct_id', '!=', $id)
            ->take(4);

        return view('clients.products.gach-trang-tri.detail', compact(
            'product', 'dinhMuc', 'relatedProducts'
        ));
    }
}
