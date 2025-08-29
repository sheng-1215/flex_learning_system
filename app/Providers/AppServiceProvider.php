<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

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
        // Handle web server deployment configuration
        $this->configureWebServer();
    }

    /**
     * Configure the application for web server deployment
     */
    protected function configureWebServer(): void
    {
        $request = request();
        
        // Check if we're in a subdirectory deployment
        if ($request && str_contains($request->getRequestUri(), '/flex_learning_system/public/')) {
            // Set the correct base URL for subdirectory deployment
            $baseUrl = 'https://e-lerning.synergy-college.org/flex_learning_system/public';
            Config::set('app.url', $baseUrl);
            
            // Force HTTPS in production
            URL::forceScheme('https');
            
            // Set the asset URL
            Config::set('app.asset_url', $baseUrl);
        }
        
        // Force HTTPS in production environment
        if (config('app.env') === 'production' || config('app.web_server.force_https', false)) {
            URL::forceScheme('https');
        }
    }
}
