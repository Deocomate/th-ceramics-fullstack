<?php

namespace App\Domains\Catalog\Http\Client;

use App\Domains\Catalog\Http\Presenters\CatalogHttpPresenter;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __construct(private readonly CatalogQueryService $catalogQuery) {}

    /**
     * Handle quick product search requests from the header.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $keyword = trim((string) $request->query('q', ''));

        if (mb_strlen($keyword) < 2) {
            return response()->json(['products' => []]);
        }

        $variants = $this->catalogQuery->search($keyword);

        return response()->json([
            'products' => CatalogHttpPresenter::presentSearchResults($variants)->values(),
        ]);
    }
}
