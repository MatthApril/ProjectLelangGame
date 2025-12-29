<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPwdRequest;
use App\Mail\EmailChangedWarning;
use App\Mail\OtpChangeEmail;
use App\Mail\OtpChangePwd;
use App\Mail\OtpEmail;
use App\Mail\OtpForgotPwd;
use App\Mail\PasswordChangedWarning;
use App\Models\User;
use App\Models\Verification;
use App\Rules\EmailRegisteredRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    // VIEW
    function index() {
        return view('pages.verification.index');
    }

    function showForgotPwdEmailForm() {
        return view('pages.auth.forgot_pwd_email');
    }

    function showForgotPwdForm() {
        return view('pages.auth.forgot_pwd');
    }

    function show(Request $req, $unique_id) {

        $verify = Verification::whereUserId(Auth::user()->user_id)->whereUniqueId($unique_id)
                    ->whereStatus('active')->count();

        // if (!$verify) abort(404);

        $param['unique_id'] = $unique_id;
        $param['type'] = $req->type;
        return view('pages.verification.show', $param);
    }

    // POST
    function update(Request $req, $unique_id) {
        $verify = Verification::whereUserId(Auth::user()->user_id)->where('unique_id', $unique_id)
                    ->where('status', 'active')->first();

        // if (!$verify) abort(404);
            if (!$verify) {
        return redirect()->route('profile')->with('error', 'Verifikasi tidak ditemukan atau sudah tidak valid.');
    }

        if (md5($req->otp) != $verify->otp || now()->greaterThan($verify->expires_at)) {
            $verify->update(['status' => 'invalid']);

            if ($verify->type == 'register') {
                Auth::logout();

                return redirect()->route('register')->with('error', 'OTP Tidak Valid Atau Sudah Kadaluarsa. Silakan Daftar Ulang!');
            }

            return redirect()->route('profile')->with('error', 'OTP Tidak Valid Atau Sudah Kadaluarsa. Silakan Coba Lagi.');
        }

        if ($verify->type == 'reset_password') {
            $verify->update([
                'status' => 'valid',
                'expires_at' => now()->addMinutes(5)
            ]);
            return redirect()->route('change-pwd-view');
        }

        if ($verify->type == 'change_email') {
            $verify->update(['status' => 'valid']);
            Mail::to(Auth::user()->email)->send(new EmailChangedWarning());
            $new_email = session('new_email');
            User::find($verify->user_id)->update(['email' => $new_email]);
            session()->forget('new_email');
            Verification::whereUserId($verify->user_id)
                ->whereType('change_email')
                ->whereStatus('active')
                ->update(['status' => 'invalid']);
            return redirect()->route('profile')->with('success', 'Email Berhasil Diubah!');
        }

        if ($verify->type == 'register') {
            $verify->update(['status' => 'valid']);
            User::find($verify->user_id)->update(['status' => 'active']);
        }

        return redirect()->route('user.home');

    }

    function store(Request $req) {
        $user = User::find($req->user()->user_id);

        if (!$user) return back();

        $otp = rand(100000, 999999);
        $verify = Verification::create([
            'user_id' => $user->user_id,
            'unique_id' => uniqid(),
            'otp' => md5($otp),
            'type' => $req->type,
            'expires_at' => now()->addMinutes(5)
        ]);

        if ($req->type == 'register') {
            Mail::to($user->email)->queue(new OtpEmail($otp));
            return redirect()->route('verify.uid', ['unique_id' => $verify->unique_id, 'type' => $req->type]);
        }

        if ($req->type == 'reset_password') {
            Mail::to($user->email)->queue(new OtpChangePwd($otp));
            return redirect()->route('verify.update-uid', ['unique_id' => $verify->unique_id, 'type' => $req->type]);
        }

        if ($req->type == 'change_email') {
            $req->validate([
                'email' => ['required', 'email', new EmailRegisteredRule]
            ], [
                'email.required' => 'Email Baru Wajib Diisi!',
                'email.email' => 'Format Email Tidak Valid!',
            ]);

            session(['new_email' => $req->email]);

            Mail::to($req->email)->queue(new OtpChangeEmail($otp));
            return redirect()->route('verify.update-uid', ['unique_id' => $verify->unique_id, 'type' => $req->type]);
        }
    }

    function sendResetPwdOtp(ForgotPwdRequest $req) {
        $req->validated();

        session(['forgot_pwd_email' => $req->email]);

        $user = User::where('email', $req->email)->first();
        if (!$user) {
            return back()->with('error', 'Email Tidak Terdaftar!');
        }

        $otp = rand(100000, 999999);
        $verify = Verification::create([
            'user_id' => $user->user_id,
            'unique_id' => uniqid(),
            'otp' => md5($otp),
            'type' => 'forgot_password',
            'expires_at' => now()->addMinutes(5)
        ]);

        Mail::to($user->email)->queue(new OtpForgotPwd($otp));
        return redirect()->route('forgot-pwd.verify.uid', ['unique_id' => $verify->unique_id, 'type' => 'forgot_password']);
    }

    function resendOTP() {
        $user = User::where('email', session('forgot_pwd_email'))->first();
        if (!$user) {
            return back()->with('error', 'Email Tidak Terdaftar!');
        }

        $otp = rand(100000, 999999);
        $verify = Verification::create([
            'user_id' => $user->user_id,
            'unique_id' => uniqid(),
            'otp' => md5($otp),
            'type' => 'forgot_password',
            'expires_at' => now()->addMinute()
        ]);

        Mail::to($user->email)->queue(new OtpForgotPwd($otp));
        return redirect()->route('forgot-pwd.verify.uid', ['unique_id' => $verify->unique_id, 'type' => 'forgot_password']);
    }

    function forgotPwdShow(Request $req, $unique_id) {

        $verify = Verification::whereUserId(User::where('email', session('forgot_pwd_email'))->first()->user_id)
                    ->whereUniqueId($unique_id)
                    ->whereStatus('active')->count();

        // if (!$verify) abort(404);

        $param['unique_id'] = $unique_id;
        $param['type'] = $req->type;
        return view('pages.verification.verify_forgot_pwd', $param);
    }

    function forgotPwdUpdate(Request $req, $unique_id) {
        $verify = Verification::whereUserId(User::where('email', session('forgot_pwd_email'))->first()->user_id)
                    ->where('unique_id', $unique_id)
                    ->where('status', 'active')->first();

        // if (!$verify) abort(404);

        if (md5($req->otp) != $verify->otp || now()->greaterThan($verify->expires_at)) {
            $verify->update(['status' => 'invalid']);

            return redirect()->route('forgot-pwd-email-view')->with('error', 'OTP Tidak Valid Atau Sudah Kadaluarsa. Silakan Coba Lagi.');
        }

        $verify->update(
            [
                'status' => 'valid',
                'expires_at' => now()->addMinutes(5)
            ]
        );
        return redirect()->route('forgot-pwd-view');
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

        Mail::to($user->email)->queue(new PasswordChangedWarning());
        return redirect()->route('profile')->with('success', 'Password berhasil diubah');
    }

    function changeForgotPassword(ChangePasswordRequest $req) {
        $req->validated();
        $user = User::whereEmail(session('forgot_pwd_email'))->first();

        $user->update([
            'password' => bcrypt($req->password)
        ]);

        Verification::where('user_id', $user->user_id)
            ->where('type', 'forgot_password')
            ->where('status', 'valid')
            ->update(['status' => 'invalid']);

        session()->forget('forgot_pwd_user_id');

        Mail::to($user->email)->queue(new PasswordChangedWarning());
        return redirect()->route('login')->with('success', 'Password berhasil diubah');
    }

}
