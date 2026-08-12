<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        return Inertia::render('Admin/Users/Index', [
            'users' => $query->get(),
            'search' => $search,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,bibliotecaria,professor,aluno',
        ];

        // Adicionar validações condicionais para alunos
        if ($request->input('role') === 'aluno') {
            $rules['grade'] = 'required|in:1,2,3';
            $rules['class'] = 'required|string|max:1';
        }

        $validated = $request->validate($rules);

        $userData = [
            ...$validated,
            'password' => bcrypt($validated['password']),
        ];

        // Remover password da cópia antes de passar para create
        unset($userData['password']);

        User::create([
            ...$userData,
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,bibliotecaria,professor,aluno',
        ];

        // Adicionar validações condicionais para alunos
        if ($request->input('role') === 'aluno') {
            $rules['grade'] = 'required|in:1,2,3';
            $rules['class'] = 'required|string|max:1';
        }

        $validated = $request->validate($rules);

        $user->update($validated);

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function toggleActive(User $user)
    {
        if ($user->role === 'admin') {
            return back();
        }

        $user->update(['active' => ! $user->active]);

        return back();
    }
}
