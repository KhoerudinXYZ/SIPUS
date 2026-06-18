<?php

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can borrow a book with available stock', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create(['stock' => 5]);

    $response = $this->actingAs($user)->post('/borrowings', [
        'book_id' => $book->id,
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertDatabaseHas('borrowings', [
        'user_id' => $user->id,
        'book_id' => $book->id,
        'status' => 'reserved',
    ]);
    $this->assertEquals(4, $book->fresh()->stock);
});

test('user cannot borrow a book with zero stock', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create(['stock' => 0]);

    $response = $this->actingAs($user)->post('/borrowings', [
        'book_id' => $book->id,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('borrowings', [
        'user_id' => $user->id,
        'book_id' => $book->id,
    ]);
});

test('user cannot borrow the same book twice without returning', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create(['stock' => 5]);

    // First borrow
    $this->actingAs($user)->post('/borrowings', ['book_id' => $book->id]);

    // Second attempt
    $response = $this->actingAs($user)->post('/borrowings', ['book_id' => $book->id]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertEquals(1, Borrowing::where('user_id', $user->id)->where('book_id', $book->id)->count());
});

test('admin can return a borrowed book', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $book = Book::factory()->create(['stock' => 4]);

    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'borrow_date' => now()->subDays(3),
        'return_date' => now()->addDays(4),
        'status' => 'borrowed',
        'fine' => 0,
    ]);

    $response = $this->actingAs($admin)->post("/borrowings/{$borrowing->id}/return");

    $response->assertRedirect();
    $this->assertEquals('returned', $borrowing->fresh()->status);
    $this->assertNotNull($borrowing->fresh()->actual_return_date);
    $this->assertEquals(5, $book->fresh()->stock);
});

test('overdue return calculates fine correctly', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $book = Book::factory()->create(['stock' => 4]);

    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'borrow_date' => now()->subDays(10),
        'return_date' => now()->subDays(3), // 3 days overdue
        'status' => 'borrowed',
        'fine' => 0,
    ]);

    $response = $this->actingAs($admin)->post("/borrowings/{$borrowing->id}/return");

    $response->assertRedirect();
    $fresh = $borrowing->fresh();
    $this->assertEquals('returned', $fresh->status);
    $this->assertEquals(3000, $fresh->fine); // 3 days * 1000
});

test('user can view borrowings index', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/borrowings');

    $response->assertStatus(200);
});

test('admin can view borrowings index', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/borrowings');

    $response->assertStatus(200);
});

test('user can request return of their borrowing', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();
    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'borrow_date' => now()->subDays(10),
        'return_date' => now()->subDays(3), // overdue by 3 days
        'status' => 'borrowed',
        'fine' => 0,
    ]);

    $response = $this->actingAs($user)->post("/borrowings/{$borrowing->id}/request-return");

    $response->assertRedirect();
    $fresh = $borrowing->fresh();
    $this->assertEquals('returning', $fresh->status);
    $this->assertEquals(3000, $fresh->fine); // fine calculated & locked
    $this->assertNotNull($fresh->actual_return_date);
});

test('user cannot request return of another users borrowing', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $book = Book::factory()->create();
    $borrowing = Borrowing::create([
        'user_id' => $user1->id,
        'book_id' => $book->id,
        'borrow_date' => now()->subDays(5),
        'return_date' => now()->addDays(2),
        'status' => 'borrowed',
        'fine' => 0,
    ]);

    $response = $this->actingAs($user2)->post("/borrowings/{$borrowing->id}/request-return");

    $response->assertStatus(403);
    $this->assertEquals('borrowed', $borrowing->fresh()->status);
});

test('admin can confirm a returning borrowing', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $book = Book::factory()->create(['stock' => 4]);
    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'borrow_date' => now()->subDays(10),
        'return_date' => now()->subDays(3),
        'actual_return_date' => now()->subDays(3),
        'status' => 'returning',
        'fine' => 3000,
    ]);

    $response = $this->actingAs($admin)->post("/borrowings/{$borrowing->id}/return");

    $response->assertRedirect();
    $fresh = $borrowing->fresh();
    $this->assertEquals('returned', $fresh->status);
    $this->assertEquals(3000, $fresh->fine); // fine stays 3000
    $this->assertEquals(5, $book->fresh()->stock); // stock increments
});

test('user cannot borrow more than 3 books', function () {
    $user = User::factory()->create();
    $books = Book::factory()->count(4)->create(['stock' => 5]);

    // Borrow 3 books successfully
    for ($i = 0; $i < 3; $i++) {
        $this->actingAs($user)->post('/borrowings', ['book_id' => $books[$i]->id]);
    }

    // 4th attempt should fail
    $response = $this->actingAs($user)->post('/borrowings', ['book_id' => $books[3]->id]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertEquals(3, Borrowing::where('user_id', $user->id)->count());
});

test('user cannot borrow a book if they have an unpaid fine', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create(['stock' => 5]);
    $newBook = Book::factory()->create(['stock' => 5]);

    // Create an unpaid fine record
    Borrowing::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'borrow_date' => now()->subDays(10),
        'return_date' => now()->subDays(3),
        'status' => 'returned',
        'fine' => 3000,
        'fine_payment_status' => 'unpaid',
    ]);

    // Try to borrow another book
    $response = $this->actingAs($user)->post('/borrowings', ['book_id' => $newBook->id]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('borrowings', [
        'user_id' => $user->id,
        'book_id' => $newBook->id,
        'status' => 'reserved',
    ]);
});

test('admin can confirm book pickup', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'borrow_date' => now(),
        'return_date' => now()->addDays(7),
        'status' => 'reserved',
        'fine' => 0,
        'fine_payment_status' => 'no_fine',
    ]);

    $response = $this->actingAs($admin)->post("/borrowings/{$borrowing->id}/confirm-pickup");

    $response->assertRedirect();
    $this->assertEquals('borrowed', $borrowing->fresh()->status);
});

test('admin can mark fine as paid', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'borrow_date' => now()->subDays(10),
        'return_date' => now()->subDays(3),
        'status' => 'returned',
        'fine' => 3000,
        'fine_payment_status' => 'unpaid',
    ]);

    $response = $this->actingAs($admin)->post("/borrowings/{$borrowing->id}/pay-fine");

    $response->assertRedirect();
    $this->assertEquals('paid', $borrowing->fresh()->fine_payment_status);
});

test('user or admin can cancel reservation', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create(['stock' => 4]);

    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'borrow_date' => now(),
        'return_date' => now()->addDays(7),
        'status' => 'reserved',
        'fine' => 0,
        'fine_payment_status' => 'no_fine',
    ]);

    // Cancel reservation
    $response = $this->actingAs($user)->post("/borrowings/{$borrowing->id}/cancel");

    $response->assertRedirect();
    $this->assertEquals('canceled', $borrowing->fresh()->status);
    $this->assertEquals(5, $book->fresh()->stock); // Stock incremented back
});

