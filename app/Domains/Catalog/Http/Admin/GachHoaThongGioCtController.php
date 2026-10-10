<?php

namespace App\Domains\Catalog\Http\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GachHoaThongGioCtController extends BaseProductItemController
{
    protected string $typeKey = 'gach_hoa_thong_gio_ct';

    protected string $viewPrefix = 'admin.catalog.gach-hoa-thong-gio-ct';

    protected string $routePrefix = 'admin.gach-hoa-thong-gio-ct';

    protected string $itemLabel = 'Gạch Hoa Thông Gió';

    protected string $imageDirectory = 'gach_hoa_thong_gio_ct';

    protected string $sizeDirectory = 'gach_hoa_thong_gio_ct/sizes';

    public function store(Request $request): RedirectResponse
    {
        try {
            return parent::store($request);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
}
