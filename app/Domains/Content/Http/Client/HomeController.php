<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Catalog\Application\Ports\CatalogQueryPort;
use App\Domains\Commerce\Infrastructure\Models\Coupon;
use App\Domains\Content\Infrastructure\Models\AwardAchievement;
use App\Domains\Content\Infrastructure\Models\HomePageConfig;
use App\Domains\Content\Infrastructure\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly CatalogQueryPort $catalogQuery) {}

    public function index(): View
    {
        $trangChu = HomePageConfig::first();

        $projects = Project::latest()->take(10)->get();

        $ngoiAmDuongs = $this->catalogQuery->forHome('ngoi_am_duong_ct', 8);

        $ngoiHais = $this->catalogQuery->forHome('ngoi_hai_van_mieu_ct', 8);

        $gachHoas = $this->catalogQuery->forHome('gach_hoa_thong_gio_ct', 8);

        $bannerCoupons = Coupon::query()
            ->where('show_banner', true)
            ->where('is_active', true)
            ->where('is_delete', 0)
            ->where('start_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $awards = AwardAchievement::latest()->get();

        return view('clients.content.home.index', compact(
            'trangChu',
            'projects',
            'ngoiAmDuongs',
            'ngoiHais',
            'gachHoas',
            'bannerCoupons',
            'awards'
        ));
    }
}
