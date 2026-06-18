<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard/login
Route::get('/', function () {
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
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Books Listing (Shared between Admin & User)
    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    // Borrowings Listing (Shared, but list data filtered by controller)
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');

    // User only: Request borrow book & request return
    Route::middleware('role:user')->group(function () {
        Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
        Route::post('/borrowings/{borrowing}/request-return', [BorrowingController::class, 'requestReturn'])->name('borrowings.request-return');
    });

    // Shared cancellation of reservation
    Route::post('/borrowings/{borrowing}/cancel', [BorrowingController::class, 'cancelReservation'])->name('borrowings.cancel');

    // Admin only: Books CRUD and Return processing
    Route::middleware('role:admin')->group(function () {
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

        Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');
        Route::post('/borrowings/{borrowing}/confirm-pickup', [BorrowingController::class, 'confirmPickup'])->name('borrowings.confirm-pickup');
        Route::post('/borrowings/{borrowing}/pay-fine', [BorrowingController::class, 'payFine'])->name('borrowings.pay-fine');

        // Categories CRUD
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Users CRUD
        Route::resource('users', UserController::class)->except(['show']);
    });
});
