<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view categories list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Category::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('categories.index'));

    $response->assertStatus(200);
    $response->assertViewIs('categories.index');
    $response->assertViewHas('categories');
});

test('user cannot view categories list', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get(route('categories.index'));

    $response->assertStatus(403);
});

test('admin can create category', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('categories.store'), [
        'name' => 'Teknologi',
        'description' => 'Buku seputar teknologi',
    ]);

    $response->assertRedirect(route('categories.index'));
    $this->assertDatabaseHas('categories', [
        'name' => 'Teknologi',
        'slug' => 'teknologi',
    ]);
});

test('admin can update category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create(['name' => 'Lama']);

    $response = $this->actingAs($admin)->put(route('categories.update', $category), [
        'name' => 'Baru',
        'description' => 'Deskripsi baru',
    ]);

    $response->assertRedirect(route('categories.index'));
    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Baru',
        'slug' => 'baru',
    ]);
});

test('admin can delete category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create();

    $response = $this->actingAs($admin)->delete(route('categories.destroy', $category));

    $response->assertRedirect(route('categories.index'));
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});
