<?php

namespace App\Providers;

use App\SDK\Ethiopian;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class EthiopianServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::bind('ethiopian', function () {
            return new Ethiopian;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
