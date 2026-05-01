<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\LanCanGomXuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class LanCanGomXuController extends Controller
{
    public function __construct(private readonly LanCanGomXuService $service) {}
    public function index(): View
    {
        $lanCanGomXu = $this->service->getFirstRecord();
        return view('admin.lan-can-gom-xu.edit', compact('lanCanGomXu'));
    }
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          =>['nullable', 'string', 'max:500'],
        ]);
        $this->service->update($data);
        return back()->with('success', 'Cập nhật cấu hình thành công.');
    }
}