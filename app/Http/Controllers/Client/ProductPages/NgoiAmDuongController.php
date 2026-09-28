<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Domains\Catalog\Models\ProductDisplayOption;
use App\Domains\Catalog\Services\CatalogQueryService;
use App\Domains\Content\Services\GiaTriVuotTroiService;
use App\Http\Controllers\Controller;
use App\Models\NgoiAmDuong;
use App\Services\DinhMucNgoiAmDuongService;
use App\Services\NgoiAmDuongService;
use App\Services\ViewHistoryService;
use App\Support\ProductJourneyVideo;
use Illuminate\Http\Request;

class NgoiAmDuongController extends Controller
{
    public function __construct(
        private readonly NgoiAmDuongService $ngoiAmDuongService,
        private readonly CatalogQueryService $catalogQuery,
        private readonly DinhMucNgoiAmDuongService $dinhMucService,
        private readonly GiaTriVuotTroiService $giaTriVuotTroiService,
    ) {}

    public function index(Request $request)
    {
        $config = $this->ngoiAmDuongService->getFirstRecord();
        $products = $this->catalogQuery->paginate('ngoi_am_duong_ct', $request->only(['search', 'sort']), 8);
        $giaTriVuotTroi = $this->giaTriVuotTroiService->getAll();

        return view('clients.products.ngoi-am-duong.index', compact(
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

        return view('clients.products.ngoi-am-duong.detail', compact(
            'product',
            'colors',
            'dinhMuc',
            'relatedProducts',
            'config',
            'journeyVideo'
        ));
    }
}
