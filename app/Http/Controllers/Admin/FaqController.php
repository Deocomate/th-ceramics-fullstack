<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use App\Services\FaqService;
use Illuminate\Http\RedirectResponse;

class FaqController extends Controller
{
    public function __construct(private readonly FaqService $service) {}

    public function index(): RedirectResponse
    {
        return redirect()->route('admin.pages.faq.edit');
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.pages.faq.edit');
    }

    public function edit(Faq $faq): RedirectResponse
    {
        return redirect()->route('admin.pages.faq.edit');
    }

    public function store(FaqRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return back()->with('success', 'Đã thêm câu hỏi FAQ mới.');
    }

    public function update(FaqRequest $request, Faq $faq): RedirectResponse
    {
        $this->service->update($faq, $request->validated());

        return back()->with('success', 'Đã cập nhật câu hỏi FAQ.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $this->service->destroy($faq);

        return back()->with('success', 'Đã xóa câu hỏi FAQ.');
    }
}
