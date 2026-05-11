<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\TrangChu;
use Illuminate\Contracts\View\View;

class ShowroomController extends Controller
{
    public function index(): View
    {
        $trangChu = TrangChu::query()->first();

        return view('clients.showroom.index', [
            'showroomImages' => collect($trangChu?->showroom_images ?? [])->values(),
            'showroomContent' => $trangChu?->showroom_noidung,
        ]);
    }
}
