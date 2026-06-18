<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the borrowings.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Borrowing::with(['user', 'book']);

        if ($user->isAdmin()) {
            // Admin can search and filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('book', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        } else {
            // Regular user only sees their own borrowings
            $query->where('user_id', $user->id);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }

        $borrowings = $query->latest('borrow_date')
            ->paginate(10)
            ->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Store a newly created borrowing in storage (Borrow Book action for User).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'book_id' => ['required', 'exists:books,id'],
        ]);

        $user = $request->user();
        $book = Book::findOrFail($request->book_id);

        // 1. Check if user has unpaid fines
        $hasUnpaidFines = Borrowing::where('user_id', $user->id)
            ->where('fine_payment_status', 'unpaid')
            ->exists();

        if ($hasUnpaidFines) {
            return redirect()->back()->with('error', 'Anda memiliki denda yang belum dibayar. Lunasi denda terlebih dahulu untuk melakukan reservasi baru.');
        }

        // 2. Check if user has reached the maximum borrowing limit (3 books)
        $activeCount = Borrowing::where('user_id', $user->id)
            ->whereIn('status', ['reserved', 'borrowed', 'returning'])
            ->count();

        if ($activeCount >= 3) {
            return redirect()->back()->with('error', 'Anda telah mencapai batas maksimum peminjaman/reservasi (maksimal 3 buku).');
        }

        // 3. Check if book has stock
        if ($book->stock <= 0) {
            return redirect()->back()->with('error', 'Buku ini sedang habis stok.');
        }

        // 4. Check if user already reserved/borrowed this book
        $alreadyActive = Borrowing::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['reserved', 'borrowed', 'returning'])
            ->exists();

        if ($alreadyActive) {
            return redirect()->back()->with('error', 'Anda sudah melakukan reservasi atau meminjam buku ini.');
        }

        // 5. Create borrowing record as reserved
        Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now(), // reservation date
            'return_date' => now()->addDays(7), // placeholder
            'status' => 'reserved',
            'fine' => 0,
            'fine_payment_status' => 'no_fine',
        ]);

        // 6. Decrement book stock
        $book->decrement('stock');

        return redirect()->route('dashboard')->with('success', 'Buku "'.$book->title.'" berhasil direservasi. Silakan ambil buku fisik di perpustakaan dalam waktu 24 jam.');
    }

    /**
     * Confirm book pickup (Admin action).
     */
    public function confirmPickup(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if (! $request->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($borrowing->status !== 'reserved') {
            return redirect()->back()->with('error', 'Hanya reservasi tertunda yang dapat dikonfirmasi pengambilannya.');
        }

        $borrowing->update([
            'status' => 'borrowed',
            'borrow_date' => now(),
            'return_date' => now()->addDays(7), // 7 days starts now
        ]);

        return redirect()->back()->with('success', 'Pengambilan buku berhasil dikonfirmasi. Status peminjaman sekarang aktif.');
    }

    /**
     * Cancel book reservation (User & Admin action).
     */
    public function cancelReservation(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if (! $request->user()->isAdmin() && $request->user()->id !== $borrowing->user_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($borrowing->status !== 'reserved') {
            return redirect()->back()->with('error', 'Hanya reservasi tertunda yang dapat dibatalkan.');
        }

        $borrowing->update([
            'status' => 'canceled',
        ]);

        // Return stock
        $borrowing->book->increment('stock');

        return redirect()->back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    /**
     * Return a borrowed book (Process return action for Admin).
     */
    public function returnBook(Request $request, Borrowing $borrowing): RedirectResponse
    {
        // Safety check: only admin can return book
        if (! $request->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($borrowing->status === 'returned') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        if ($borrowing->status === 'borrowed') {
            $today = now()->startOfDay();
            $returnDate = $borrowing->return_date->startOfDay();
            $fine = 0;

            // Calculate fine if overdue (Rp 1.000 per day)
            if ($today->greaterThan($returnDate)) {
                $daysOverdue = abs($today->diffInDays($returnDate));
                $fine = $daysOverdue * 1000;
            }

            // Update borrowing record
            $borrowing->update([
                'actual_return_date' => now(),
                'status' => 'returned',
                'fine' => $fine,
                'fine_payment_status' => $fine > 0 ? 'unpaid' : 'no_fine',
            ]);

            // Increment book stock
            $borrowing->book->increment('stock');

            $message = 'Buku berhasil dikembalikan.';
            if ($fine > 0) {
                $message .= ' Pengguna terlambat '.$daysOverdue.' hari dan dikenakan denda sebesar Rp '.number_format($fine, 0, ',', '.').' (Belum Dibayar).';
            } else {
                $message .= ' Tepat waktu, tidak ada denda.';
            }

            return redirect()->back()->with('success', $message);
        }

        if ($borrowing->status === 'returning') {
            // Admin confirms the return request submitted by user
            $borrowing->update([
                'status' => 'returned',
            ]);

            // Increment book stock
            $borrowing->book->increment('stock');

            $message = 'Pengembalian buku berhasil dikonfirmasi.';
            if ($borrowing->fine > 0) {
                $message .= ' Terdapat denda sebesar Rp '.number_format($borrowing->fine, 0, ',', '.').' yang perlu dilunasi.';
            } else {
                $message .= ' Tepat waktu, tidak ada denda.';
            }

            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'Status peminjaman tidak valid.');
    }

    /**
     * Mark a fine as paid (Admin action).
     */
    public function payFine(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if (! $request->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($borrowing->fine_payment_status !== 'unpaid') {
            return redirect()->back()->with('error', 'Tidak ada denda menunggak untuk transaksi ini.');
        }

        $borrowing->update([
            'fine_payment_status' => 'paid',
        ]);

        return redirect()->back()->with('success', 'Denda sebesar Rp '.number_format($borrowing->fine, 0, ',', '.').' berhasil dilunasi.');
    }

    /**
     * Request a return of a book (User action).
     */
    public function requestReturn(Request $request, Borrowing $borrowing): RedirectResponse
    {
        // Safety check: user can only return their own borrowing
        if ($borrowing->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($borrowing->status !== 'borrowed') {
            return redirect()->back()->with('error', 'Peminjaman ini tidak sedang aktif.');
        }

        $today = now()->startOfDay();
        $returnDate = $borrowing->return_date->startOfDay();
        $fine = 0;

        // Calculate fine if overdue (Rp 1.000 per day)
        if ($today->greaterThan($returnDate)) {
            $daysOverdue = abs($today->diffInDays($returnDate));
            $fine = $daysOverdue * 1000;
        }

        // Update borrowing record to lock fine and actual return date
        $borrowing->update([
            'actual_return_date' => now(),
            'status' => 'returning',
            'fine' => $fine,
            'fine_payment_status' => $fine > 0 ? 'unpaid' : 'no_fine',
        ]);

        return redirect()->back()->with('success', 'Pengajuan pengembalian berhasil dikirim. Menunggu konfirmasi penerimaan buku oleh pustakawan.');
    }
}
