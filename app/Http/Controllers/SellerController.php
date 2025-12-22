<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerController extends Controller
{
    function showDashboard() {
        return view('seller.dashboard');
    }
}
