<?php

namespace App\Domains\Catalog\Http\Client;

use App\Domains\Catalog\Domain\ProductJourneyVideo;
use App\Domains\Catalog\Infrastructure\Models\GachTrangTri;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Domains\Catalog\Infrastructure\Services\DinhMucGachTrangTriService;
use App\Domains\Catalog\Infrastructure\Services\GachTrangTriService;
use App\Domains\Content\Infrastructure\Models\Project;
use App\Http\Controllers\Controller;
use App\Infrastructure\ViewHistoryService;
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
        $projects = Project::query()->latest()->take(6)->get();
        $products = $this->catalogQuery->paginate('gach_trang_tri_ct', $request->only(['search', 'sort']), 8);

        return view('clients.catalog.products.gach-trang-tri.index', compact(
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

        return view('clients.catalog.products.gach-trang-tri.detail', compact(
            'product', 'dinhMuc', 'relatedProducts', 'config', 'journeyVideo'
        ));
    }
}
