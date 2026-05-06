<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\LanCanGomXuService;

class LanCanGomSuController extends Controller
{
    public function __construct(
        private readonly LanCanGomXuService $lanCanGomXuService,
    ) {}

    public function index()
    {
        $config = $this->lanCanGomXuService->getFirstRecord();

        return view('clients.products.lan-can-gom-su.index', compact('config'));
    }

    public function detail($id)
    {
        return view('clients.products.lan-can-gom-su.detail', compact('id'));
    }
}
