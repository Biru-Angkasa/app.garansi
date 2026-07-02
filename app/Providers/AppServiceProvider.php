<?php

namespace App\Providers;

use App\Models\Garansi;
use App\Observers\GaransiObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Garansi::observe(GaransiObserver::class);
    }
}