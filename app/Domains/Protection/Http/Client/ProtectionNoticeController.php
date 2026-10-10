<?php

namespace App\Domains\Protection\Http\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProtectionNoticeController extends Controller
{
    public function notice(): View
    {
        return view('clients.protection.notice');
    }

    public function warning(Request $request): View
    {
        return view('clients.protection.warning', [
            'recordId' => $request->session()->get(ViolationReportController::SESSION_KEY),
        ]);
    }
}
