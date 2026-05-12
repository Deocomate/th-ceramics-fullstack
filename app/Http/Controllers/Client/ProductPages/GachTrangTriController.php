<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\DinhMucGachTrangTriService;
use App\Services\GachTrangTriCtService;
use App\Services\GachTrangTriService;
use App\Services\ViewHistoryService;
use App\Support\CollectionPaginator;
use App\Support\ProductCollectionFilter;
use Illuminate\Http\Request;

class GachTrangTriController extends Controller
{
    public function __construct(
        private readonly GachTrangTriService $gachTrangTriService,
        private readonly GachTrangTriCtService $gachTrangTriCtService,
        private readonly DinhMucGachTrangTriService $dinhMucService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->gachTrangTriService->getFirstRecord();
        $products = ProductCollectionFilter::apply(
            $this->gachTrangTriCtService->getAll('active'),
            $request->only(['search', 'sort'])
        );
        $products = CollectionPaginator::paginate($products, 12);

        return view('clients.products.gach-trang-tri.index', compact(
            'config', 'products'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->gachTrangTriCtService->findById($id);

        if ($product->is_delete == 1) {
            abort(404);
        }

        $historyService->trackProduct('gach_trang_tri_ct', (int) $product->gach_trang_tri_ct_id);

        $dinhMuc = $this->dinhMucService->getAll();

        $relatedProducts = $this->gachTrangTriCtService->getAll('active')
            ->where('gach_trang_tri_ct_id', '!=', $id)
            ->take(4);

        return view('clients.products.gach-trang-tri.detail', compact(
            'product', 'dinhMuc', 'relatedProducts'
        ));
    }
}
