<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function cart()
    {
        return view('clients.cart.gio-hang');
    }

    public function checkout()
    {
        return view('clients.cart.thanh-toan');
    }
}
