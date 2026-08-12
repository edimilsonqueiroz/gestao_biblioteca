<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index()
    {
        return Inertia::render('Librarian/Books/Index', [
            'books' => Book::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Librarian/Books/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|unique:books|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'category' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1000|max:'.now()->year,
            'publisher' => 'nullable|string|max:255',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('books/covers', 'public');
            $validated['cover'] = $coverPath;
        }

        Book::create([
            ...$validated,
            'quantity_available' => $validated['quantity'],
        ]);

        return redirect()->route('librarian.books.index');
    }

    public function show(Book $book)
    {
        return Inertia::render('Librarian/Books/Show', [
            'book' => $book,
        ]);
    }

    public function edit(Book $book)
    {
        return Inertia::render('Librarian/Books/Edit', [
            'book' => $book,
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|unique:books,isbn,'.$book->id.'|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'category' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1000|max:'.now()->year,
            'publisher' => 'nullable|string|max:255',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($book->cover) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($book->cover);
            }
            $validated['cover'] = $request->file('cover')->store('books/covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('librarian.books.index');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('librarian.books.index');
    }
}
