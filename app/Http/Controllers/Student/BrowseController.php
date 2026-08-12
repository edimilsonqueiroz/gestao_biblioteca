<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrowseController extends Controller
{
    public function browse(Request $request)
    {
        $query = Book::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%")
                ->orWhere('isbn', 'like', "%{$search}%");
        }

        if ($request->has('category') && $request->get('category')) {
            $query->where('category', $request->get('category'));
        }

        return Inertia::render('Student/Browse', [
            'books' => $query->where('quantity_available', '>', 0)->get(),
            'categories' => Book::distinct('category')->pluck('category')->filter(),
        ]);
    }

    public function myLoans()
    {
        return Inertia::render('Student/MyLoans', [
            'loans' => auth()->user()->loans()->with('book')->latest()->get(),
        ]);
    }

    public function requestLoan(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->quantity_available <= 0) {
            return redirect()->back()->with('error', 'Livro não disponível');
        }

        $existingLoan = Loan::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->where('status', 'active')
            ->first();

        if ($existingLoan) {
            return redirect()->back()->with('error', 'Você já tem este livro emprestado');
        }

        $book->decrement('quantity_available');

        Loan::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'loaned_at' => now(),
            'due_at' => now()->addDays(14),
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Livro emprestado com sucesso!');
    }

    public function returnLoan(Loan $loan)
    {
        if ($loan->user_id !== auth()->id()) {
            return redirect()->back();
        }

        $loan->book->increment('quantity_available');
        $loan->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Livro devolvido com sucesso!');
    }
}
