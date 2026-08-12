<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectUnactivatedUsers extends Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // If user was just registered (session has a newly_registered flag), redirect to success page
        if ($request->session()->has('newly_registered')) {
            Auth::logout();
            $request->session()->forget('newly_registered');

            return redirect()->route('register.success');
        }

        return $response;
    }
}
