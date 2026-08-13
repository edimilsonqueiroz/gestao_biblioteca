<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Librarian\BookController;
use App\Http\Controllers\Librarian\LoanController;
use App\Http\Controllers\Librarian\ReservationController;
use App\Http\Controllers\Student\BrowseController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::inertia('register-success', 'auth/RegisterSuccess')->name('register.success');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');
    });

    // Librarian routes
    Route::middleware('role:bibliotecaria')->prefix('bibliotecaria')->name('librarian.')->group(function () {
        Route::resource('books', BookController::class);
        Route::resource('loans', LoanController::class);
        Route::post('loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        Route::post('loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');
        Route::post('loans/{loan}/mark-returned', [LoanController::class, 'markReturned'])->name('loans.markReturned');
        
        Route::get('reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::post('reservations/{reservation}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
        Route::post('reservations/{reservation}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');
        Route::post('reservations/{reservation}/convert-to-loan', [ReservationController::class, 'convertToLoan'])->name('reservations.convertToLoan');
    });

    // Student/Professor routes
    Route::middleware('role:aluno,professor')->prefix('minha-biblioteca')->name('student.')->group(function () {
        Route::get('buscar-livros', [BrowseController::class, 'browse'])->name('browse');
        Route::post('solicitar-livro', [BrowseController::class, 'requestLoan'])->name('request-loan');
        Route::get('meus-emprestimos', [BrowseController::class, 'myLoans'])->name('my-loans');
        Route::post('devolver-livro/{loan}', [BrowseController::class, 'returnLoan'])->name('return-loan');
    });
});

require __DIR__.'/settings.php';
