<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request): View|Response
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $books = $query->orderBy('title', 'asc')->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        if ($request->ajax()) {
            return response(view('books.partials.book-list', compact('books'))->render());
        }

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book): View
    {
        $book->load('category');
        $book->loadCount('borrowings');

        $relatedBooks = Book::with('category')
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('stock', '>', 0)
            ->limit(4)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created book.
     */
    public function store(StoreBookRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $result = cloudinary()->uploadApi()->upload(
                $request->file('cover_image')->getRealPath(),
                ['folder' => 'perpustakaan/covers']
            );
            $data['cover_image'] = $result['secure_url'];
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Buku baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book): View
    {
        $categories = Category::orderBy('name')->get();

        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book.
     */
    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && str_starts_with($book->cover_image, 'http')) {
                cloudinary()->uploadApi()->destroy($this->extractCloudinaryPublicId($book->cover_image));
            }

            $result = cloudinary()->uploadApi()->upload(
                $request->file('cover_image')->getRealPath(),
                ['folder' => 'perpustakaan/covers']
            );
            $data['cover_image'] = $result['secure_url'];
        }

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Remove the specified book.
     */
    public function destroy(Book $book): RedirectResponse
    {
        $hasActiveBorrowings = $book->borrowings()
            ->whereIn('status', ['reserved', 'borrowed', 'returning'])
            ->exists();

        if ($hasActiveBorrowings) {
            return redirect()->back()->with('error', 'Buku "'.$book->title.'" tidak dapat dihapus karena masih ada peminjaman aktif.');
        }

        if ($book->cover_image && str_starts_with($book->cover_image, 'http')) {
            cloudinary()->uploadApi()->destroy($this->extractCloudinaryPublicId($book->cover_image));
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku "'.$book->title.'" berhasil dihapus.');
    }

    private function extractCloudinaryPublicId(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $afterUpload = explode('/upload/', $path)[1] ?? '';
        $withoutVersion = preg_replace('/^v\d+\//', '', $afterUpload);

        return preg_replace('/\.[^.]+$/', '', $withoutVersion);
    }
}
