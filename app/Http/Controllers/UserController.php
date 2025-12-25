<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // VIEW
    function showHome() {
        $products = Product::all();
        $param['products'] = $products;
        return view('pages.user.home', $param);
    }
}
