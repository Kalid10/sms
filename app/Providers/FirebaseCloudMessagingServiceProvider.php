<?php

namespace App\Providers;

use App\SDK\FirebaseCloudMessaging;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class FirebaseCloudMessagingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::bind('firebaseCloudMessaging', function () {
            return new FirebaseCloudMessaging;
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
