<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

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
        $settings = Cache::remember('site_settings', 86400, function () {
            return SiteSetting::pluck('value', 'key')->all();
        });

        // Share with all blade views
        View::share('global_info', (object) $settings);
    }
}
