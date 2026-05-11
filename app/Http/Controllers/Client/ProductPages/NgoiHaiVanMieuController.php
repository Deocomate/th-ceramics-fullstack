<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\DinhMucNgoiHaiVanMieuService;
use App\Services\GiaTriVuotTroiService;
use App\Services\MauSacNgoiHaiVanMieuCtService;
use App\Services\NgoiHaiVanMieuCtService;
use App\Services\NgoiHaiVanMieuService;
use App\Support\CollectionPaginator;
use App\Support\ProductCollectionFilter;
use Illuminate\Http\Request;

class NgoiHaiVanMieuController extends Controller
{
    public function __construct(
        private readonly NgoiHaiVanMieuService $ngoiHaiVanMieuService,
        private readonly NgoiHaiVanMieuCtService $ngoiHaiVanMieuCtService,
        private readonly MauSacNgoiHaiVanMieuCtService $mauSacService,
        private readonly DinhMucNgoiHaiVanMieuService $dinhMucService,
        private readonly GiaTriVuotTroiService $giaTriVuotTroiService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->ngoiHaiVanMieuService->getFirstRecord();
        $products = ProductCollectionFilter::apply(
            $this->ngoiHaiVanMieuCtService->getAll('active'),
            $request->only(['search', 'sort'])
        );
        $products = CollectionPaginator::paginate($products, 12);
        $giaTriVuotTroi = $this->giaTriVuotTroiService->getAll();

        return view('clients.products.ngoi-hai-van-mieu.index', compact(
            'config', 'products', 'giaTriVuotTroi'
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
