<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Catalog\Http\Admin\BaseProductItemController;
use Illuminate\Http\Request;

class LinhVatPhongThuyCtController extends BaseProductItemController
{
    protected string $typeKey = 'linh_vat_phong_thuy_ct';
    protected string $viewPrefix = 'admin.linh-vat-phong-thuy-ct';
    protected string $routePrefix = 'admin.linh-vat-phong-thuy-ct';
    protected string $itemLabel = 'Linh Vật Phong Thủy';
    protected string $imageDirectory = 'linh_vat_phong_thuy_ct';
    protected string $sizeDirectory = 'linh_vat_phong_thuy_ct/sizes';

    protected function customStoreRules(Request $request): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }
}
