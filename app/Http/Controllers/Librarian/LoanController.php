<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Inertia\Inertia;

class LoanController extends Controller
{
    public function index()
    {
        return Inertia::render('Librarian/Loans/Index', [
            'loans' => Loan::with(['user', 'book'])->latest()->get(),
        ]);
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
