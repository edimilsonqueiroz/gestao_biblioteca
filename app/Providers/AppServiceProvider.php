<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Policies\BookPolicy;
use App\Policies\LoanPolicy;
use App\Policies\UserPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Book::class, BookPolicy::class);
        Gate::policy(Loan::class, LoanPolicy::class);

        // Listener for new user registration
        Event::listen(Registered::class, function (Registered $event) {
            // Auto-login admin users, redirect others to success page
            if ($event->user->role === 'admin') {
                Auth::login($event->user);

                // Redirect to dashboard
                throw new HttpResponseException(
                    redirect()->route('dashboard')
                );
            }

            // For non-admin users, log out to prevent auto-login
            Auth::logout();

            // Redirect to success page
            throw new HttpResponseException(
                redirect()->route('register.success')
            );
        });

        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
