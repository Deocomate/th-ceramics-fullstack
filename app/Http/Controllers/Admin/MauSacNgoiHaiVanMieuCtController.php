<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Catalog\Http\Admin\BaseProductVariantController;

class MauSacNgoiHaiVanMieuCtController extends BaseProductVariantController
{
    protected string $typeKey = 'ngoi_hai_van_mieu_ct';
    protected string $viewPrefix = 'admin.mau-sac-ngoi-hai-van-mieu-ct';
    protected string $routePrefix = 'admin.mau-sac-ngoi-hai-van-mieu-ct';
    protected string $foreignKey = 'ngoi_hai_van_mieu_ct_id';
    protected string $imageDirectory = 'ngoi_hai_van_mieu_ct/variants';
}
