<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        // Register anonymous component paths
        Blade::anonymousComponentPath(base_path('resources/views/layouts'), 'layouts');
        Blade::anonymousComponentPath(base_path('resources/views/components'), 'components');
    }
}
