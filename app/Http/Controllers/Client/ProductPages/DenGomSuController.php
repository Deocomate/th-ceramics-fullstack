<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Services\DenGomSuService;

class DenGomSuController extends Controller
{
    public function __construct(
        private readonly DenGomSuService $denGomSuService,
    ) {}

    public function index()
    {
        $config = $this->denGomSuService->getFirstRecord();

        return view('clients.products.den-gom-su.index', compact('config'));
    }

    public function detail($id)
    {
        return view('clients.products.den-gom-su.detail', compact('id'));
    }
}
