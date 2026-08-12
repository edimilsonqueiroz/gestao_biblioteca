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

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => $this->passwordRules(),
            'role' => [
                'required',
                'string',
                Rule::in(['aluno', 'professor', 'bibliotecaria', 'admin']),
                $hasAdmin ? Rule::notIn(['admin']) : null,
            ],
        ];

        // Adicionar validações condicionais para alunos
        if (($input['role'] ?? null) === 'aluno') {
            $rules['grade'] = 'required|in:1,2,3';
            $rules['class'] = 'required|string|max:1';
        }

        Validator::make($input, $rules)->validate();

        $role = $input['role'];
        if ($hasAdmin && $role === 'admin') {
            $role = 'aluno';
        }

        $userData = [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'role' => $role,
            'active' => $role === 'admin',
        ];

        // Adicionar grade e class se for aluno
        if ($role === 'aluno') {
            $userData['grade'] = $input['grade'] ?? null;
            $userData['class'] = $input['class'] ?? null;
        }

        return User::create($userData);
    }
}
