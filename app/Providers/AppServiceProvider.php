<?php

namespace App\Providers;

use App\Models\Assessment;
use App\Observers\AssessmentObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
    public function boot(UrlGenerator $url): void
    {
        // Check if 'locale' key exists in the session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        if (env('APP_ENV') !== 'local') {
            $url->forceScheme('https');
        }

        // Prevent lazy loading of Eloquent relationships
        Model::preventLazyLoading(! $this->app->isProduction());

        Assessment::observe(AssessmentObserver::class);

        Inertia::share([
            'locale' => fn () => app()->getLocale(),

            'language' => function () {
                if (! file_exists(resource_path('lang/'.app()->getLocale().'.json'))) {
                    return [];
                }

                return json_decode(file_get_contents(resource_path('lang/'.app()->getLocale().'.json')), true);
            },
        ]);
    }
}
