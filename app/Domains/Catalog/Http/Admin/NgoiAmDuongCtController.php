<?php

namespace App\Domains\Catalog\Http\Admin;

use Illuminate\Http\Request;

class NgoiAmDuongCtController extends BaseProductItemController
{
    protected string $typeKey = 'ngoi_am_duong_ct';

    protected string $viewPrefix = 'admin.catalog.ngoi-am-duong-ct';

    protected string $routePrefix = 'admin.ngoi-am-duong-ct';

    protected string $itemLabel = 'chi tiết Ngói Âm Dương';

    protected string $imageDirectory = 'ngoi_am_duong_ct';

    protected string $sizeDirectory = 'ngoi_am_duong_ct/sizes';

    protected function customStoreRules(Request $request): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }
}
