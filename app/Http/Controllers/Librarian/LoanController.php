<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoanController extends Controller
{
    public function index()
    {
        return Inertia::render('Librarian/Loans/Index', [
            'loans' => Loan::with(['user', 'book'])->latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Librarian/Loans/Create', [
            'users' => User::where('active', true)->whereIn('role', ['aluno', 'professor'])->get(),
            'books' => Book::where('quantity_available', '>', 0)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_ids' => 'required|array|min:1',
            'book_ids.*' => 'exists:books,id',
            'loaned_at' => 'required|date',
            'due_at' => 'required|date|after:loaned_at',
        ]);

        // Verificar disponibilidade de todos os livros primeiro
        foreach ($validated['book_ids'] as $bookId) {
            $book = Book::findOrFail($bookId);
            if ($book->quantity_available <= 0) {
                return back()->withErrors(['book_ids' => "O livro '{$book->title}' não tem exemplares disponíveis"]);
            }
        }

        // Criar empréstimo para cada livro
        foreach ($validated['book_ids'] as $bookId) {
            $book = Book::findOrFail($bookId);

            Loan::create([
                'user_id' => $validated['user_id'],
                'book_id' => $bookId,
                'loaned_at' => $validated['loaned_at'],
                'due_at' => $validated['due_at'],
                'status' => 'active',
            ]);

            // Decrementar quantidade disponível
            $book->decrement('quantity_available');
        }

        return redirect()->route('librarian.loans.index');
    }

    public function show(Loan $loan)
    {
        return Inertia::render('Librarian/Loans/Show', [
            'loan' => $loan->load(['user', 'book']),
        ]);
    }

    public function approve(Loan $loan)
    {
        $loan->update(['status' => 'active']);

        return redirect()->back();
    }

    public function reject(Loan $loan)
    {
        $loan->delete();

        return redirect()->back();
    }

    public function markReturned(Loan $loan)
    {
        $book = $loan->book;
        $book->increment('quantity_available');

        $loan->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        return redirect()->back();
    }
}
