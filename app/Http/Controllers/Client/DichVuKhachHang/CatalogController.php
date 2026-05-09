<?php

namespace App\Http\Controllers\Client\DichVuKhachHang;

use App\Http\Controllers\Controller;
use App\Models\Catalog;

class CatalogController extends Controller
{
    public function index()
    {
        $allCatalogs = Catalog::query()->latest()->get();

        $featuredCatalog = $allCatalogs->first();
        $catalogs = $allCatalogs->slice(1)->values();

        return view('clients.dich-vu-khach-hang.tai-catalog', compact('featuredCatalog', 'catalogs'));
    }

    public function read($id)
    {
        $catalog = Catalog::query()->findOrFail($id);

        if (! $catalog->file) {
            abort(404);
        }

        return view('clients.dich-vu-khach-hang.flipbook', compact('catalog'));
    }
}
