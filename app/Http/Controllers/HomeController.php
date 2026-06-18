<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $newBooks = Book::with('category')->latest()->take(6)->get();
        $popularBooks = Book::withCount('borrowings')->orderBy('borrowings_count', 'desc')->take(6)->get();
        $totalBooks = Book::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalBorrowings = Borrowing::where('status', 'borrowed')->count();
        $totalCategories = Category::count();

        return view('welcome', compact(
            'newBooks',
            'popularBooks',
            'totalBooks',
            'totalUsers',
            'totalBorrowings',
            'totalCategories'
        ));
    }
}
