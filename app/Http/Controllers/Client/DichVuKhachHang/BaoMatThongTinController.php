<?php

namespace App\Http\Controllers\Client\DichVuKhachHang;

use App\Http\Controllers\Controller;

class BaoMatThongTinController extends Controller
{
    public function index()
    {
        return view('clients.dich-vu-khach-hang.bao-mat-thong-tin');
    }
}
