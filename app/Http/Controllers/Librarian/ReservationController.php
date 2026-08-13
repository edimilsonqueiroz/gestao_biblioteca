<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Reservation;
use Inertia\Inertia;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::with(['user', 'book'])
            ->latest()
            ->paginate();

        return Inertia::render('Librarian/Reservations/Index', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Approve a reservation.
     */
    public function approve(Reservation $reservation)
    {
        $reservation->update(['status' => 'approved']);

        return redirect()->back();
    }

    /**
     * Reject a reservation.
     */
    public function reject(Reservation $reservation)
    {
        $reservation->update(['status' => 'cancelled']);

        return redirect()->back();
    }

    /**
     * Convert a reservation to a loan.
     */
    public function convertToLoan(Reservation $reservation)
    {
        $book = $reservation->book;

        if ($book->quantity_available <= 0) {
            return back()->withErrors(['error' => "O livro '{$book->title}' não tem exemplares disponíveis"]);
        }

        // Criar empréstimo
        Loan::create([
            'user_id' => $reservation->user_id,
            'book_id' => $reservation->book_id,
            'loaned_at' => now(),
            'due_at' => now()->addDays(14),
            'status' => 'active',
        ]);

        // Decrementar quantidade disponível
        $book->decrement('quantity_available');

        // Marcar reserva como convertida
        $reservation->update(['status' => 'converted_to_loan']);

        return redirect()->back();
    }
}
