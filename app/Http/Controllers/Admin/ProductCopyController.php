<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductCopyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ProductCopyController extends Controller
{
    public function __construct(
        protected ProductCopyService $copyService
    ) {}

    /**
     * Lấy danh sách sản phẩm phục vụ tìm kiếm trong Modal
     */
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

    /**
     * Lấy thông tin chi tiết của 1 sản phẩm để fill vào form
     */
    public function detail(string $type, int $id): JsonResponse
    {
        $product = $this->copyService->getProductDetailForCopy($type, $id);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm yêu cầu.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }
}
