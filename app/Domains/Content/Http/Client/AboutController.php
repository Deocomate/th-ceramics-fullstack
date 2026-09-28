<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Models\GiaiThuongThanhTuu;
use App\Domains\Content\Models\VeChungToi;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function index()
    {
        return view('clients.about.index', [
            'about' => VeChungToi::first(),
            'giaiThuongThanhTuu' => GiaiThuongThanhTuu::latest()->get(),
        ]);
    }
}
