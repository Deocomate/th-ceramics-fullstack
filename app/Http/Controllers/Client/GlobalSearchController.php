<?php

namespace App\Http\Controllers\Client;

use App\Domains\Catalog\Services\CatalogQueryService;
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

        return response()->json([
            'products' => $this->catalogQuery->search($keyword)->values(),
        ]);
    }
}
