<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HandleUserRegistered
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        // Log the user out immediately to prevent auto-login
        Auth::logout();

        // Invalidate session to ensure clean state
        Session::invalidate();
        Session::regenerateToken();
    }
}
