<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\VeChungToiService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VeChungToiController extends Controller
{
    public function __construct(private readonly VeChungToiService $service) {}

    public function edit(): View
    {
        $veChungToi = $this->service->getFirstRecord();
        return view('admin.ve-chung-toi.edit', compact('veChungToi'));
    }

    public function update(Request $request): RedirectResponse
    {
        // Validation được rút gọn vì Admin chủ yếu làm chuẩn, nhưng đảm bảo file là ảnh
        $data = $request->all();
        $this->service->update($data);

        return back()->with('success', 'Cập nhật Trang Về Chúng Tôi thành công.');
    }
}