<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Infrastructure\Services\FactoryPageConfigService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class FactoryController extends Controller
{
    public function index(FactoryPageConfigService $service): View
    {
        return view('clients.content.factory.index', [
            'factory' => $service->getFirstRecord(),
        ]);
    }
}
