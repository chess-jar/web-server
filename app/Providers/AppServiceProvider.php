<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void Returns nothing.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void Returns nothing.
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
