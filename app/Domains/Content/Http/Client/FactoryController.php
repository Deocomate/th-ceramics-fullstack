<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Services\FactoryPageService;
use App\Http\Controllers\Controller;

class FactoryController extends Controller
{
    public function index(FactoryPageService $service)
    {
        return view('clients.factory.index', [
            'factory' => $service->getFirstRecord(),
        ]);
    }
}
