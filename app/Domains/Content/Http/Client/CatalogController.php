<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Infrastructure\Models\Catalog;
use App\Http\Controllers\Controller;
use App\Support\AssetPath;
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

        // Readers only ever receive page images; the original file stays private.
        $pages = array_map(static fn (array $item): array => [
            'url' => AssetPath::url($item['path']),
            'w' => (int) $item['w'],
            'h' => (int) $item['h'],
            'pdfPage' => (int) $item['pdf_page'],
            'side' => (string) $item['side'],
        ], $catalog->pageItems());

        if ($pages === []) {
            abort(404);
        }

        return view('clients.content.dich-vu-khach-hang.flipbook', compact('catalog', 'pages'));
    }
}
