<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view users list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('users.index'));

    $response->assertStatus(200);
    $response->assertViewIs('users.index');
    $response->assertViewHas('users');
});

test('user cannot view users list', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get(route('users.index'));

    $response->assertStatus(403);
});

test('admin can create user', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('users.store'), [
        'name' => 'Budi Baru',
        'email' => 'budi.baru@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'user',
    ]);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', [
        'email' => 'budi.baru@example.com',
        'role' => 'user',
    ]);
});

test('admin can update user', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['name' => 'Lama', 'email' => 'lama@example.com', 'role' => 'user']);

    $response = $this->actingAs($admin)->put(route('users.update', $user), [
        'name' => 'Baru Update',
        'email' => 'baru@example.com',
        'role' => 'admin',
    ]);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Baru Update',
        'email' => 'baru@example.com',
        'role' => 'admin',
    ]);
});

test('admin can delete user', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create();

    $response = $this->actingAs($admin)->delete(route('users.destroy', $user));

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
