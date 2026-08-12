<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Bibliotecária
        User::create([
            'name' => 'Bibliotecária',
            'email' => 'bibliotecaria@example.com',
            'password' => bcrypt('password'),
            'role' => 'bibliotecaria',
            'email_verified_at' => now(),
        ]);

        // Professor
        User::create([
            'name' => 'Professor Silva',
            'email' => 'professor@example.com',
            'password' => bcrypt('password'),
            'role' => 'professor',
            'email_verified_at' => now(),
        ]);

        // Aluno
        User::create([
            'name' => 'Aluno João',
            'email' => 'aluno@example.com',
            'password' => bcrypt('password'),
            'role' => 'aluno',
            'email_verified_at' => now(),
        ]);

        // Criar alguns livros de teste
        Book::factory(10)->create();
    }
}
