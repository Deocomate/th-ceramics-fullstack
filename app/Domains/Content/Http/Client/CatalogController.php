<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Infrastructure\Models\Catalog;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(): View
    {
        $allCatalogs = Catalog::query()->latest()->get();

        $featuredCatalog = $allCatalogs->first();
        $catalogs = $allCatalogs->slice(1)->values();

        return view('clients.content.dich-vu-khach-hang.tai-catalog', compact('featuredCatalog', 'catalogs'));
    }

    public function read(int $id): View
    {
        $catalog = Catalog::query()->findOrFail($id);

        if (! $catalog->file) {
            abort(404);
        }

        return view('clients.content.dich-vu-khach-hang.flipbook', compact('catalog'));
    }
}
