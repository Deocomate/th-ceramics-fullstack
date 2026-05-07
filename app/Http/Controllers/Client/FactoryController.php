<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\FactoryPageService;

class FactoryController extends Controller
{
    public function index(FactoryPageService $service)
    {
        return view('clients.factory.index', [
            'factory' => $service->getFirstRecord(),
        ]);
    }
}
