<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    function showLoginForm() {
        return view('auth.login');
    }

    function showRegisterForm() {
        return view('auth.register');
    }

    function doLogin(Request $req) {

    }

    function doRegister(Request $req) {

    }

}
