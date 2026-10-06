<?php

namespace App\Domains\Content\Http\Admin;

use App\Domains\Content\Infrastructure\Services\AboutPageConfigService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutPageConfigController extends Controller
{
    public function __construct(private readonly AboutPageConfigService $service) {}

    public function edit(): View
    {
        $veChungToi = $this->service->getFirstRecord();

        return view('admin.content.ve-chung-toi.edit', compact('veChungToi'));
    }

    public function update(Request $request): RedirectResponse
    {
        $section = $request->query('section');

        if (! $section || ! in_array($section, ['banner', 'gom_su_1', 'gom_su_2', 'gom_su_3', 'che_tac'])) {
            return back()->with('error', 'Section lưu không hợp lệ.');
        }

        $this->service->updateSection($section, $request->all());

        $messages = [
            'banner' => 'Cập nhật Banner thành công.',
            'gom_su_1' => 'Cập nhật Điểm Nhấn & Giá Trị Cốt Lõi thành công.',
            'gom_su_2' => 'Cập nhật Lịch Sử & Giải Thưởng thành công.',
            'gom_su_3' => 'Cập nhật Thông tin Người Sáng Lập thành công.',
            'che_tac' => 'Cập nhật Nghệ Thuật Chế Tác thành công.',
        ];

        return back()->with('success', $messages[$section]);
    }
}
