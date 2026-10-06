<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Infrastructure\Models\AboutPageConfig;
use App\Domains\Content\Infrastructure\Models\AwardAchievement;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('clients.content.about.index', [
            'about' => AboutPageConfig::first(),
            'giaiThuongThanhTuu' => AwardAchievement::latest()->get(),
        ]);
    }
}
