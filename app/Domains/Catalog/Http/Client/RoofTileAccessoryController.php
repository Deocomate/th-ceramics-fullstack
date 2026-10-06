<?php

namespace App\Domains\Catalog\Http\Client;

use App\Domains\Catalog\Domain\ProductJourneyVideo;
use App\Domains\Catalog\Domain\RoofTileAccessoryCategory;
use App\Domains\Catalog\Infrastructure\Models\RoofTileAccessory;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Domains\Catalog\Infrastructure\Services\RoofTileAccessoryService;
use App\Http\Controllers\Controller;
use App\Domains\Catalog\Infrastructure\Services\UnifiedProductCatalog;
use App\Infrastructure\ViewHistoryService;
use Illuminate\Http\Request;

class RoofTileAccessoryController extends Controller
{
    public const TYPE_BO_NOC = RoofTileAccessoryCategory::TYPE_BO_NOC;

    public const TYPE_CHU_VAN = RoofTileAccessoryCategory::TYPE_CHU_VAN;

    public function __construct(
        private readonly RoofTileAccessoryService $phuKienNgoiService,
        private readonly CatalogQueryService $catalogQuery,
    ) {}

    public function index()
    {
        $config = $this->phuKienNgoiService->getFirstRecord();
        $ngoiBoNocProducts = $this->catalogQuery->all('phu_kien_ngoi_ct', 'active', self::TYPE_BO_NOC);
        $boNocChuVanProducts = $this->catalogQuery->all('phu_kien_ngoi_ct', 'active', self::TYPE_CHU_VAN);

        return view('clients.catalog.products.phu-kien-ngoi.index', compact(
            'config', 'ngoiBoNocProducts', 'boNocChuVanProducts'
        ));
    }

    public function detailNgoiBoNoc($id, ViewHistoryService $historyService)
    {
        return $this->detailByType((int) $id, self::TYPE_BO_NOC, 'clients.catalog.products.phu-kien-ngoi.ngoi-bo-noc-detail', $historyService);
    }

    public function detailBoNocChuVan($id, ViewHistoryService $historyService)
    {
        return $this->detailByType((int) $id, self::TYPE_CHU_VAN, 'clients.catalog.products.phu-kien-ngoi.bo-noc-chu-van-detail', $historyService);
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
        $pageConfig = RoofTileAccessory::query()->first();
        $relatedProducts = $this->catalogQuery->related('phu_kien_ngoi_ct', (int) $product->public_id, null, 4);

        $journeyVideo = ProductJourneyVideo::resolve($product->video ?? null, $pageConfig);

        return view($view, compact(
            'product', 'type', 'phanLoais', 'relatedProducts', 'pageConfig', 'journeyVideo'
        ));
    }
}
