<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Models\DuAn;
use App\Domains\Content\Models\TrangChu;
use App\Http\Controllers\Controller;
use App\Models\GachHoaThongGioCt;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiHaiVanMieuCt;

class HomeController extends Controller
{
    public function index()
    {
        $trangChu = TrangChu::first();

        $projects = DuAn::latest()->take(10)->get();

        $ngoiAmDuongs = NgoiAmDuongCt::where('is_delete', 0)
            ->orderedByPriority()
            ->take(8)
            ->get();

        $ngoiHais = NgoiHaiVanMieuCt::with(['mauSacs' => function ($query) {
            $query->where('is_delete', 0);
        }])
            ->where('is_delete', 0)
            ->orderedByPriority()
            ->take(8)
            ->get();

        $gachHoas = GachHoaThongGioCt::where('is_delete', 0)
            ->orderedByPriority()
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
