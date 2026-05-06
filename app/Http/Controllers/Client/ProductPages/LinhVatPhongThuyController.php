<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\LinhVatPhongThuyCtService;
use App\Services\LinhVatPhongThuyService;

class LinhVatPhongThuyController extends Controller
{
    public function __construct(
        private readonly LinhVatPhongThuyService $linhVatPhongThuyService,
        private readonly LinhVatPhongThuyCtService $linhVatPhongThuyCtService,
    ) {}

    public function index()
    {
        $config = $this->linhVatPhongThuyService->getFirstRecord();
        $products = $this->linhVatPhongThuyCtService->getAll('active');

        return view('clients.products.linh-vat-phong-thuy.index', compact(
            'config', 'products'
        ));
    }

    public function detail($id)
    {
        $product = $this->linhVatPhongThuyCtService->findById($id);

        if ($product->is_delete == 1) {
            abort(404);
        }

        $relatedProducts = $this->linhVatPhongThuyCtService->getAll('active')
            ->where('linh_vat_phong_thuy_ct_id', '!=', $id)
            ->take(4);

        return view('clients.products.linh-vat-phong-thuy.detail', compact(
            'product', 'relatedProducts'
        ));
    }
}
