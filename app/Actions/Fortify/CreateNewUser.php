<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $hasAdmin = User::where('role', 'admin')->exists();

        Validator::make($input, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => $this->passwordRules(),
            'role' => [
                'required',
                'string',
                Rule::in(['aluno', 'professor', 'bibliotecaria', 'admin']),
                $hasAdmin ? Rule::notIn(['admin']) : null,
            ],
        ])->validate();

        $role = $input['role'];
        if ($hasAdmin && $role === 'admin') {
            $role = 'aluno';
        }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'role' => $role,
            'active' => false,
        ]);
    }
}
