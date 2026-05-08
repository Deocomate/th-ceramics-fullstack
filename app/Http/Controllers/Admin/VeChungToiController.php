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
        $section = $request->query('section');
        
        // Kiểm tra section
        if (!$section || !in_array($section,['banner', 'gom_su_1', 'gom_su_2', 'gom_su_3', 'che_tac'])) {
            return back()->with('error', 'Section lưu không hợp lệ.');
        }

        // Thực hiện update
        $this->service->updateSection($section, $request->all());

        // Custom Message theo Section
        $messages =[
            'banner'   => 'Cập nhật Banner thành công.',
            'gom_su_1' => 'Cập nhật Điểm Nhấn & Giá Trị Cốt Lõi thành công.',
            'gom_su_2' => 'Cập nhật Lịch Sử & Giải Thưởng thành công.',
            'gom_su_3' => 'Cập nhật Thông tin Người Sáng Lập thành công.',
            'che_tac'  => 'Cập nhật Nghệ Thuật Chế Tác thành công.',
        ];

        return back()->with('success', $messages[$section]);
    }
}