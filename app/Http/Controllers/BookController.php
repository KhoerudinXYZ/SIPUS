<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request)
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

        $books = $query->orderBy('title', 'asc')->paginate(12)->withQueryString();

        if ($request->ajax()) {
            return view('books.partials.book-list', compact('books'))->render();
        }

        return view('books.index', compact('books'));
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
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'year' => ['required', 'integer', 'min:1000', 'max:'.(date('Y') + 1)],
            'isbn' => ['nullable', 'string', 'unique:books,isbn'],
            'stock' => ['required', 'integer', 'min:0'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

            // Ensure covers directory exists
            if (! File::exists(public_path('covers'))) {
                File::makeDirectory(public_path('covers'), 0755, true);
            }

            $image->move(public_path('covers'), $imageName);
            $data['cover_image'] = 'covers/'.$imageName;
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
    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'year' => ['required', 'integer', 'min:1000', 'max:'.(date('Y') + 1)],
            'isbn' => ['nullable', 'string', 'unique:books,isbn,'.$book->id],
            'stock' => ['required', 'integer', 'min:0'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('cover_image')) {
            // Delete old cover if exists
            if ($book->cover_image && File::exists(public_path($book->cover_image))) {
                File::delete(public_path($book->cover_image));
            }

            $image = $request->file('cover_image');
            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

            if (! File::exists(public_path('covers'))) {
                File::makeDirectory(public_path('covers'), 0755, true);
            }

            $image->move(public_path('covers'), $imageName);
            $data['cover_image'] = 'covers/'.$imageName;
        }

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Remove the specified book.
     */
    public function destroy(Book $book): RedirectResponse
    {
        // Delete cover image file
        if ($book->cover_image && File::exists(public_path($book->cover_image))) {
            File::delete(public_path($book->cover_image));
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
