<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhMucTinTuc;
use App\Services\TinTucService;
use Illuminate\Http\Request;

class TinTucController extends Controller
{
    public function __construct(private readonly TinTucService $service) {}

    public function index(Request $request)
    {
        $danhMucId = $request->query('danh_muc_id');
        $tinTucs = $this->service->getAll($danhMucId);
        $danhMucs = DanhMucTinTuc::where('is_delete', 0)->get();

        return view('admin.tin-tuc.index', compact('tinTucs', 'danhMucs', 'danhMucId'));
    }

    public function create()
    {
        $danhMucs = DanhMucTinTuc::where('is_delete', 0)->get();

        return view('admin.tin-tuc.create', compact('danhMucs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'danh_muc_tin_tuc_id' => ['required', 'exists:danh_muc_tin_tuc,danh_muc_tin_tuc_id'],
            'tieu_de' => ['required', 'string', 'max:255'],
            'mo_ta_ngan' => ['required', 'string'],
            'the_loai' => ['nullable', 'string', 'max:255'],
            'trang_thai' => ['required', 'in:draft,published'],
            'anh_dai_dien' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'blocks' => ['nullable', 'array'],
            'block_images' => ['nullable', 'array'],
        ]);

        $this->service->create($data);

        return redirect()->route('admin.tin-tuc.index')->with('success', 'Thêm mới Tin tức thành công.');
    }

    public function edit(int $id)
    {
        $tinTuc = $this->service->findById($id);
        $danhMucs = DanhMucTinTuc::where('is_delete', 0)->get();

        return view('admin.tin-tuc.edit', compact('tinTuc', 'danhMucs'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'danh_muc_tin_tuc_id' => ['required', 'exists:danh_muc_tin_tuc,danh_muc_tin_tuc_id'],
            'tieu_de' => ['required', 'string', 'max:255'],
            'mo_ta_ngan' => ['required', 'string'],
            'the_loai' => ['nullable', 'string', 'max:255'],
            'trang_thai' => ['required', 'in:draft,published'],
            'anh_dai_dien' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'blocks' => ['nullable', 'array'],
            'block_images' => ['nullable', 'array'],
        ]);

        $this->service->update($id, $data);

        return back()->with('success', 'Cập nhật Tin tức thành công.');
    }

    public function destroy(int $id)
    {
        $this->service->destroy($id);

        return back()->with('success', 'Đã xóa tin tức thành công.');
    }
}
