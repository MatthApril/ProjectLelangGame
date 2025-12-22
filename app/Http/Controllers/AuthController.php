<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SellerRegisterRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    public function showSellerRegisterForm()
    {
        return view('pages.auth.seller_register');
    }

    public function showUserRegisterForm()
    {
        return view('pages.auth.user_register');
    }

    function showChangeEmail() {
        return view();
    }

    function showChangePassword() {
        return view();
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

    public function doUserRegister(UserRegisterRequest $req)
    {
        $req->validated();

        $req['status'] = 'verify';
        $user = User::create([
            'username' => $req->username,
            'password' => $req->password,
            'email' => $req->email,
            'role' => 'user',
        ]);

        Auth::login($user);
        return redirect()->route('verify.index');
    }

    public function doSellerRegister(SellerRegisterRequest $req)
    {
        $req->validated();

        $req['status'] = 'verify';
        $user = User::create([
            'username' => $req->username,
            'password' => $req->password,
            'email' => $req->email,
            'role' => 'seller',
        ]);

        $user->shop()->create([
            'shop_name'   => $req->shop_name,
            'shop_rating' => 0,
        ]);

        Auth::login($user);
        return redirect()->route('verify.index');
    }

    function showProfile() {
        $user = Auth::user();
        $param['user'] = $user;
        return view('pages.auth.profile', $param);
    }

    function changeEmail(Request $req) {

    }

    function changePassword(Request $req) {

    }

}
