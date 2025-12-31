<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Verification;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckForgotPwdStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('forgot_pwd_email')) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $user = User::where('email', session('forgot_pwd_email'))->first();

        $verify = Verification::whereUserId($user->user_id)
            ->whereStatus('valid')
            ->whereType('forgot_password')
            ->latest()
            ->first();

        // dd($verify->expires_at);
        if (now()->lessThanOrEqualTo($verify->expires_at)) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
}
