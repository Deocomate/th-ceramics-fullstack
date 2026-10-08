<?php

namespace App\Domains\Catalog\Http\Admin;

class CategoryGardenCeramicLampAdminController extends BaseProductVariantController
{
    protected string $typeKey = 'den_vuon_gom_su_ct';

    protected string $viewPrefix = 'admin.catalog.phan-loai-den-vuon-gom-su-ct';

    protected string $routePrefix = 'admin.phan-loai-den-vuon-gom-su-ct';

    protected string $foreignKey = 'den_vuon_gom_su_ct_id';

    protected string $imageDirectory = 'den_vuon_gom_su_ct/variants';
}
