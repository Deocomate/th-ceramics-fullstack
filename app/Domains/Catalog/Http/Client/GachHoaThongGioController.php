<?php

namespace App\Domains\Catalog\Http\Client;

use App\Domains\Catalog\Domain\ProductJourneyVideo;
use App\Domains\Catalog\Infrastructure\Models\GachHoaThongGio;
use App\Domains\Catalog\Infrastructure\Services\GachHoaThongGioService;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Domains\Catalog\Infrastructure\Services\DinhMucGachHoaThongGioService;
use App\Http\Controllers\Controller;
use App\Infrastructure\ViewHistoryService;
use Illuminate\Http\Request;

class GachHoaThongGioController extends Controller
{
    public function __construct(
        private readonly GachHoaThongGioService $gachHoaThongGioService,
        private readonly CatalogQueryService $catalogQuery,
        private readonly DinhMucGachHoaThongGioService $dinhMucService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->gachHoaThongGioService->getFirstRecord();
        $products = $this->catalogQuery->paginate('gach_hoa_thong_gio_ct', $request->only(['search', 'sort']), 8);

        return view('clients.catalog.products.gach-hoa-thong-gio.index', compact(
            'config', 'products'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->catalogQuery->findActive('gach_hoa_thong_gio_ct', (int) $id);
        $historyService->trackProduct('gach_hoa_thong_gio_ct', (int) $product->public_id);

        $dinhMuc = $this->dinhMucService->getAll();
        $relatedProducts = $this->catalogQuery->related('gach_hoa_thong_gio_ct', (int) $product->public_id, null, 4);

        $config = GachHoaThongGio::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.catalog.products.gach-hoa-thong-gio.detail', compact(
            'product', 'dinhMuc', 'relatedProducts', 'config', 'journeyVideo'
        ));
    }
}
