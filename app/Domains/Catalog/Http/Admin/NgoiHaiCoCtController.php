<?php

namespace App\Domains\Catalog\Http\Admin;

class NgoiHaiCoCtController extends BaseProductItemController
{
    protected string $typeKey = 'ngoi_hai_co_ct';

    protected string $viewPrefix = 'admin.catalog.ngoi-hai-co-ct';

    protected string $routePrefix = 'admin.ngoi-hai-co-ct';

    protected string $itemLabel = 'Ngói Hài Cổ';

    protected string $imageDirectory = 'ngoi_hai_co_ct';

    protected string $sizeDirectory = 'ngoi_hai_co_ct/sizes';
}
