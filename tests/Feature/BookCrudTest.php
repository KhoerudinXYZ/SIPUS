<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view book index page', function () {
    $admin = User::factory()->admin()->create();
    Book::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get('/books');

    $response->assertStatus(200);
});

test('user can view book index page', function () {
    $user = User::factory()->create();
    Book::factory()->count(3)->create();

    $response = $this->actingAs($user)->get('/books');

    $response->assertStatus(200);
});

test('admin can create a book', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post('/books', [
        'title' => 'Buku Test Baru',
        'author' => 'Penulis Test',
        'publisher' => 'Penerbit Test',
        'year' => 2024,
        'isbn' => '9781234567890',
        'stock' => 5,
    ]);

    $response->assertRedirect('/books');
    $this->assertDatabaseHas('books', [
        'title' => 'Buku Test Baru',
        'author' => 'Penulis Test',
        'stock' => 5,
    ]);
});

test('admin can update a book', function () {
    $admin = User::factory()->admin()->create();
    $book = Book::factory()->create(['title' => 'Judul Lama']);

    $response = $this->actingAs($admin)->put("/books/{$book->id}", [
        'title' => 'Judul Baru',
        'author' => $book->author,
        'publisher' => $book->publisher,
        'year' => $book->year,
        'stock' => $book->stock,
    ]);

    $response->assertRedirect('/books');
    $this->assertDatabaseHas('books', [
        'id' => $book->id,
        'title' => 'Judul Baru',
    ]);
});

test('admin can delete a book', function () {
    $admin = User::factory()->admin()->create();
    $book = Book::factory()->create();

    $response = $this->actingAs($admin)->delete("/books/{$book->id}");

    $response->assertRedirect('/books');
    $this->assertDatabaseMissing('books', ['id' => $book->id]);
});

test('regular user cannot create a book', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/books', [
        'title' => 'Buku Test',
        'author' => 'Penulis Test',
        'publisher' => 'Penerbit Test',
        'year' => 2024,
        'stock' => 5,
    ]);

    $response->assertForbidden();
});

test('books can be searched', function () {
    $admin = User::factory()->admin()->create();
    Book::factory()->create(['title' => 'Laskar Pelangi']);
    Book::factory()->create(['title' => 'Filosofi Teras']);

    $response = $this->actingAs($admin)->get('/books?search=Laskar');

    $response->assertStatus(200);
    $response->assertSee('Laskar Pelangi');
});
