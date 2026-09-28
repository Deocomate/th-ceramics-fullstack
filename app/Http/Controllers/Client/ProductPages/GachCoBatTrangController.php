<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Domains\Catalog\Services\CatalogQueryService;
use App\Domains\Content\Services\GiaTriVuotTroiService;
use App\Http\Controllers\Controller;
use App\Models\GachCoBatTrang;
use App\Services\DinhMucGachCoBatTrangService;
use App\Services\GachCoBatTrangService;
use App\Services\ViewHistoryService;
use App\Support\ProductJourneyVideo;
use Illuminate\Http\Request;

class GachCoBatTrangController extends Controller
{
    public function __construct(
        private readonly GachCoBatTrangService $gachCoBatTrangService,
        private readonly CatalogQueryService $catalogQuery,
        private readonly DinhMucGachCoBatTrangService $dinhMucService,
        private readonly GiaTriVuotTroiService $giaTriVuotTroiService,
    ) {}

    public function index(Request $request, ViewHistoryService $historyService)
    {
        $config = $this->gachCoBatTrangService->getFirstRecord();
        $category = in_array($request->query('type'), ['bat', 'that', 'the'], true)
            ? $request->query('type') : null;
        $products = $this->catalogQuery->filtered('gach_co_bat_trang_ct', $request->query(), $category);

        $batProducts = $products->where('category_type', 'bat')->values();
        $thatXayProducts = $products->where('category_type', 'that')->values();
        $theProducts = $products->where('category_type', 'the')->values();
        $giaTriVuotTroi = $this->giaTriVuotTroiService->getAll();
        $recentProducts = $historyService->recentProducts(6);
        $recommendationProducts = $recentProducts->isNotEmpty() ? $recentProducts : $products->take(4);

        return view('clients.products.gach-co-bat-trang.index', compact(
            'config',
            'products',
            'batProducts',
            'thatXayProducts',
            'theProducts',
            'giaTriVuotTroi',
            'recommendationProducts'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->catalogQuery->findActive('gach_co_bat_trang_ct', (int) $id);
        $historyService->trackProduct('gach_co_bat_trang_ct', (int) $product->public_id);

        $dinhMuc = $this->dinhMucService->getAll();
        $relatedProducts = $this->catalogQuery->related('gach_co_bat_trang_ct', (int) $product->public_id, null, 4);

        $config = GachCoBatTrang::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.products.gach-co-bat-trang.detail', compact(
            'product', 'dinhMuc', 'relatedProducts', 'config', 'journeyVideo'
        ));
    }
}
