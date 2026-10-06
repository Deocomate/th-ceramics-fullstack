<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Infrastructure\Models\HomePageConfig;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ShowroomController extends Controller
{
    public function index(): View
    {
        $trangChu = HomePageConfig::query()->first();

        return view('clients.content.showroom.index', [
            'showroomImages' => collect($trangChu?->showroom_images ?? [])->values(),
            'showroomContent' => $trangChu?->showroom_noidung,
        ]);
    }
}
