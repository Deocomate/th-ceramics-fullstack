<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Catalog\Http\Admin\BaseProductItemController;

class NgoiHaiVanMieuCtController extends BaseProductItemController
{
    protected string $typeKey = 'ngoi_hai_van_mieu_ct';
    protected string $viewPrefix = 'admin.ngoi-hai-van-mieu-ct';
    protected string $routePrefix = 'admin.ngoi-hai-van-mieu-ct';
    protected string $itemLabel = 'Ngói Hài Văn Miếu';
    protected string $imageDirectory = 'ngoi_hai_van_mieu_ct';
    protected string $sizeDirectory = 'ngoi_hai_van_mieu_ct/sizes';
}
