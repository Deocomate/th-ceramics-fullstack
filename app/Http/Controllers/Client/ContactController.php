<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ContactPageService;

class ContactController extends Controller
{
    public function index(ContactPageService $service)
    {
        return view('clients.contact.index', [
            'contact' => $service->getFirstRecord(),
        ]);
    }
}
