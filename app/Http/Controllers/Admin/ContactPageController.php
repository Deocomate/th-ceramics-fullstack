<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactPageRequest;
use App\Services\ContactPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactPageController extends Controller
{
    public function __construct(private readonly ContactPageService $service) {}

    public function edit(): View
    {
        $contactPage = $this->service->getFirstRecord();

        return view('admin.pages.contact.edit', compact('contactPage'));
    }

    public function update(ContactPageRequest $request): RedirectResponse
    {
        $this->service->update($request->validated());

        return back()->with('success', 'Cập nhật trang Liên hệ thành công.');
    }
}
