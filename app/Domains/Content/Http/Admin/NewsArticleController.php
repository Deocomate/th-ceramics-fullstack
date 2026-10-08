<?php

namespace App\Domains\Content\Http\Admin;

use App\Domains\Content\Infrastructure\Models\NewsCategory;
use App\Domains\Content\Infrastructure\Services\NewsArticleService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsArticleController extends Controller
{
    public function __construct(private readonly NewsArticleService $service) {}

    public function index(Request $request): View
    {
        $danhMucId = $request->query('danh_muc_id') ? (int) $request->query('danh_muc_id') : null;
        $tinTucs = $this->service->getAll($danhMucId);
        $danhMucs = NewsCategory::where('is_delete', 0)->get();

        return view('admin.content.tin-tuc.index', compact('tinTucs', 'danhMucs', 'danhMucId'));
    }

    public function create(): View
    {
        $danhMucs = NewsCategory::where('is_delete', 0)->get();

        return view('admin.content.tin-tuc.create', compact('danhMucs'));
    }

    public function store(Request $request): RedirectResponse
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

    public function edit(int $id): View
    {
        $tinTuc = $this->service->findById($id);
        $danhMucs = NewsCategory::where('is_delete', 0)->get();

        return view('admin.content.tin-tuc.edit', compact('tinTuc', 'danhMucs'));
    }

    public function update(Request $request, int $id): RedirectResponse
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

    public function destroy(int $id): RedirectResponse
    {
        $this->service->destroy($id);

        return back()->with('success', 'Đã xóa tin tức thành công.');
    }
}
