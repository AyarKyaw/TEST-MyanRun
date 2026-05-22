<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

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
        /**
         * We check if the table exists first. 
         * This prevents 'Table not found' errors during migrations.
         */
        if (Schema::hasTable('site_settings')) {
            $settings = Cache::remember('site_settings', 86400, function () {
                return SiteSetting::pluck('value', 'key')->all();
            });

            // Share with all blade views
            View::share('global_info', (object) $settings);
        } else {
            // Provide a fallback empty object so views don't error out 
            // during the migration/installation process.
            View::share('global_info', (object) []);
        }
    }
}