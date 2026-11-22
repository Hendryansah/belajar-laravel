<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\User;
use App\Policies\BookPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // Gate untuk membatasi fitur pengelolaan pengguna khusus untuk role 'admin'
        Gate::define('manage-users', function (User $user): bool {
            return $user->role === 'admin';
        });

        // Mendaftarkan Policy untuk Model Book
        Gate::policy(Book::class, BookPolicy::class);
    }
}
