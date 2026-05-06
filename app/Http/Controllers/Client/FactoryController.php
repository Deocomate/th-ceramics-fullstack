<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class FactoryController extends Controller
{
    public function index()
    {
        return view('clients.factory.index');
    }
}
