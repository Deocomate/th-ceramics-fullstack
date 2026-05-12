<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\DinhMucGachHoaThongGioService;
use App\Services\GachHoaThongGioCtService;
use App\Services\GachHoaThongGioService;
use App\Services\ViewHistoryService;
use App\Support\CollectionPaginator;
use App\Support\ProductCollectionFilter;
use Illuminate\Http\Request;

class GachHoaThongGioController extends Controller
{
    public function __construct(
        private readonly GachHoaThongGioService $gachHoaThongGioService,
        private readonly GachHoaThongGioCtService $gachHoaThongGioCtService,
        private readonly DinhMucGachHoaThongGioService $dinhMucService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->gachHoaThongGioService->getFirstRecord();
        $products = ProductCollectionFilter::apply(
            $this->gachHoaThongGioCtService->getAll('active'),
            $request->only(['search', 'sort'])
        );
        $products = CollectionPaginator::paginate($products, 12);

        return view('clients.products.gach-hoa-thong-gio.index', compact(
            'config', 'products'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->gachHoaThongGioCtService->findById($id);

        if ($product->is_delete == 1) {
            abort(404);
        }

        $historyService->trackProduct('gach_hoa_thong_gio_ct', (int) $product->gach_hoa_thong_gio_ct_id);

        $dinhMuc = $this->dinhMucService->getAll();

        $relatedProducts = $this->gachHoaThongGioCtService->getAll('active')
            ->where('gach_hoa_thong_gio_ct_id', '!=', $id)
            ->take(4);

        return view('clients.products.gach-hoa-thong-gio.detail', compact(
            'product', 'dinhMuc', 'relatedProducts'
        ));
    }
}
