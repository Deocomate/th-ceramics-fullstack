<?php

namespace App\Domains\Catalog\Http\Admin;

class ColorOptionVanMieuFishScaleRoofTileAdminController extends BaseProductVariantController
{
    protected string $typeKey = 'ngoi_hai_van_mieu_ct';

    protected string $viewPrefix = 'admin.catalog.mau-sac-ngoi-hai-van-mieu-ct';

    protected string $routePrefix = 'admin.mau-sac-ngoi-hai-van-mieu-ct';

    protected string $foreignKey = 'ngoi_hai_van_mieu_ct_id';

    protected string $imageDirectory = 'ngoi_hai_van_mieu_ct/variants';
}
