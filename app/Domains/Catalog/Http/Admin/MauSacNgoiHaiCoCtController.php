<?php

namespace App\Domains\Catalog\Http\Admin;

class MauSacNgoiHaiCoCtController extends BaseProductVariantController
{
    protected string $typeKey = 'ngoi_hai_co_ct';

    protected string $viewPrefix = 'admin.catalog.mau-sac-ngoi-hai-co-ct';

    protected string $routePrefix = 'admin.mau-sac-ngoi-hai-co-ct';

    protected string $foreignKey = 'ngoi_hai_co_ct_id';

    protected string $imageDirectory = 'ngoi_hai_co_ct/variants';
}
