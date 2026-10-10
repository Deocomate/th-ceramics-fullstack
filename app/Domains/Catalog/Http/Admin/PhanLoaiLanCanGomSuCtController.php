<?php

namespace App\Domains\Catalog\Http\Admin;

class PhanLoaiLanCanGomSuCtController extends BaseProductVariantController
{
    protected string $typeKey = 'lan_can_gom_su_ct';

    protected string $viewPrefix = 'admin.catalog.phan-loai-lan-can-gom-su-ct';

    protected string $routePrefix = 'admin.phan-loai-lan-can-gom-su-ct';

    protected string $foreignKey = 'lan_can_gom_su_ct_id';

    protected string $imageDirectory = 'lan_can_gom_su_ct/variants';
}
