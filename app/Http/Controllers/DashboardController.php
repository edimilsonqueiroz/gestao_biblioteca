<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalBooks = Book::count();
        $activeLoans = Loan::where('status', 'active')->count();
        $overdueLoans = Loan::where('status', 'active')
            ->where('due_at', '<', now())
            ->count();

        $recentLoans = Loan::with(['user', 'book'])
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('Dashboard', [
            'statistics' => [
                'totalBooks' => $totalBooks,
                'activeLoans' => $activeLoans,
                'overdueLoans' => $overdueLoans,
            ],
            'recentLoans' => $recentLoans,
        ]);
    }
}
