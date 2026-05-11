<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactFormMail;
use App\Services\ContactPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(ContactPageService $service)
    {
        return view('clients.contact.index', [
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
