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
        ]);

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
        ]);

        $book->update($validated);

        return redirect()->route('librarian.books.index');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('librarian.books.index');
    }
}
