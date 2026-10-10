<?php

namespace App\Domains\Catalog\Http\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GachCoBatTrangCtController extends BaseProductItemController
{
    protected string $typeKey = 'gach_co_bat_trang_ct';

    protected string $viewPrefix = 'admin.catalog.gach-co-bat-trang-ct';

    protected string $routePrefix = 'admin.gach-co-bat-trang-ct';

    protected string $itemLabel = 'Gạch Cổ Bát Tràng';

    protected string $imageDirectory = 'gach_co_bat_trang_ct';

    protected string $sizeDirectory = 'gach_co_bat_trang_ct/sizes';

    protected function customStoreRules(Request $request): array
    {
        return [
            'category_type' => ['required', 'string', Rule::in(['bat', 'that', 'the'])],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }
}
