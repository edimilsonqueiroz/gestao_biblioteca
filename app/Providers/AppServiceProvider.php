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
            // Immediately log out the user to prevent auto-login
            Auth::logout();
            
            // Redirect to success page
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
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
