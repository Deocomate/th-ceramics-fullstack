<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Catalog\Http\Admin\BaseProductItemController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GachCoBatTrangCtController extends BaseProductItemController
{
    protected string $typeKey = 'gach_co_bat_trang_ct';
    protected string $viewPrefix = 'admin.gach-co-bat-trang-ct';
    protected string $routePrefix = 'admin.gach-co-bat-trang-ct';
    protected string $itemLabel = 'Gạch Cổ Bát Tràng';
    protected string $imageDirectory = 'gach_co_bat_trang_ct';
    protected string $sizeDirectory = 'gach_co_bat_trang_ct/sizes';

    protected function customStoreRules(Request $request): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
            'category_type' => ['required', Rule::in(['bat', 'that', 'the'])],
        ];
    }
}
