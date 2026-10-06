<?php

namespace App\Domains\Catalog\Http\Admin;

class CeramicBalustradeAdminController extends BaseProductItemController
{
    protected string $typeKey = 'lan_can_gom_su_ct';

    protected string $viewPrefix = 'admin.catalog.lan-can-gom-su-ct';

    protected string $routePrefix = 'admin.lan-can-gom-su-ct';

    protected string $itemLabel = 'Lan Can Gốm Sứ';

    protected string $imageDirectory = 'lan_can_gom_su_ct';

    protected string $sizeDirectory = 'lan_can_gom_su_ct/sizes';
}
