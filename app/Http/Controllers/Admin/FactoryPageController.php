<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FactoryPageRequest;
use App\Services\FactoryPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FactoryPageController extends Controller
{
    public function __construct(private readonly FactoryPageService $service) {}

    public function edit(): View
    {
        return view('admin.pages.factory.edit', [
            'factory' => $this->service->getFirstRecord(),
        ]);
    }

    public function update(FactoryPageRequest $request): RedirectResponse
    {
        $this->service->update($request->validated());

        return back()->with('success', 'Cập nhật trang Xưởng sản xuất thành công.');
    }
}
