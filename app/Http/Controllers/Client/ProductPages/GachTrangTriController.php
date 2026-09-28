<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Domains\Catalog\Services\CatalogQueryService;
use App\Domains\Content\Models\DuAn;
use App\Http\Controllers\Controller;
use App\Models\GachTrangTri;
use App\Services\DinhMucGachTrangTriService;
use App\Services\GachTrangTriService;
use App\Services\ViewHistoryService;
use App\Support\ProductJourneyVideo;
use Illuminate\Http\Request;

class GachTrangTriController extends Controller
{
    public function __construct(
        private readonly GachTrangTriService $gachTrangTriService,
        private readonly CatalogQueryService $catalogQuery,
        private readonly DinhMucGachTrangTriService $dinhMucService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->gachTrangTriService->getFirstRecord();
        $projects = DuAn::query()->latest()->take(6)->get();
        $products = $this->catalogQuery->paginate('gach_trang_tri_ct', $request->only(['search', 'sort']), 8);

        return view('clients.products.gach-trang-tri.index', compact(
            'config', 'products', 'projects'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->catalogQuery->findActive('gach_trang_tri_ct', (int) $id);
        $historyService->trackProduct('gach_trang_tri_ct', (int) $product->public_id);

        $dinhMuc = $this->dinhMucService->getAll();
        $relatedProducts = $this->catalogQuery->related('gach_trang_tri_ct', (int) $product->public_id, null, 4);

        $config = GachTrangTri::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.products.gach-trang-tri.detail', compact(
            'product', 'dinhMuc', 'relatedProducts', 'config', 'journeyVideo'
        ));
    }
}
