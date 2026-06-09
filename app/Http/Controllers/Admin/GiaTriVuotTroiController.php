<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GiaTriVuotTroiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GiaTriVuotTroiController extends Controller
{
    public function __construct(private readonly GiaTriVuotTroiService $service) {}

    public function index(): View
    {
        $giaTriVuotTroi = $this->service->getAll();

        return view('admin.gia-tri-vuot-troi.index', compact('giaTriVuotTroi'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title' => ['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'],
        ], [
            'title.max' => 'Tiêu đề không được vượt quá 50 ký tự.',
            'image.max' => 'Hình ảnh không được vượt quá 5MB.',
        ]);

        $this->service->addGiaTri($data);

        return back()->with('success', 'Thêm Giá trị vượt trội thành công.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title' => ['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'],
        ], [
            'title.max' => 'Tiêu đề không được vượt quá 50 ký tự.',
            'image.max' => 'Hình ảnh không được vượt quá 5MB.',
        ]);

        $this->service->updateGiaTri($id, $data);

        return back()->with('success', 'Cập nhật Giá trị vượt trội thành công.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->deleteGiaTri($id);

        return back()->with('success', 'Đã xóa Giá trị vượt trội khỏi danh sách.');
    }
}
