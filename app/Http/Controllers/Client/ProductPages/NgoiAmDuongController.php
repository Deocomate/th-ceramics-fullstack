<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\DinhMucNgoiAmDuongService;
use App\Services\GiaTriVuotTroiService;
use App\Services\MauSacNgoiAmDuongCtService;
use App\Services\NgoiAmDuongCtService;
use App\Services\NgoiAmDuongService;
use App\Support\CollectionPaginator;
use App\Support\ProductCollectionFilter;
use Illuminate\Http\Request;

class NgoiAmDuongController extends Controller
{
    public function __construct(
        private readonly NgoiAmDuongService $ngoiAmDuongService,
        private readonly NgoiAmDuongCtService $ngoiAmDuongCtService,
        private readonly MauSacNgoiAmDuongCtService $mauSacService,
        private readonly DinhMucNgoiAmDuongService $dinhMucService,
        private readonly GiaTriVuotTroiService $giaTriVuotTroiService
    ) {}

    /**
     * Trang danh mục Ngói Âm Dương
     */
    public function index(Request $request)
    {
        // 1. Lấy cấu hình chung (Banner, Video trang ngói âm dương)
        $config = $this->ngoiAmDuongService->getFirstRecord();

        // 2. Lấy danh sách sản phẩm (chỉ lấy các SP đang active)
        $products = ProductCollectionFilter::apply(
            $this->ngoiAmDuongCtService->getAll('active'),
            $request->only(['search', 'sort'])
        );
        $products = CollectionPaginator::paginate($products, 12);

        // 3. Lấy giá trị vượt trội chung
        $giaTriVuotTroi = $this->giaTriVuotTroiService->getAll();

        // Trả data về View
        return view('clients.products.ngoi-am-duong.index', compact(
            'config',
            'products',
            'giaTriVuotTroi'
        ));
    }

    /**
     * Trang chi tiết 1 sản phẩm Ngói Âm Dương cụ thể
     */
    public function detail($id)
    {
        // 1. Lấy thông tin chi tiết của sản phẩm theo ID
        $product = $this->ngoiAmDuongCtService->findById($id);

        // Kiểm tra nếu sản phẩm đã bị xóa (is_delete = 1) thì báo lỗi 404
        if ($product->is_delete == 1) {
            abort(404, 'Sản phẩm không tồn tại hoặc đã bị gỡ.');
        }

        // 2. Lấy danh sách màu sắc của ngói âm dương
        $colors = $this->mauSacService->getAll();

        // 3. Lấy bảng định mức thi công
        $dinhMuc = $this->dinhMucService->getAll();

        // 4. Lấy sản phẩm liên quan (Gợi ý các sản phẩm Ngói Âm Dương khác)
        // Lấy tất cả active và loại trừ sản phẩm hiện tại, lấy ngẫu nhiên 4 sản phẩm
        $relatedProducts = $this->ngoiAmDuongCtService->getAll('active')
            ->where('ngoi_am_duong_ct_id', '!=', $id)
            ->take(4);

        // Trả data về View
        return view('clients.products.ngoi-am-duong.detail', compact(
            'product',
            'colors',
            'dinhMuc',
            'relatedProducts'
        ));
    }
}
