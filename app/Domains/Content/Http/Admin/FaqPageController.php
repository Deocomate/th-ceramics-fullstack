<?php

namespace App\Domains\Content\Http\Admin;

use App\Domains\Content\Services\FaqPageService;
use App\Domains\Content\Services\FaqService;
use App\Http\Controllers\Controller;
use App\Http\Requests\FaqPageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FaqPageController extends Controller
{
    public function __construct(
        private readonly FaqPageService $faqPageService,
        private readonly FaqService $faqService,
    ) {}

    public function edit(): View
    {
        $faqPage = $this->faqPageService->getFirstRecord();
        $faqs = $this->faqService->getAll();

        return view('admin.pages.faq.edit', compact('faqPage', 'faqs'));
    }

    public function update(FaqPageRequest $request): RedirectResponse
    {
        $this->faqPageService->update($request->validated());

        return back()->with('success', 'Cập nhật banner FAQ thành công.');
    }
}
