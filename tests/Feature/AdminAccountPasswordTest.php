<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('shows the account password screen to authenticated users', function () {
    $user = User::factory()->create();
    $user->forceFill(['role' => 'admin'])->save();

    $response = $this->actingAs($user)->get(route('admin.account.edit'));

    $response->assertOk();
    $response->assertSee('Senha');
    $response->assertSee($user->email);
});

it('updates the authenticated user password', function () {
    $user = User::factory()->create([
        'password' => 'old-password',
        'role' => 'admin',
    ]);

    $response = $this->actingAs($user)->patch(route('admin.account.password.update'), [
        'current_password' => 'old-password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

it('requires the current password before changing it', function () {
    $user = User::factory()->create([
        'password' => 'old-password',
        'role' => 'admin',
    ]);

    $response = $this->actingAs($user)->from(route('admin.account.edit'))->patch(route('admin.account.password.update'), [
        'current_password' => 'wrong-password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect(route('admin.account.edit'));
    $response->assertSessionHasErrors('current_password');

    expect(Hash::check('old-password', $user->fresh()->password))->toBeTrue();
});
