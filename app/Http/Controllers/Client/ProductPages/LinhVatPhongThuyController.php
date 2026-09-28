<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Models\LinhVatPhongThuy;
use App\Services\LinhVatPhongThuyCtService;
use App\Services\LinhVatPhongThuyService;
use App\Services\UnifiedProductCatalog;
use App\Services\ViewHistoryService;
use App\Support\CollectionPaginator;
use App\Support\ProductCollectionFilter;
use App\Support\ProductJourneyVideo;
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
        $products = config('product_catalog.read_unified')
            ? app(UnifiedProductCatalog::class)->paginate('linh_vat_phong_thuy_ct', $request->only(['search', 'sort']))
            : CollectionPaginator::paginate(ProductCollectionFilter::apply(
                $this->linhVatPhongThuyCtService->getAll('active'), $request->only(['search', 'sort'])
            ), 8);

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

        $relatedProducts = config('product_catalog.read_unified')
            ? app(UnifiedProductCatalog::class)->related('linh_vat_phong_thuy_ct', (int) $id, null, 4)
            : $this->linhVatPhongThuyCtService->getAll('active')->where('linh_vat_phong_thuy_ct_id', '!=', $id)->take(4);

        $config = LinhVatPhongThuy::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.products.linh-vat-phong-thuy.detail', compact(
            'product', 'relatedProducts', 'config', 'journeyVideo'
        ));
    }
}
