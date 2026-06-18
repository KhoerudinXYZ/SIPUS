<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page based on user role.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            // Admin stats
            $totalBooks = Book::count();
            $totalUsers = User::where('role', 'user')->count();
            $activeBorrowings = Borrowing::where('status', 'borrowed')->count();
            $totalReturned = Borrowing::where('status', 'returned')->count();
            $totalFines = Borrowing::sum('fine');

            // Latest borrowings
            $latestBorrowings = Borrowing::with(['user', 'book'])
                ->latest()
                ->limit(5)
                ->get();

            // Book stock alerts (stock <= 2)
            $lowStockBooks = Book::where('stock', '<=', 2)->get();

            return view('dashboard.admin', compact(
                'totalBooks',
                'totalUsers',
                'activeBorrowings',
                'totalReturned',
                'totalFines',
                'latestBorrowings',
                'lowStockBooks'
            ));
        }

        // User stats (reserved, borrowed, returning all count as active slots)
        $activeBorrowingsCount = Borrowing::where('user_id', $user->id)
            ->whereIn('status', ['reserved', 'borrowed', 'returning'])
            ->count();

        $historyCount = Borrowing::where('user_id', $user->id)
            ->where('status', 'returned')
            ->count();

        // Only sum UNPAID fines
        $myFines = Borrowing::where('user_id', $user->id)
            ->where('fine_payment_status', 'unpaid')
            ->sum('fine');

        // Current active borrowings and reservations list
        $myActiveBorrowings = Borrowing::with('book')
            ->where('user_id', $user->id)
            ->whereIn('status', ['reserved', 'borrowed', 'returning'])
            ->orderBy('status', 'desc') // puts reserved/borrowed higher
            ->orderBy('created_at', 'desc')
            ->get();

        // Available books recommendation
        $availableBooks = Book::where('stock', '>', 0)
            ->latest()
            ->limit(4)
            ->get();

        return view('dashboard.user', compact(
            'activeBorrowingsCount',
            'historyCount',
            'myFines',
            'myActiveBorrowings',
            'availableBooks'
        ));
    }
}
