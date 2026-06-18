<?php

namespace App\Models;

use Database\Factories\BorrowingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    /** @use HasFactory<BorrowingFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_date',
        'return_date',
        'actual_return_date',
        'status',
        'fine',
        'fine_payment_status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'return_date' => 'date',
            'actual_return_date' => 'date',
            'fine' => 'integer',
        ];
    }

    /**
     * Get the user who borrowed the book.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that was borrowed.
     *
     * @return BelongsTo<Book, $this>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Calculate fine based on today vs return_date.
     */
    public function calculateFine(): int
    {
        $today = now()->startOfDay();
        $returnDate = $this->return_date->startOfDay();

        if (! $today->greaterThan($returnDate)) {
            return 0;
        }

        return abs($today->diffInDays($returnDate)) * config('library.fine_per_day');
    }
}
