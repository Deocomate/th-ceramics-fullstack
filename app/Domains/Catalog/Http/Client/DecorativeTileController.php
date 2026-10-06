<?php

namespace App\Domains\Catalog\Http\Client;

use App\Domains\Catalog\Domain\ProductJourneyVideo;
use App\Domains\Catalog\Infrastructure\Models\DecorativeTile;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Domains\Catalog\Infrastructure\Services\DecorativeTileService;
use App\Domains\Catalog\Infrastructure\Services\UsageNormDecorativeTileService;
use App\Domains\Content\Models\DuAn;
use App\Http\Controllers\Controller;
use App\Infrastructure\ViewHistoryService;
use Illuminate\Http\Request;

class DecorativeTileController extends Controller
{
    public function __construct(
        private readonly DecorativeTileService $gachTrangTriService,
        private readonly CatalogQueryService $catalogQuery,
        private readonly UsageNormDecorativeTileService $dinhMucService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->gachTrangTriService->getFirstRecord();
        $projects = DuAn::query()->latest()->take(6)->get();
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

        $config = DecorativeTile::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.catalog.products.gach-trang-tri.detail', compact(
            'product', 'dinhMuc', 'relatedProducts', 'config', 'journeyVideo'
        ));
    }
}
