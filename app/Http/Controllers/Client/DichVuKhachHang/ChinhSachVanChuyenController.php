<?php

namespace App\Http\Controllers\Client\DichVuKhachHang;

use App\Http\Controllers\Controller;

class ChinhSachVanChuyenController extends Controller
{
    public function index()
    {
        return view('clients.dich-vu-khach-hang.chinh-sach-van-chuyen');
    }
}
