<?php

namespace App\Domains\Content\Http\Admin;

use App\Domains\Content\Infrastructure\Models\ProjectCategory;
use App\Domains\Content\Infrastructure\Services\ProjectService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $service) {}

    public function index(Request $request): View
    {
        $danhMucId = $request->query('danh_muc_id') ? (int) $request->query('danh_muc_id') : null;
        $duAns = $this->service->getAll($danhMucId);
        $danhMucs = ProjectCategory::where('is_delete', 0)->get();

        return view('admin.content.du-an.index', compact('duAns', 'danhMucs', 'danhMucId'));
    }

    public function create(): View
    {
        $danhMucs = ProjectCategory::where('is_delete', 0)->get();

        return view('admin.content.du-an.create', compact('danhMucs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'danh_muc_du_an_id' => ['required', 'exists:danh_muc_du_an,danh_muc_du_an_id'],
            'ten_du_an' => ['required', 'string', 'max:255'],
            'dia_diem' => ['required', 'string', 'max:255'],
            'san_pham' => ['required', 'string', 'max:255'],
            'nam' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'images' => ['required', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->create($data);

        return redirect()->route('admin.du-an.index')->with('success', 'Thêm mới Dự án thành công.');
    }

    public function edit(int $id): View
    {
        $duAn = $this->service->findById($id);
        $danhMucs = ProjectCategory::where('is_delete', 0)->get();

        return view('admin.content.du-an.edit', compact('duAn', 'danhMucs'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'danh_muc_du_an_id' => ['required', 'exists:danh_muc_du_an,danh_muc_du_an_id'],
            'ten_du_an' => ['required', 'string', 'max:255'],
            'dia_diem' => ['required', 'string', 'max:255'],
            'san_pham' => ['required', 'string', 'max:255'],
            'nam' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'new_images' => ['nullable', 'array'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($id, $data);

        return back()->with('success', 'Cập nhật Dự án thành công.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->destroy($id);

        return back()->with('success', 'Đã xóa dự án thành công.');
    }

    public function destroyImage(Request $request, int $id): RedirectResponse
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeImageFromJson($id, $request->input('image_path'));

        return back()->with('success', 'Đã xóa ảnh khỏi dự án.');
    }
}
