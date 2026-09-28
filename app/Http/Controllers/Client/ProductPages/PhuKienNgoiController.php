<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Models\PhuKienNgoi;
use App\Models\PhuKienNgoiCt;
use App\Services\PhuKienNgoiCtService;
use App\Services\PhuKienNgoiService;
use App\Services\UnifiedProductCatalog;
use App\Services\ViewHistoryService;
use Illuminate\Http\Request;

class PhuKienNgoiController extends Controller
{
    public function __construct(
        private readonly PhuKienNgoiService $phuKienNgoiService,
        private readonly PhuKienNgoiCtService $phuKienNgoiCtService,
    ) {}

    public function index()
    {
        $config = $this->phuKienNgoiService->getFirstRecord();
        $ngoiBoNocProducts = $this->phuKienNgoiCtService->getAll('active', PhuKienNgoiCt::TYPE_BO_NOC);
        $boNocChuVanProducts = $this->phuKienNgoiCtService->getAll('active', PhuKienNgoiCt::TYPE_CHU_VAN);

        return view('clients.products.phu-kien-ngoi.index', compact(
            'config', 'ngoiBoNocProducts', 'boNocChuVanProducts'
        ));
    }

    public function detailNgoiBoNoc($id, ViewHistoryService $historyService)
    {
        return $this->detailByType((int) $id, PhuKienNgoiCt::TYPE_BO_NOC, 'clients.products.phu-kien-ngoi.ngoi-bo-noc-detail', $historyService);
    }

    public function detailBoNocChuVan($id, ViewHistoryService $historyService)
    {
        return $this->detailByType((int) $id, PhuKienNgoiCt::TYPE_CHU_VAN, 'clients.products.phu-kien-ngoi.bo-noc-chu-van-detail', $historyService);
    }

    public function legacyDetailRedirect($id, Request $request)
    {
        $type = $request->query('type') === 'chu_van' ? PhuKienNgoiCt::TYPE_CHU_VAN : PhuKienNgoiCt::TYPE_BO_NOC;

        $product = config('product_catalog.read_unified')
            ? app(UnifiedProductCatalog::class)->all('phu_kien_ngoi_ct', 'all', $type)
                ->first(fn ($item) => (int) $item->legacy_id === (int) $id || (int) $item->phu_kien_ngoi_ct_id === (int) $id)
            : PhuKienNgoiCt::query()
            ->where('category_type', $type)
            ->where('legacy_type', $type)
            ->where('legacy_id', $id)
            ->first();

        if (! $product) {
            $product = PhuKienNgoiCt::query()
                ->where('category_type', $type)
                ->where('phu_kien_ngoi_ct_id', $id)
                ->firstOrFail();
        }

        $routeName = $type === PhuKienNgoiCt::TYPE_CHU_VAN
            ? 'client.products.phu-kien-ngoi.bo-noc-chu-van.detail'
            : 'client.products.phu-kien-ngoi.ngoi-bo-noc.detail';

        return redirect()->route($routeName, $product->phu_kien_ngoi_ct_id, 301);
    }

    private function detailByType(int $id, string $type, string $view, ViewHistoryService $historyService)
    {
        $product = config('product_catalog.read_unified')
            ? $this->phuKienNgoiCtService->findById($id)
            : PhuKienNgoiCt::query()
            ->with(['phanLoais' => fn ($query) => $query->where('is_delete', 0)->orderBy('price')])
            ->where('category_type', $type)
            ->where('is_delete', 0)
            ->findOrFail($id);
        abort_if($product->category_type !== $type || $product->is_delete, 404);

        $historyService->trackProduct('phu_kien_ngoi_ct', (int) $product->phu_kien_ngoi_ct_id, ['accessory_type' => $type]);

        $phanLoais = $product->phanLoais;
        $pageConfig = PhuKienNgoi::query()->first();
        $relatedProducts = config('product_catalog.read_unified')
            ? app(UnifiedProductCatalog::class)->related('phu_kien_ngoi_ct', $product->phu_kien_ngoi_ct_id, null, 4)
            : PhuKienNgoiCt::query()
            ->where('is_delete', 0)
            ->where('phu_kien_ngoi_ct_id', '!=', $product->phu_kien_ngoi_ct_id)
            ->orderedByPriority()
            ->take(4)
            ->get();

        $journeyVideo = \App\Support\ProductJourneyVideo::resolve($product->video ?? null, $pageConfig);

        return view($view, compact(
            'product', 'type', 'phanLoais', 'relatedProducts', 'pageConfig', 'journeyVideo'
        ));
    }
}
