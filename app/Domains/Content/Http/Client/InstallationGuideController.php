<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Infrastructure\Models\InstallationGuide;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class InstallationGuideController extends Controller
{
    public function index(): View
    {
        $guides = InstallationGuide::query()->orderBy('thi_cong')->get();

        return view('clients.content.dich-vu-khach-hang.huong-dan-thi-cong', compact('guides'));
    }
}
