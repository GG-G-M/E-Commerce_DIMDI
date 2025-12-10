<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the audit service provider so it listens to events
        if (class_exists(\App\Providers\AuditServiceProvider::class)) {
            $this->app->register(\App\Providers\AuditServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure admin action logging middleware is applied to web routes
        try {
            if ($this->app->bound('router')) {
                $router = $this->app->make(\Illuminate\Routing\Router::class);
                // push our middleware to the web group if available
                $router->pushMiddlewareToGroup('web', \App\Http\Middleware\LogAdminActions::class);
            }
        } catch (\Throwable $e) {
            // ignore if router not available in this context
        }
    }
}
