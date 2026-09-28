<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Catalog\Http\Admin\BaseProductItemController;
use Illuminate\Http\Request;

class GachHoaThongGioCtController extends BaseProductItemController
{
    protected string $typeKey = 'gach_hoa_thong_gio_ct';
    protected string $viewPrefix = 'admin.gach-hoa-thong-gio-ct';
    protected string $routePrefix = 'admin.gach-hoa-thong-gio-ct';
    protected string $itemLabel = 'Gạch Hoa Thông Gió';
    protected string $imageDirectory = 'gach_hoa_thong_gio_ct';
    protected string $sizeDirectory = 'gach_hoa_thong_gio_ct/sizes';

    protected function customStoreRules(Request $request): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }
}
