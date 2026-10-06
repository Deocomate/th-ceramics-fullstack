<?php

namespace App\Domains\Content\Http\Admin;

use App\Domains\Content\Infrastructure\Services\ProjectCategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectCategoryController extends Controller
{
    public function __construct(private readonly ProjectCategoryService $service) {}

    public function index(Request $request): View
    {
        $status = $request->query('status', 'active');
        $danhMucs = $this->service->getAll($status);

        return view('admin.content.danh-muc-du-an.index', compact('danhMucs', 'status'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ten_danh_muc' => ['required', 'string', 'max:255'],
        ]);

        $this->service->create($data);

        return back()->with('success', 'Thêm danh mục dự án thành công.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'ten_danh_muc' => ['required', 'string', 'max:255'],
        ]);

        $this->service->update($id, $data);

        return back()->with('success', 'Cập nhật danh mục thành công.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->toggleStatus($id, 1);

        return back()->with('success', 'Đã tạm ẩn danh mục.');
    }

    public function restore(int $id): RedirectResponse
    {
        $this->service->toggleStatus($id, 0);

        return back()->with('success', 'Khôi phục danh mục thành công.');
    }
}
