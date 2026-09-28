<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Domains\Catalog\Services\CatalogQueryService;
use App\Domains\Content\Services\GiaTriVuotTroiService;
use App\Http\Controllers\Controller;
use App\Models\NgoiHaiVanMieu;
use App\Services\DinhMucNgoiHaiCoService;
use App\Services\DinhMucNgoiHaiVanMieuService;
use App\Services\NgoiHaiVanMieuService;
use App\Services\ViewHistoryService;
use App\Support\ProductJourneyVideo;
use Illuminate\Http\Request;

class NgoiHaiVanMieuController extends Controller
{
    public function __construct(
        private readonly NgoiHaiVanMieuService $ngoiHaiVanMieuService,
        private readonly CatalogQueryService $catalogQuery,
        private readonly DinhMucNgoiHaiVanMieuService $dinhMucService,
        private readonly DinhMucNgoiHaiCoService $dinhMucNgoiHaiCoService,
        private readonly GiaTriVuotTroiService $giaTriVuotTroiService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->ngoiHaiVanMieuService->getFirstRecord();
        $products = $this->catalogQuery->paginate('ngoi_hai_van_mieu_ct', $request->only(['search', 'sort']), 8);
        $giaTriVuotTroi = $this->giaTriVuotTroiService->getAll();

        return view('clients.products.ngoi-hai-van-mieu.index', compact(
            'config', 'products', 'giaTriVuotTroi'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $parentConfig = NgoiHaiVanMieu::query()->first();
        $product = $this->catalogQuery->findActive('ngoi_hai_van_mieu_ct', (int) $id);

        $historyService->trackProduct('ngoi_hai_van_mieu_ct', (int) $product->public_id);

        $colors = $product->variants->where('is_default', false)->where('is_delete', false);
        $dinhMuc = $this->dinhMucService->getAll();
        $relatedProducts = $this->catalogQuery->related('ngoi_hai_van_mieu_ct', (int) $product->public_id, null, 4);

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
        $product = $this->catalogQuery->findActive('ngoi_hai_co_ct', (int) $id);
        $parentConfig = NgoiHaiVanMieu::query()->first();

        $historyService->trackProduct('ngoi_hai_co_ct', (int) $product->public_id);

        $colors = $product->variants->where('is_default', false)->where('is_delete', false);
        $dinhMuc = $this->dinhMucNgoiHaiCoService->getAll();
        $relatedProducts = $this->catalogQuery->related('ngoi_hai_co_ct', (int) $product->public_id, null, 4);

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
