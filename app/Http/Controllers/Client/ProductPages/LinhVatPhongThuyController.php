<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\LinhVatPhongThuyCtService;
use App\Services\LinhVatPhongThuyService;
use App\Services\ViewHistoryService;
use App\Support\CollectionPaginator;
use App\Support\ProductCollectionFilter;
use Illuminate\Http\Request;

class LinhVatPhongThuyController extends Controller
{
    public function __construct(
        private readonly LinhVatPhongThuyService $linhVatPhongThuyService,
        private readonly LinhVatPhongThuyCtService $linhVatPhongThuyCtService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->linhVatPhongThuyService->getFirstRecord();
        $products = ProductCollectionFilter::apply(
            $this->linhVatPhongThuyCtService->getAll('active'),
            $request->only(['search', 'sort'])
        );
        $products = CollectionPaginator::paginate($products, 8);

        return view('clients.products.linh-vat-phong-thuy.index', compact(
            'config', 'products'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->linhVatPhongThuyCtService->findById($id);

        if ($product->is_delete == 1) {
            abort(404);
        }

        $historyService->trackProduct('linh_vat_phong_thuy_ct', (int) $product->linh_vat_phong_thuy_ct_id);

        $relatedProducts = $this->linhVatPhongThuyCtService->getAll('active')
            ->where('linh_vat_phong_thuy_ct_id', '!=', $id)
            ->take(4);

        return view('clients.products.linh-vat-phong-thuy.detail', compact(
            'product', 'relatedProducts'
        ));
    }
}
