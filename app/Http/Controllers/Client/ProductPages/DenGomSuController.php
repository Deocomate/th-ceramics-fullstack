<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Models\DenGomSu;
use App\Services\DenGomSuService;
use App\Services\ViewHistoryService;

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

    public function detail($id, ViewHistoryService $historyService)
    {
        if (DenGomSu::query()->whereKey($id)->exists()) {
            $historyService->trackProduct('den_gom_su', (int) $id);
        }

        return view('clients.products.den-gom-su.detail', compact('id'));
    }
}
