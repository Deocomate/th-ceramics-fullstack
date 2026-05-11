<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\VeChungToi;

class AboutController extends Controller
{
    public function index()
    {
        return view('clients.about.index', [
            'about' => VeChungToi::first(),
        ]);
    }
}
