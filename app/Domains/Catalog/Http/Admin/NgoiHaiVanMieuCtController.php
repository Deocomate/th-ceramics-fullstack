<?php

namespace App\Domains\Catalog\Http\Admin;

class NgoiHaiVanMieuCtController extends BaseProductItemController
{
    protected string $typeKey = 'ngoi_hai_van_mieu_ct';

    protected string $viewPrefix = 'admin.catalog.ngoi-hai-van-mieu-ct';

    protected string $routePrefix = 'admin.ngoi-hai-van-mieu-ct';

    protected string $itemLabel = 'Ngói Hài Văn Miếu';

    protected string $imageDirectory = 'ngoi_hai_van_mieu_ct';

    protected string $sizeDirectory = 'ngoi_hai_van_mieu_ct/sizes';
}
