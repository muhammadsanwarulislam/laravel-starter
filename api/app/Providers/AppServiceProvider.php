<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Repositories\UserRepository;
use App\Services\LocalizationService;
use Illuminate\Support\ServiceProvider;

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
        //
    }
}
