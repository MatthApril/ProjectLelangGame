<?php

namespace App\Http\Middleware;

use App\Models\Verification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role == 'admin') {
            return $next($request);
        }

        $verify = Verification::whereUserId($request->user()->user_id)
                    ->whereStatus('valid')
                    ->whereType('register')->first();
        if ($verify) {
            return $next($request);
        }

        return redirect()->route('verify.index');
    }
}
