<?php

namespace App\Domains\Identity\Http\Admin;

use App\Domains\Identity\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = User::customers()
            ->latest()
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }
}
