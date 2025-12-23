<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerController extends Controller
{
    // VIEW
    function showDashboard() {
        return view('pages.seller.dashboard');
    }
}
