<?php

namespace App\Domains\Content\Http\Admin;

use App\Domains\Content\Http\Requests\ContactPageRequest;
use App\Domains\Content\Infrastructure\Services\ContactPageConfigService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactPageController extends Controller
{
    public function __construct(private readonly ContactPageConfigService $service) {}

    public function edit(): View
    {
        $contactPage = $this->service->getFirstRecord();

        return view('admin.content.pages.contact.edit', compact('contactPage'));
    }

    public function update(ContactPageRequest $request): RedirectResponse
    {
        $this->service->update($request->validated());

        return back()->with('success', 'Cập nhật trang Liên hệ thành công.');
    }
}
