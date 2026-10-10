<?php

namespace App\Domains\Catalog\Http\Client;

use App\Domains\Catalog\Domain\ProductJourneyVideo;
use App\Domains\Catalog\Infrastructure\Models\NgoiHaiVanMieu;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Domains\Catalog\Infrastructure\Services\DinhMucNgoiHaiCoService;
use App\Domains\Catalog\Infrastructure\Services\DinhMucNgoiHaiVanMieuService;
use App\Domains\Catalog\Infrastructure\Services\NgoiHaiVanMieuService;
use App\Domains\Content\Infrastructure\Services\CoreValueService;
use App\Http\Controllers\Controller;
use App\Infrastructure\ViewHistoryService;
use Illuminate\Http\Request;

class NgoiHaiVanMieuController extends Controller
{
    public function __construct(
        private readonly NgoiHaiVanMieuService $ngoiHaiVanMieuService,
        private readonly CatalogQueryService $catalogQuery,
        private readonly DinhMucNgoiHaiVanMieuService $dinhMucService,
        private readonly DinhMucNgoiHaiCoService $dinhMucNgoiHaiCoService,
        private readonly CoreValueService $giaTriVuotTroiService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->ngoiHaiVanMieuService->getFirstRecord();
        $products = $this->catalogQuery->paginate('ngoi_hai_van_mieu_ct', $request->only(['search', 'sort']), 8);
        $giaTriVuotTroi = $this->giaTriVuotTroiService->getAll();

        return view('clients.catalog.products.ngoi-hai-van-mieu.index', compact(
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

        return view('clients.catalog.products.ngoi-hai-van-mieu.detail', compact(
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

        return view('clients.catalog.products.ngoi-hai-van-mieu.detail', compact(
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
