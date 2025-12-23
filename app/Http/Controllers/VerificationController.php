<?php

namespace App\Http\Controllers;

use App\Mail\EmailChangedWarning;
use App\Mail\OtpChangeEmail;
use App\Mail\OtpChangePwd;
use App\Mail\OtpEmail;
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

    function show(Request $req, $unique_id) {
        $verify = Verification::whereUserId(Auth::user()->user_id)->whereUniqueId($unique_id)
                    ->whereStatus('active')->count();
        if (!$verify) abort(404);

        $param['unique_id'] = $unique_id;
        $param['type'] = $req->type;
        return view('pages.verification.show', $param);
    }

    // POST
    function update(Request $req, $unique_id) {
        $verify = Verification::whereUserId(Auth::user()->user_id)->where('unique_id', $unique_id)
                    ->where('status', 'active')->first();

        if (!$verify) abort(404);

        if (md5($req->otp) != $verify->otp || now()->greaterThan($verify->expires_at)) {
            $verify->update(['status' => 'invalid']);

            if ($verify->type == 'register') {
                Auth::logout();

                return redirect()->route('register')->with('error', 'OTP tidak valid atau sudah kadaluarsa. Silakan daftar ulang.');
            }

            return redirect()->route('profile')->with('error', 'OTP tidak valid atau sudah kadaluarsa. Silakan coba lagi.');
        }

        if ($verify->type == 'reset_password') {
            $verify->update(['status' => 'valid']);
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
            return redirect()->route('profile')->with('success', 'Email berhasil diubah');
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
            'expires_at' => now()->addMinute()
        ]);

        if ($req->type == 'register') {
            Mail::to($user->email)->queue(new OtpEmail($otp));
            return redirect()->route('verify.update-uid', ['unique_id' => $verify->unique_id, 'type' => $req->type]);
        }

        if ($req->type == 'reset_password') {
            Mail::to($user->email)->queue(new OtpChangePwd($otp));
            return redirect()->route('verify.update-uid', ['unique_id' => $verify->unique_id, 'type' => $req->type]);
        }

        if ($req->type == 'change_email') {
            $req->validate([
                'email' => ['required', 'email', new EmailRegisteredRule]
            ], [
                'email.required' => 'Email baru wajib diisi',
                'email.email' => 'Format email tidak valid',
            ]);

            session(['new_email' => $req->email]);

            Mail::to($req->email)->queue(new OtpChangeEmail($otp));
            return redirect()->route('verify.update-uid', ['unique_id' => $verify->unique_id, 'type' => $req->type]);
        }
    }

}
