<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // VIEW
    function showHome() {
        return view('pages.user.home');
    }
}
