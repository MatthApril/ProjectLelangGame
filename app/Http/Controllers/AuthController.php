<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeShopNameRequest;
use App\Http\Requests\ChangeUsernameRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OpenShopRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $shop = null;
        return view('pages.auth.open_shop',compact('shop'));
    }

    function showEditShop() {
        $shop = Auth::user()->shop;

        if (!$shop) {
            return redirect()->route('profile')->with('error', 'Anda belum memiliki toko');
        }

        return view('pages.auth.open_shop', compact('shop'));
    }

    function showProfile() {
        $user = Auth::user();
        $categories = Category::orderBy('category_name')->get();
        $param['user'] = $user;
        $param['categories'] = $categories;

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
        if (Auth::attempt($req->only('email', 'password'), $req->filled('remember'))) {

            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if (Auth::user()->role == 'seller') {
                return redirect()->route('seller.dashboard');
            }

            return redirect()->route('user.home');
        }

        return back()->with('error', 'Email Atau Password Salah!');
    }

    public function doRegister(RegisterRequest $req)
    {
        $req->validated();

        $req['status'] = 'verify';
        $user = User::create([
            'username' => $req->username,
            'password' => bcrypt($req->password),
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

        return redirect()->route('seller.dashboard')->with('success', 'Toko Berhasil Dibuat!');
    }

    function doUpdateShop(UpdateShopRequest $req) {
        $validated = $req->validated();

        $shop = Auth::user()->shop;

        if (!$shop) {
            return redirect()->route('profile')->with('error', 'Anda belum memiliki toko');
        }

        if ($req->hasFile('shop_img')) {
            if ($shop->shop_img) {
                Storage::disk('public')->delete($shop->shop_img);
            }

            $userId = Auth::user()->user_id;
            $validated['shop_img'] = $req->file('shop_img')->store("shops/{$userId}", 'public');
        }

        $shop->update([
            'shop_name' => $validated['shop_name'],
            'shop_img' => $validated['shop_img'] ?? $shop->shop_img,
            'open_hour' => $validated['open_hour'],
            'close_hour' => $validated['close_hour'],
        ]);

        return redirect()->route('profile')->with('success', 'Toko berhasil diupdate!');
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

}
