<?php

namespace App\Http\Controllers;

class MainPageController extends Controller
{
    public function show()
    {
        return view('content.main.index');
    }
}
