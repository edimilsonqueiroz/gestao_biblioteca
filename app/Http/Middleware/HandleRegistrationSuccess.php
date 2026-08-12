<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HandleRegistrationSuccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if user just registered (either via session flag or if authenticated user is inactive)
        if (Auth::check() && Auth::user()->active === false && $request->session()->has('just_registered')) {
            // Clear the flag
            $request->session()->forget('just_registered');
            $request->session()->forget('just_registered_user_id');

            // Log out the user
            Auth::logout();

            // Redirect to success page
            return redirect()->route('register.success');
        }

        return $response;
    }
}
