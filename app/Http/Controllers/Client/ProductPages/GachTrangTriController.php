<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Domains\Content\Models\DuAn;
use App\Models\GachTrangTri;
use App\Services\DinhMucGachTrangTriService;
use App\Services\GachTrangTriCtService;
use App\Services\GachTrangTriService;
use App\Services\UnifiedProductCatalog;
use App\Services\ViewHistoryService;
use App\Support\CollectionPaginator;
use App\Support\ProductCollectionFilter;
use App\Support\ProductJourneyVideo;
use Illuminate\Http\Request;

class GachTrangTriController extends Controller
{
    public function __construct(
        private readonly GachTrangTriService $gachTrangTriService,
        private readonly GachTrangTriCtService $gachTrangTriCtService,
        private readonly DinhMucGachTrangTriService $dinhMucService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->gachTrangTriService->getFirstRecord();
        $projects = DuAn::query()->latest()->take(6)->get();
        $products = config('product_catalog.read_unified')
            ? app(UnifiedProductCatalog::class)->paginate('gach_trang_tri_ct', $request->only(['search', 'sort']))
            : CollectionPaginator::paginate(ProductCollectionFilter::apply(
                $this->gachTrangTriCtService->getAll('active'), $request->only(['search', 'sort'])
            ), 8);

        return view('clients.products.gach-trang-tri.index', compact(
            'config', 'products', 'projects'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->gachTrangTriCtService->findById($id);

        if ($product->is_delete == 1) {
            abort(404);
        }

        $historyService->trackProduct('gach_trang_tri_ct', (int) $product->gach_trang_tri_ct_id);

        $dinhMuc = $this->dinhMucService->getAll();

        $relatedProducts = config('product_catalog.read_unified')
            ? app(UnifiedProductCatalog::class)->related('gach_trang_tri_ct', (int) $id, null, 4)
            : $this->gachTrangTriCtService->getAll('active')->where('gach_trang_tri_ct_id', '!=', $id)->take(4);

        $config = GachTrangTri::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.products.gach-trang-tri.detail', compact(
            'product', 'dinhMuc', 'relatedProducts', 'config', 'journeyVideo'
        ));
    }
}
