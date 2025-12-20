<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    function showLoginForm() {
        return view('auth.login');
    }

    function showRegisterForm() {
        return view('auth.register');
    }

    function doLogin(Request $req) {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ],
        [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        if (Auth::attempt($req->only('email', 'password'), $req->remember)) {

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if (Auth::user()->role === 'seller') {
                return redirect()->route('seller.dashboard');
            }

            return redirect()->route('user.home');
        }
    }

    function doRegister(Request $req) {
        // $req->validate([
        //     'username' => 'required|string',
        //     'email' => 'required|email',
        //     'password' => 'required|string',
        //     'confirm_password' => 'required|same:password',
        //     'role' => ['required', Rule::in(['user', 'seller'])]
        // ],
        // [
        //     'username.required' => 'Username wajib diisi',
        //     'email.required' => 'Email wajib diisi',
        //     'password.required' => 'Password wajib diisi',
        //     'confirm_password.required' => 'Konfirmasi Password wajib diisi',
        //     'confirm_password.same' => 'Konfirmasi Password dengan Password tidak sama',
        //     'role.required' => 'Role wajib dipilih',
        //     'role.' => 'Role tidak valid',
        // ]);

        $req['status'] = 'verify';
        dd($req->all());
        $account = User::create([
            'username' => $req->username,
            'password' => $req->password,
            'email' => $req->email,
            'role' => $req->role,
        ]);

    }

}
