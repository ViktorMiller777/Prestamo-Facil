<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $user = Auth::user();

        if ($user instanceof User) {
            $user->loadMissing(['persona', 'sucursal', 'role', 'distribuidora']);
        }
    }

}
