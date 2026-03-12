<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects back with error on invalid credentials', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->from('/login')->post('/login', [
        'email' => 'john@example.com',
        'password' => 'wrong',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('auth');
});

it('returns JSON 401 on invalid credentials for ajax', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->postJson('/login', [
        'email' => 'john@example.com',
        'password' => 'wrong',
    ]);

    $response->assertStatus(401);
    $response->assertJson([
        'message' => 'Invalid credentials.',
    ]);
});
