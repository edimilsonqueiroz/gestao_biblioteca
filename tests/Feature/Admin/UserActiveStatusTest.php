<?php

use App\Models\User;

test('administrador pode ativar e inativar usuários', function () {
    // Criar usuários
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'aluno', 'active' => true]);

    // Fazer login como admin
    $this->actingAs($admin);

    // Verificar que o usuário está ativo
    expect($user->fresh()->active)->toBe(true);

    // Desativar o usuário
    $response = $this->post(route('admin.users.toggleActive', $user));
    $response->assertRedirect(route('admin.users.show', $user));

    // Verificar que foi desativado
    expect($user->fresh()->active)->toBe(false);

    // Ativar o usuário novamente
    $response = $this->post(route('admin.users.toggleActive', $user));
    $response->assertRedirect(route('admin.users.show', $user));

    // Verificar que está ativo novamente
    expect($user->fresh()->active)->toBe(true);
});

test('usuário inativo não consegue fazer login', function () {
    $user = User::factory()->create(['role' => 'aluno', 'active' => false]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    // Após uma requisição, ele será redirecionado se o middleware funcionar
    // Vamos fazer outra requisição para verificar se foi deslogado
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('usuário ativo consegue fazer login', function () {
    $user = User::factory()->create(['role' => 'aluno', 'active' => true]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
});
