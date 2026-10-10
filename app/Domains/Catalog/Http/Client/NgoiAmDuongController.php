<?php

namespace App\Domains\Catalog\Http\Client;

use App\Domains\Catalog\Domain\ProductJourneyVideo;
use App\Domains\Catalog\Infrastructure\Models\ProductDisplayOption;
use App\Domains\Catalog\Infrastructure\Models\NgoiAmDuong;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Domains\Catalog\Infrastructure\Services\DinhMucNgoiAmDuongService;
use App\Domains\Catalog\Infrastructure\Services\NgoiAmDuongService;
use App\Domains\Content\Infrastructure\Services\CoreValueService;
use App\Http\Controllers\Controller;
use App\Infrastructure\ViewHistoryService;
use Illuminate\Http\Request;

class NgoiAmDuongController extends Controller
{
    public function __construct(
        private readonly NgoiAmDuongService $ngoiAmDuongService,
        private readonly CatalogQueryService $catalogQuery,
        private readonly DinhMucNgoiAmDuongService $dinhMucService,
        private readonly CoreValueService $giaTriVuotTroiService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->ngoiAmDuongService->getFirstRecord();
        $products = $this->catalogQuery->paginate('ngoi_am_duong_ct', $request->only(['search', 'sort']), 8);
        $giaTriVuotTroi = $this->giaTriVuotTroiService->getAll();

        return view('clients.catalog.products.ngoi-am-duong.index', compact(
            'config',
            'products',
            'giaTriVuotTroi'
        ));
    }

    public function detail($id, ViewHistoryService $historyService)
    {
        $product = $this->catalogQuery->findActive('ngoi_am_duong_ct', (int) $id);
        $historyService->trackProduct('ngoi_am_duong_ct', (int) $product->public_id);

        $colors = ProductDisplayOption::query()
            ->where('type_key', 'ngoi_am_duong_ct')
            ->orderBy('sort_order')
            ->get();

        $dinhMuc = $this->dinhMucService->getAll();
        $relatedProducts = $this->catalogQuery->related('ngoi_am_duong_ct', (int) $product->public_id, null, 4);

        $config = NgoiAmDuong::query()->first();
        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $config);

        return view('clients.catalog.products.ngoi-am-duong.detail', compact(
            'product',
            'colors',
            'dinhMuc',
            'relatedProducts',
            'config',
            'journeyVideo'
        ));
    }
}
