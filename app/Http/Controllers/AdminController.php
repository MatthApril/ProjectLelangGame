<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // VIEW
    function showDashboard() {
        return view('pages.admin.dashboard');
    }
}
