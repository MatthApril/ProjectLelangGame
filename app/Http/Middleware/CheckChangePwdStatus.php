<?php

namespace App\Http\Middleware;

use App\Models\Verification;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckChangePwdStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $verify = Verification::whereUserId($request->user()->user_id)
                    ->whereStatus('valid')
                    ->whereType('reset_password')
                    ->latest()
                    ->first();
        if (now()->lessThan($verify->expires_at)) {
            return $next($request);
        }

        return redirect()->back()->with('error', 'Unauthorized access.');
    }
}
