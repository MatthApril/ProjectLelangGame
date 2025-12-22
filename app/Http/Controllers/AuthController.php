<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Rules\EmailRegisteredRule;
use App\Rules\UsernameExistRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    public function showRegisterForm()
    {
        return view('pages.auth.register');
    }

    public function logout(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect()->route('user.home');
    }

    public function doLogin(LoginRequest $req)
    {
        $req->validated();

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

    public function doRegister(RegisterRequest $req)
    {
        $req->validated();

        $req['status'] = 'verify';
        $user = User::create([
            'username' => $req->username,
            'password' => $req->password,
            'email' => $req->email,
            'role' => $req->role,
        ]);

        Auth::login($user);
        return redirect()->route('verify.index');
    }

    function showProfile() {
        $user = Auth::user();
        $param['user'] = $user;
        return view('pages.auth.profile', $param);
    }

    function showChangeEmail() {
        return view();
    }

    function showChangePassword() {
        return view();
    }

    function changeEmail(Request $req) {

    }

    function changePassword(Request $req) {

    }

}
