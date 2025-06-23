<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('is-supplier', function ($user) {
            return $user->role === 'supplier';
        });
        Gate::define('is-distributor', function ($user) {
            return $user->role === 'distributor';
        });
        Gate::define('is-bakery-manager', function ($user) {
            return $user->role === 'bakery_manager';
        });
        Gate::define('is-pending', function ($user) {
            return $user->role === 'pending';
        });
        Gate::define('is-retail-manager', function ($user) {
            return $user->role === 'retail_manager';
        });
    }
} 