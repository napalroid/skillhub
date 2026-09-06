<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LivewireServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(\Livewire\LivewireServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
