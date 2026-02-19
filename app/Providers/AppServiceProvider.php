<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Matche;
use App\Observers\MatcheObserver;
use App\Models\User;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Matche::observe(MatcheObserver::class);
        User::observe(UserObserver::class);
    }
}
