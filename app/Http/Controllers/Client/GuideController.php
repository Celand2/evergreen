<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class GuideController extends Controller
{
    public function index()
    {
        return view('client.guide');
    }
}
