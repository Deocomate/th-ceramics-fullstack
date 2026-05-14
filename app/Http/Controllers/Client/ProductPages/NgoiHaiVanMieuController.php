<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\DinhMucNgoiHaiCoService;
use App\Services\DinhMucNgoiHaiVanMieuService;
use App\Services\GiaTriVuotTroiService;
use App\Services\MauSacNgoiHaiVanMieuCtService;
use App\Services\NgoiHaiCoCtService;
use App\Services\NgoiHaiVanMieuCtService;
use App\Services\NgoiHaiVanMieuService;
use App\Services\ViewHistoryService;
use App\Support\CollectionPaginator;
use App\Support\ProductCollectionFilter;
use Illuminate\Http\Request;

class NgoiHaiVanMieuController extends Controller
{
    public function __construct(
        private readonly NgoiHaiVanMieuService $ngoiHaiVanMieuService,
        private readonly NgoiHaiVanMieuCtService $ngoiHaiVanMieuCtService,
        private readonly NgoiHaiCoCtService $ngoiHaiCoCtService,
        private readonly MauSacNgoiHaiVanMieuCtService $mauSacService,
        private readonly DinhMucNgoiHaiVanMieuService $dinhMucService,
        private readonly DinhMucNgoiHaiCoService $dinhMucNgoiHaiCoService,
        private readonly GiaTriVuotTroiService $giaTriVuotTroiService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->ngoiHaiVanMieuService->getFirstRecord();
        $products = ProductCollectionFilter::apply(
            $this->ngoiHaiVanMieuCtService->getAll('active'),
            $request->only(['search', 'sort'])
        );
        $products = CollectionPaginator::paginate($products, 8);
        $giaTriVuotTroi = $this->giaTriVuotTroiService->getAll();

        return view('clients.products.ngoi-hai-van-mieu.index', compact(
            'config', 'products', 'giaTriVuotTroi'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->ngoiHaiVanMieuCtService->findById($id);
        $parentConfig = $this->ngoiHaiVanMieuService->getFirstRecord();

        if ($product->is_delete == 1) {
            abort(404);
        }

        $historyService->trackProduct('ngoi_hai_van_mieu_ct', (int) $product->ngoi_hai_van_mieu_ct_id);

        $colors = $product->mauSacs()->where('is_delete', 0)->get();

        $dinhMuc = $this->dinhMucService->getAll();

        $relatedProducts = $this->ngoiHaiVanMieuCtService->getAll('active')
            ->where('ngoi_hai_van_mieu_ct_id', '!=', $id)
            ->take(4);

        $pageLabel = 'Ngói Hài Văn Miếu';
        $indexRouteName = 'client.products.ngoi-hai-van-mieu.index';
        $detailRouteName = 'client.products.ngoi-hai-van-mieu.detail';
        $productType = 'ngoi_hai_van_mieu_ct';
        $productPkField = 'ngoi_hai_van_mieu_ct_id';
        $variantPkField = 'mau_sac_ngoi_hai_van_mieu_ct_id';

        return view('clients.products.ngoi-hai-van-mieu.detail', compact(
            'product',
            'colors',
            'dinhMuc',
            'relatedProducts',
            'parentConfig',
            'pageLabel',
            'indexRouteName',
            'detailRouteName',
            'productType',
            'productPkField',
            'variantPkField'
        ));
    }

    public function detailNgoiHaiCo($id, ViewHistoryService $historyService)
    {
        $product = $this->ngoiHaiCoCtService->findById($id);
        $parentConfig = $this->ngoiHaiVanMieuService->getFirstRecord();

        if ($product->is_delete == 1) {
            abort(404);
        }

        $historyService->trackProduct('ngoi_hai_co_ct', (int) $product->ngoi_hai_co_ct_id);

        $colors = $product->mauSacs()->where('is_delete', 0)->get();
        $dinhMuc = $this->dinhMucNgoiHaiCoService->getAll();
        $relatedProducts = $this->ngoiHaiCoCtService->getAll('active')
            ->where('ngoi_hai_co_ct_id', '!=', $id)
            ->take(4);

        $pageLabel = 'Ngói Hài Cổ';
        $indexRouteName = 'client.products.ngoi-hai-van-mieu.index';
        $detailRouteName = 'client.products.ngoi-hai-co.detail';
        $productType = 'ngoi_hai_co_ct';
        $productPkField = 'ngoi_hai_co_ct_id';
        $variantPkField = 'mau_sac_ngoi_hai_co_ct_id';

        return view('clients.products.ngoi-hai-van-mieu.detail', compact(
            'product',
            'colors',
            'dinhMuc',
            'relatedProducts',
            'parentConfig',
            'pageLabel',
            'indexRouteName',
            'detailRouteName',
            'productType',
            'productPkField',
            'variantPkField'
        ));
    }
}
