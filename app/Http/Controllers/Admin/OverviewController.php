<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OverviewController extends Controller
{
    public function index()
    {
        return view('content.admin.overview.index');
    }
}
