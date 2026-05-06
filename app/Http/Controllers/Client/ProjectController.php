<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function index()
    {
        return view('clients.projects.index');
    }

    public function detail($slug)
    {
        return view('clients.projects.detail', compact('slug'));
    }
}
