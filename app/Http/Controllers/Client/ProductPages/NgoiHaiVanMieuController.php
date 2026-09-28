<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Models\NgoiHaiVanMieu;
use App\Services\DinhMucNgoiHaiCoService;
use App\Services\DinhMucNgoiHaiVanMieuService;
use App\Services\GiaTriVuotTroiService;
use App\Services\MauSacNgoiHaiVanMieuCtService;
use App\Services\NgoiHaiCoCtService;
use App\Services\NgoiHaiVanMieuCtService;
use App\Services\NgoiHaiVanMieuService;
use App\Services\UnifiedProductCatalog;
use App\Services\ViewHistoryService;
use App\Support\CollectionPaginator;
use App\Support\ProductCollectionFilter;
use App\Support\ProductJourneyVideo;
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
        $products = config('product_catalog.read_unified')
            ? app(UnifiedProductCatalog::class)->paginate('ngoi_hai_van_mieu_ct', $request->only(['search', 'sort']))
            : CollectionPaginator::paginate(ProductCollectionFilter::apply(
                $this->ngoiHaiVanMieuCtService->getAll('active'), $request->only(['search', 'sort'])
            ), 8);
        $giaTriVuotTroi = $this->giaTriVuotTroiService->getAll();

        return view('clients.products.ngoi-hai-van-mieu.index', compact(
            'config', 'products', 'giaTriVuotTroi'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $parentConfig = NgoiHaiVanMieu::query()->first();
        $product = $this->ngoiHaiVanMieuCtService->findById($id);

        if ($product->is_delete == 1) {
            abort(404);
        }

        $historyService->trackProduct('ngoi_hai_van_mieu_ct', (int) $product->ngoi_hai_van_mieu_ct_id);

        $colors = config('product_catalog.read_unified')
            ? $product->mauSacs->where('is_delete', 0)
            : $product->mauSacs()->where('is_delete', 0)->get();

        $dinhMuc = $this->dinhMucService->getAll();

        $relatedProducts = config('product_catalog.read_unified')
            ? app(UnifiedProductCatalog::class)->related('ngoi_hai_van_mieu_ct', (int) $id, null, 4)
            : $this->ngoiHaiVanMieuCtService->getAll('active')->where('ngoi_hai_van_mieu_ct_id', '!=', $id)->take(4);

        $pageLabel = 'Ngói Hài Văn Miếu';
        $indexRouteName = 'client.products.ngoi-hai-van-mieu.index';
        $detailRouteName = 'client.products.ngoi-hai-van-mieu.detail';
        $productType = 'ngoi_hai_van_mieu_ct';
        $productPkField = 'ngoi_hai_van_mieu_ct_id';
        $variantPkField = 'mau_sac_ngoi_hai_van_mieu_ct_id';
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $parentConfig);

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
            'variantPkField',
            'journeyVideo'
        ));
    }

    public function detailNgoiHaiCo($id, ViewHistoryService $historyService)
    {
        $product = $this->ngoiHaiCoCtService->findById($id);
        $parentConfig = NgoiHaiVanMieu::query()->first();

        if ($product->is_delete == 1) {
            abort(404);
        }

        $historyService->trackProduct('ngoi_hai_co_ct', (int) $product->ngoi_hai_co_ct_id);

        $colors = config('product_catalog.read_unified')
            ? $product->mauSacs->where('is_delete', 0)
            : $product->mauSacs()->where('is_delete', 0)->get();
        $dinhMuc = $this->dinhMucNgoiHaiCoService->getAll();
        $relatedProducts = config('product_catalog.read_unified')
            ? app(UnifiedProductCatalog::class)->related('ngoi_hai_co_ct', (int) $id, null, 4)
            : $this->ngoiHaiCoCtService->getAll('active')->where('ngoi_hai_co_ct_id', '!=', $id)->take(4);

        $pageLabel = 'Ngói Hài Cổ';
        $indexRouteName = 'client.products.ngoi-hai-van-mieu.index';
        $detailRouteName = 'client.products.ngoi-hai-co.detail';
        $productType = 'ngoi_hai_co_ct';
        $productPkField = 'ngoi_hai_co_ct_id';
        $variantPkField = 'mau_sac_ngoi_hai_co_ct_id';
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $parentConfig);

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
            'variantPkField',
            'journeyVideo'
        ));
    }
}
