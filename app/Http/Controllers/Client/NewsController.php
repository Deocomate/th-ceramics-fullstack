<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function index()
    {
        return view('clients.news.index');
    }

    public function detail($slug)
    {
        return view('clients.news.detail', compact('slug'));
    }
}
