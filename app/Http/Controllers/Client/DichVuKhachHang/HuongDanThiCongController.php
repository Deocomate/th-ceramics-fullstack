<?php

namespace App\Http\Controllers\Client\DichVuKhachHang;

use App\Http\Controllers\Controller;
use App\Models\ThiCong;

class HuongDanThiCongController extends Controller
{
    public function index()
    {
        $guides = ThiCong::query()->latest()->get();

        return view('clients.dich-vu-khach-hang.huong-dan-thi-cong', compact('guides'));
    }
}
