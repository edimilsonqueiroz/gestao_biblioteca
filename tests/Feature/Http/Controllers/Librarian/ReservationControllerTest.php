<?php

use App\Models\Reservation;
use App\Models\User;

test('librarian can view reservations index', function () {
    $librarian = User::factory()->create(['role' => 'bibliotecaria']);

    $response = $this->actingAs($librarian)
        ->get(route('librarian.reservations.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Librarian/Reservations/Index')
        ->has('reservations')
    );
});

test('librarian can see reservations with users and books', function () {
    $librarian = User::factory()->create(['role' => 'bibliotecaria']);
    Reservation::factory(3)->create();

    $response = $this->actingAs($librarian)
        ->get(route('librarian.reservations.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Librarian/Reservations/Index')
        ->has('reservations.data', 3)
    );
});

test('non-librarian cannot access reservations page', function () {
    $student = User::factory()->create(['role' => 'aluno']);

    $response = $this->actingAs($student)
        ->get(route('librarian.reservations.index'));

    $response->assertForbidden();
});
