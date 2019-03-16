<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class OverviewController extends Controller
{
    public function index()
    {
//        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
//            abort(401);
//        }

        return view('content.admin.overview.index');
    }
}
