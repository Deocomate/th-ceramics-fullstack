<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Catalog\Http\Admin\BaseProductItemController;
use Illuminate\Http\Request;

class GachTrangTriCtController extends BaseProductItemController
{
    protected string $typeKey = 'gach_trang_tri_ct';
    protected string $viewPrefix = 'admin.gach-trang-tri-ct';
    protected string $routePrefix = 'admin.gach-trang-tri-ct';
    protected string $itemLabel = 'Gạch Trang Trí';
    protected string $imageDirectory = 'gach_trang_tri_ct';
    protected string $sizeDirectory = 'gach_trang_tri_ct/sizes';

    protected function customStoreRules(Request $request): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }
}
