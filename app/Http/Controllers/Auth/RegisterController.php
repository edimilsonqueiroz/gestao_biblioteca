<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;

class RegisterController implements RegisterResponse
{
    use \Illuminate\Foundation\Validation\ValidatesRequests;

    /**
     * Handle a registration request for the application.
     */
    public function __invoke(Request $request, CreateNewUser $creator)
    {
        // Create the user using Fortify's action
        $user = $creator->create($request->all());

        // Do NOT log the user in - they will be redirected to success page
        // The user is created but must be activated by admin

        return redirect()->route('register.success');
    }

    /**
     * Get the response for a successful user registration.
     */
    public function toResponse($request)
    {
        return redirect()->route('register.success');
    }
}
