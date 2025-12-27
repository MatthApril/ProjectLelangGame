<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangeShopNameRequest;
use App\Http\Requests\ChangeUsernameRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OpenShopRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // VIEW
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    public function showRegisterForm()
    {
        return view('pages.auth.register');
    }

    function showChangePassword() {
        return view('pages.auth.change_pwd');
    }

    function showOpenShop() {

        return view('pages.auth.open_shop');
    }

    // POST
    function showProfile() {
        $user = Auth::user();
        $param['user'] = $user;
        return view('pages.auth.profile', $param);
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
            'role' => 'user',
            'balance' => 0
        ]);

        Auth::login($user);
        return redirect()->route('verify.index');
    }

    function doOpenShop(OpenShopRequest $req) {
        $validated = $req->validated();

        $user = Auth::user();

        $imagePath = null;
        if ($req->hasFile('shop_img')) {
            $imagePath = $req->file('shop_img')->store("shops/{$user->user_id}", 'public');
        }

        $user->update([
            'role' => 'seller',
        ]);

        $user->shop()->create([
            'shop_name' => $validated['shop_name'],
            'shop_img' => $imagePath,
            'open_hour' => $validated['open_hour'],
            'close_hour' => $validated['close_hour'],
            'status' => 'open',
            'shop_rating' => 0,
            'running_transactions' => 0,
            'shop_balance' => 0
        ]);

        return redirect()->route('seller.dashboard')->with('success', 'Toko berhasil dibuat');
    }

    function changeUsername(ChangeUsernameRequest $req) {
        $req->validated();

        $user = Auth::user();
        $user->update([
            'username' => $req->username
        ]);

        return back()->with('success', 'Username berhasil diubah');
    }

    function changeShopName(ChangeShopNameRequest $req) {
        $req->validated();

        $user = Auth::user();
        $user->shop->update([
            'shop_name' => $req->shop_name
        ]);

        return back()->with('success', 'Nama Toko berhasil diubah');
    }

    function changePassword(ChangePasswordRequest $req) {
        $req->validated();
        $user = Auth::user();

        $user->update([
            'password' => $req->password
        ]);

        Verification::where('user_id', $user->user_id)
            ->where('type', 'reset_password')
            ->where('status', 'valid')
            ->update(['status' => 'invalid']);

        return redirect()->route('profile')->with('success', 'Password berhasil diubah');
    }

}
