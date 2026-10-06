<?php

namespace App\Domains\Catalog\Http\Admin;

use App\Domains\Catalog\Infrastructure\Services\ProductCopyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ProductCopyController extends Controller
{
    public function __construct(
        protected ProductCopyService $copyService
    ) {}

    public function list(Request $request): JsonResponse
    {
        $type = (string) $request->query('type', '');
        $keyword = $request->query('q');

        if (! $type) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng cung cấp loại sản phẩm (type).',
                'data' => [],
            ], 422);
        }

        try {
            $products = $this->copyService->searchProducts($type, $keyword);
            $types = $this->copyService->getSupportedTypes();

            return response()->json([
                'success' => true,
                'data' => $products,
                'types' => $types,
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 400);
        }
    }

    public function detail(string $type, int $id): JsonResponse
    {
        $product = $this->copyService->getProductDetailForCopy($type, $id);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm yêu cầu.',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }
}
