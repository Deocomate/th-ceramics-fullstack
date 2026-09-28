<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Domains\Catalog\Services\CatalogQueryService;
use App\Http\Controllers\Controller;
use App\Models\PhuKienNgoi;
use App\Services\PhuKienNgoiService;
use App\Services\UnifiedProductCatalog;
use App\Services\ViewHistoryService;
use App\Support\ProductJourneyVideo;
use Illuminate\Http\Request;

class PhuKienNgoiController extends Controller
{
    public const TYPE_BO_NOC = 'ngoi_bo_noc';
    public const TYPE_CHU_VAN = 'chu_van';

    public function __construct(
        private readonly PhuKienNgoiService $phuKienNgoiService,
        private readonly CatalogQueryService $catalogQuery,
    ) {}

    public function index()
    {
        $config = $this->phuKienNgoiService->getFirstRecord();
        $ngoiBoNocProducts = $this->catalogQuery->all('phu_kien_ngoi_ct', 'active', self::TYPE_BO_NOC);
        $boNocChuVanProducts = $this->catalogQuery->all('phu_kien_ngoi_ct', 'active', self::TYPE_CHU_VAN);

        return view('clients.products.phu-kien-ngoi.index', compact(
            'config', 'ngoiBoNocProducts', 'boNocChuVanProducts'
        ));
    }

    public function detailNgoiBoNoc($id, ViewHistoryService $historyService)
    {
        return $this->detailByType((int) $id, self::TYPE_BO_NOC, 'clients.products.phu-kien-ngoi.ngoi-bo-noc-detail', $historyService);
    }

    public function detailBoNocChuVan($id, ViewHistoryService $historyService)
    {
        return $this->detailByType((int) $id, self::TYPE_CHU_VAN, 'clients.products.phu-kien-ngoi.bo-noc-chu-van-detail', $historyService);
    }

    public function legacyDetailRedirect($id, Request $request)
    {
        $type = $request->query('type') === 'chu_van' ? self::TYPE_CHU_VAN : self::TYPE_BO_NOC;

        $product = app(UnifiedProductCatalog::class)->findByLegacyReference('phu_kien_ngoi_ct', (int) $id, $type);

        if (! $product) {
            $product = $this->catalogQuery->find('phu_kien_ngoi_ct', (int) $id);
        }

        $routeName = $type === self::TYPE_CHU_VAN
            ? 'client.products.phu-kien-ngoi.bo-noc-chu-van.detail'
            : 'client.products.phu-kien-ngoi.ngoi-bo-noc.detail';

        return redirect()->route($routeName, $product->public_id, 301);
    }

    private function detailByType(int $id, string $type, string $view, ViewHistoryService $historyService)
    {
        $product = $this->catalogQuery->findActive('phu_kien_ngoi_ct', $id);
        abort_if($product->category_type !== $type, 404);

        $historyService->trackProduct('phu_kien_ngoi_ct', (int) $product->public_id, ['accessory_type' => $type]);

        $phanLoais = $product->variants->where('is_default', false)->where('is_delete', false);
        $pageConfig = PhuKienNgoi::query()->first();
        $relatedProducts = $this->catalogQuery->related('phu_kien_ngoi_ct', (int) $product->public_id, null, 4);

        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $pageConfig);

        return view($view, compact(
            'product', 'type', 'phanLoais', 'relatedProducts', 'pageConfig', 'journeyVideo'
        ));
    }
}
