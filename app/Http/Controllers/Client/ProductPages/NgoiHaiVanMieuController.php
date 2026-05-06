<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\DinhMucNgoiHaiVanMieuService;
use App\Services\MauSacNgoiHaiVanMieuCtService;
use App\Services\NgoiHaiVanMieuCtService;
use App\Services\NgoiHaiVanMieuService;

class NgoiHaiVanMieuController extends Controller
{
    public function __construct(
        private readonly NgoiHaiVanMieuService $ngoiHaiVanMieuService,
        private readonly NgoiHaiVanMieuCtService $ngoiHaiVanMieuCtService,
        private readonly MauSacNgoiHaiVanMieuCtService $mauSacService,
        private readonly DinhMucNgoiHaiVanMieuService $dinhMucService,
    ) {}

    public function index()
    {
        $config = $this->ngoiHaiVanMieuService->getFirstRecord();
        $products = $this->ngoiHaiVanMieuCtService->getAll('active');

        return view('clients.products.ngoi-hai-van-mieu.index', compact(
            'config', 'products'
        ));
    }

    public function detail($id)
    {
        $product = $this->ngoiHaiVanMieuCtService->findById($id);

        if ($product->is_delete == 1) {
            abort(404);
        }

        $colors = $product->mauSacs;

        $dinhMuc = $this->dinhMucService->getAll();

        $relatedProducts = $this->ngoiHaiVanMieuCtService->getAll('active')
            ->where('ngoi_hai_van_mieu_ct_id', '!=', $id)
            ->take(4);

        return view('clients.products.ngoi-hai-van-mieu.detail', compact(
            'product', 'colors', 'dinhMuc', 'relatedProducts'
        ));
    }
}
