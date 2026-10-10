<?php

namespace App\Domains\Catalog\Http\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GachTrangTriCtController extends BaseProductItemController
{
    protected string $typeKey = 'gach_trang_tri_ct';

    protected string $viewPrefix = 'admin.catalog.gach-trang-tri-ct';

    protected string $routePrefix = 'admin.gach-trang-tri-ct';

    protected string $itemLabel = 'Gạch Trang Trí';

    protected string $imageDirectory = 'gach_trang_tri_ct';

    protected string $sizeDirectory = 'gach_trang_tri_ct/sizes';

    public function store(Request $request): RedirectResponse
    {
        try {
            return parent::store($request);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
}
