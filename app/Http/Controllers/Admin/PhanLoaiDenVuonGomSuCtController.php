<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\DenVuonGomSuCt;
use App\Services\PhanLoaiDenVuonGomSuCtService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class PhanLoaiDenVuonGomSuCtController extends Controller
{
    public function __construct(private readonly PhanLoaiDenVuonGomSuCtService $service) {}
    public function index(Request $request) {
        $status = $request->query('status', 'active');
        $productId = $request->query('product_id');
        $phanLoais = $this->service->getAll($status, $productId);
        $products = DenVuonGomSuCt::where('is_delete', 0)->get();
        $selectedProduct = $productId ? DenVuonGomSuCt::find($productId) : null;
        return view('admin.phan-loai-den-vuon-gom-su-ct.index', compact('phanLoais', 'products', 'status', 'selectedProduct'));
    }
    public function store(Request $request) {
        $data = $request->validate([
            'den_vuon_gom_su_ct_id' => ['required', 'exists:den_vuon_gom_su_ct,den_vuon_gom_su_ct_id'],
            'name' => ['required', 'string', 'max:255'], 'code' => ['required', 'string', 'max:50'], 'price' => ['required', 'integer', 'min:0'],
        ]);
        try {
            $this->service->create($data);
            return back()->with('success', 'Đã thêm phân loại thành công.');
        } catch (InvalidArgumentException $e) { return back()->withInput()->withErrors(['code' => $e->getMessage()]); }
    }
    public function update(Request $request, int $id) {
        $data = $request->validate([
            'den_vuon_gom_su_ct_id' => ['required', 'exists:den_vuon_gom_su_ct,den_vuon_gom_su_ct_id'],
            'name' => ['required', 'string', 'max:255'], 'code' => ['required', 'string', 'max:50'], 'price' => ['required', 'integer', 'min:0'],
        ]);
        try {
            $this->service->update($id, $data);
            return back()->with('success', 'Cập nhật phân loại thành công.');
        } catch (InvalidArgumentException $e) { return back()->withInput()->withErrors(['code' => $e->getMessage()]); }
    }
    public function destroy(int $id) {
        $this->service->toggleStatus($id, 1);
        return back()->with('success', 'Đã tạm ẩn phân loại.');
    }
    public function restore(int $id) {
        $this->service->toggleStatus($id, 0);
        return back()->with('success', 'Khôi phục phân loại thành công.');
    }
}