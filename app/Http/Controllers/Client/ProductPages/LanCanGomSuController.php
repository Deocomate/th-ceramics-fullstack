<?php

namespace App\Http\Controllers\Client\ProductPages;

use App\Http\Controllers\Controller;
use App\Models\LanCanGomXu;
use App\Services\LanCanGomXuService;
use App\Services\ViewHistoryService;

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

    public function detail($id, ViewHistoryService $historyService)
    {
        if (LanCanGomXu::query()->whereKey($id)->exists()) {
            $historyService->trackProduct('lan_can_gom_xu', (int) $id);
        }

        return view('clients.products.lan-can-gom-su.detail', compact('id'));
    }
}
