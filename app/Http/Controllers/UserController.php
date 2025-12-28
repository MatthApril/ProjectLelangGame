<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // VIEW
    function showHome() {
        $products = Product::all();
        $owners = User::where('role', 'seller')->get();
        $param['owners'] = $owners;
        $param['products'] = $products;
        return view('pages.user.home', $param);
    }
    
}
