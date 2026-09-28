<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Catalog\Services\CatalogQueryService;
use App\Domains\Content\Models\DuAn;
use App\Domains\Content\Models\TrangChu;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct(private readonly CatalogQueryService $catalogQuery) {}

    public function index()
    {
        $trangChu = TrangChu::first();

        $projects = DuAn::latest()->take(10)->get();

        $ngoiAmDuongs = $this->catalogQuery->forHome('ngoi_am_duong_ct', 8);

        $ngoiHais = $this->catalogQuery->forHome('ngoi_hai_van_mieu_ct', 8);

        $gachHoas = $this->catalogQuery->forHome('gach_hoa_thong_gio_ct', 8);

        return view('clients.home.index', compact(
            'trangChu',
            'projects',
            'ngoiAmDuongs',
            'ngoiHais',
            'gachHoas'
        ));
    }
}
