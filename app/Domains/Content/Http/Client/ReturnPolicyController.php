<?php

namespace App\Domains\Content\Http\Client;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ReturnPolicyController extends Controller
{
    public function index(): View
    {
        return view('clients.content.dich-vu-khach-hang.chinh-sach-doi-tra');
    }
}
