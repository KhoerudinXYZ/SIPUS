<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@perpustakaan.com'],
            [
                'name' => 'Administrator Perpustakaan',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        // Create Regular User
        $user = User::updateOrCreate(
            ['email' => 'user@perpustakaan.com'],
            [
                'name' => 'Budi Setiawan',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        // Create Books
        $books = [
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'year' => 2005,
                'isbn' => '9789793062791',
                'stock' => 5,
            ],
            [
                'title' => 'Bumi',
                'author' => 'Tere Liye',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2014,
                'isbn' => '9786020301120',
                'stock' => 3,
            ],
            [
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'publisher' => 'Buku Kompas',
                'year' => 2018,
                'isbn' => '9786024125189',
                'stock' => 7,
            ],
            [
                'title' => 'Cantik Itu Luka',
                'author' => 'Eka Kurniawan',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2002,
                'isbn' => '9789792225211',
                'stock' => 4,
            ],
            [
                'title' => 'Home Sweet Loan',
                'author' => 'Almira Bastari',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2022,
                'isbn' => '9786020658071',
                'stock' => 6,
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2019,
                'isbn' => '9786020633177',
                'stock' => 10,
            ],
            [
                'title' => 'Rich Dad Poor Dad',
                'author' => 'Robert T. Kiyosaki',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2016,
                'isbn' => '9786020333183',
                'stock' => 8,
            ],
        ];

        foreach ($books as $bookData) {
            Book::updateOrCreate(['isbn' => $bookData['isbn']], $bookData);
        }

        // Add some sample borrowings
        $book1 = Book::where('title', 'Laskar Pelangi')->first();
        $book2 = Book::where('title', 'Filosofi Teras')->first();

        if ($book1 && $user) {
            // 1. Returned borrowing (no fine)
            Borrowing::create([
                'user_id' => $user->id,
                'book_id' => $book1->id,
                'borrow_date' => now()->subDays(10),
                'return_date' => now()->subDays(3),
                'actual_return_date' => now()->subDays(3),
                'status' => 'returned',
                'fine' => 0,
            ]);
        }

        if ($book2 && $user) {
            // 2. Active borrowing
            Borrowing::create([
                'user_id' => $user->id,
                'book_id' => $book2->id,
                'borrow_date' => now()->subDays(4),
                'return_date' => now()->addDays(3),
                'actual_return_date' => null,
                'status' => 'borrowed',
                'fine' => 0,
            ]);
            $book2->decrement('stock');
        }
    }
}
