<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TacGiaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TacGiaController extends Controller
{
    public function __construct(private readonly TacGiaService $service) {}

    public function index(): View
    {
        $tacGias = $this->service->getAll();

        return view('admin.tac-gia.index', compact('tacGias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ten_tac_gia' => ['required', 'string', 'max:255'],
            'link_fb' => ['nullable', 'string', 'max:500'],
            'link_linkedin' => ['nullable', 'string', 'max:500'],
            'link_tele' => ['nullable', 'string', 'max:500'],
            'link_sky' => ['nullable', 'string', 'max:500'],
            'mo_ta' => ['required', 'string'],
            'anh_dai_dien' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->store($data);

        return back()->with('success', 'Thêm tác giả thành công.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'ten_tac_gia' => ['required', 'string', 'max:255'],
            'link_fb' => ['nullable', 'string', 'max:500'],
            'link_linkedin' => ['nullable', 'string', 'max:500'],
            'link_tele' => ['nullable', 'string', 'max:500'],
            'link_sky' => ['nullable', 'string', 'max:500'],
            'mo_ta' => ['required', 'string'],
            'anh_dai_dien' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($id, $data);

        return back()->with('success', 'Cập nhật tác giả thành công.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->destroy($id);

        return back()->with('success', 'Đã xóa tác giả khỏi danh sách.');
    }
}
