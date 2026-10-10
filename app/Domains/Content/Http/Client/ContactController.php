<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Http\Requests\ContactFormRequest;
use App\Domains\Content\Infrastructure\Services\ContactPageService;
use App\Http\Controllers\Controller;
use App\Domains\Content\Infrastructure\Mail\ContactFormMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(ContactPageService $service): View
    {
        return view('clients.content.contact.index', [
            'contact' => $service->getFirstRecord(),
        ]);
    }

    public function submit(ContactFormRequest $request): RedirectResponse
    {
        Mail::to(config('mail.contact_email', 'gshaithanh@gmail.com'))
            ->queue(new ContactFormMail($request->validated()));

        return redirect()
            ->route('client.contact')
            ->with('success', 'Cảm ơn bạn! Chúng tôi sẽ liên hệ lại sớm nhất.');
    }
}
