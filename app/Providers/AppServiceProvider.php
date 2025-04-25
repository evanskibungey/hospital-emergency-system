<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Add component aliases for backward compatibility
        Blade::component('components.input-label', 'label');
        Blade::component('components.text-input', 'input');
        Blade::component('components.primary-button', 'button');
    }
}