<?php

namespace App\Domains\Content\Http\Admin;

use App\Domains\Content\Infrastructure\Services\InstallationGuideService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstallationGuideController extends Controller
{
    public function __construct(private readonly InstallationGuideService $service) {}

    public function index(): View
    {
        $thiCongs = $this->service->getAll();

        return view('admin.content.thi-cong.index', compact('thiCongs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tieu_de' => ['required', 'string', 'max:255'],
            'link_youtube' => ['nullable', 'string', 'max:500'],
            'anh' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->store($data);

        return back()->with('success', 'Thêm video thi công thành công.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'tieu_de' => ['required', 'string', 'max:255'],
            'link_youtube' => ['nullable', 'string', 'max:500'],
            'anh' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($id, $data);

        return back()->with('success', 'Cập nhật video thi công thành công.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->destroy($id);

        return back()->with('success', 'Đã xóa video thi công khỏi danh sách.');
    }
}
