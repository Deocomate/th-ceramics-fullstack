<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Catalog\Http\Admin\BaseProductItemController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DenVuonGomSuCtController extends BaseProductItemController
{
    protected string $typeKey = 'den_vuon_gom_su_ct';
    protected string $viewPrefix = 'admin.den-vuon-gom-su-ct';
    protected string $routePrefix = 'admin.den-vuon-gom-su-ct';
    protected string $itemLabel = 'Đèn Vườn Gốm Sứ';
    protected string $imageDirectory = 'den_vuon_gom_su_ct';
    protected string $sizeDirectory = 'den_vuon_gom_su_ct/sizes';

    public function index(Request $request): View
    {
        $status = $request->query('status', 'active');
        $categoryType = $request->query('category_type', 'all');
        abort_unless(in_array($categoryType, ['all', 'den_gom', 'den_su'], true), 404);
        $products = $this->queryService->all($this->typeKey, $status, $categoryType === 'all' ? null : $categoryType);

        return view("{$this->viewPrefix}.index", compact('products', 'status', 'categoryType'));
    }

    protected function customStoreRules(Request $request): array
    {
        return [
            'category_type' => ['required', Rule::in(['den_gom', 'den_su'])],
        ];
    }
}
