<?php

namespace App\Http\Controllers;

use App\Mail\OtpEmail;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    function index() {
        return view('pages.verification.index');
    }

    function show($unique_id) {
        $verify = Verification::whereUserId(Auth::user()->user_id)->whereUniqueId($unique_id)
                    ->whereStatus('active')->count();
        if (!$verify) abort(404);
        return view('pages.verification.show', compact('unique_id'));
    }

    function update(Request $req, $unique_id) {
        $verify = Verification::whereUserId(Auth::user()->user_id)->where('unique_id', $unique_id)
                    ->where('status', 'active')->first();

        if (!$verify) abort(404);

        if (md5($req->otp) != $verify->otp || now()->greaterThan($verify->expires_at)) {
            $verify->update(['status' => 'invalid']);
            return redirect()->route('verify.index')->with('login_failed', 'Gagal Verifikasi');
        }

        $verify->update(['status' => 'valid']);
        User::find($verify->user_id)->update(['status' => 'active']);

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

        // dd($otp);

        if ($req->type == 'register') {
            Mail::to($user->email)->queue(new OtpEmail($otp));
            return redirect()->route('verify.update-uid', ['unique_id' => $verify->unique_id]);
        } else if ($req->type == 'reset_password') {
            Mail::to($user->email)->queue(new OtpEmail($otp));
            return redirect()->route('verify.update-uid', ['unique_id' => $verify->unique_id]);
        } else {

        }
    }

}
