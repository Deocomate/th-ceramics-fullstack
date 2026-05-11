<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DuAn;
use App\Models\GachHoaThongGioCt;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiHaiVanMieuCt;
use App\Models\TrangChu;

class HomeController extends Controller
{
    public function index()
    {
        $trangChu = TrangChu::first();

        $projects = DuAn::latest()->take(10)->get();

        $ngoiAmDuongs = NgoiAmDuongCt::where('is_delete', 0)
            ->latest()
            ->take(8)
            ->get();

        $ngoiHais = NgoiHaiVanMieuCt::where('is_delete', 0)
            ->latest()
            ->take(8)
            ->get();

        $gachHoas = GachHoaThongGioCt::where('is_delete', 0)
            ->latest()
            ->take(8)
            ->get();

        return view('clients.home.index', compact(
            'trangChu',
            'projects',
            'ngoiAmDuongs',
            'ngoiHais',
            'gachHoas'
        ));
    }
}
