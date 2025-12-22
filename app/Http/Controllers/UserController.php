<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function showHome() {
        if (Auth::check()) {

            $user = Auth::user();
            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role == 'seller') {
                return redirect()->route('seller.dashboard');
            }

        }

        return view('pages.user.home');
    }
}
