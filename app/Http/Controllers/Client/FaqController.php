<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index()
    {
        return view('clients.faq.index');
    }
}
