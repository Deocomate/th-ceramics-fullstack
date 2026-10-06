<?php

namespace App\Domains\Catalog\Http\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GardenCeramicLampAdminController extends BaseProductItemController
{
    protected string $typeKey = 'den_vuon_gom_su_ct';

    protected string $viewPrefix = 'admin.catalog.den-vuon-gom-su-ct';

    protected string $routePrefix = 'admin.den-vuon-gom-su-ct';

    protected string $itemLabel = 'Đèn Vườn Gốm Sứ';

    protected string $imageDirectory = 'den_vuon_gom_su_ct';

    protected string $sizeDirectory = 'den_vuon_gom_su_ct/sizes';

    protected function customStoreRules(Request $request): array
    {
        return [
            'category_type' => ['required', 'string', Rule::in(['den_gom', 'den_su'])],
        ];
    }
}
