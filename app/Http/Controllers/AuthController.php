<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function showLoginForm() {
        return view('pages.auth.login');
    }

    function showRegisterForm() {
        return view('pages.auth.register');
    }

    function logout(Request $req) {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect()->route('user.home');
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

        if (Auth::attempt($req->only('email', 'password'), $req->filled('remember'))) {

            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if (Auth::user()->role == 'seller') {
                return redirect()->route('seller.dashboard');
            }

            return redirect()->route('user.home');
        }

        return back()->with('error', 'Email atau Password salah');
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
        $user = User::create([
            'username' => $req->username,
            'password' => $req->password,
            'email' => $req->email,
            'role' => $req->role,
        ]);

        Auth::login($user);
        if ($req->role == 'seller') {
            return redirect()->route('seller.dashboard');
        }

        return redirect()->route('verify.index');
    }

}
