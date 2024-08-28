<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Cruzmediaorg\InertiaPageBlockBuilder\Console\Commands\CreateBlockCommand;

class IPBBServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ipbb.php', 'ipbb');

        $this->app->singleton(BlockManager::class, function () {
            return new BlockManager();
        });
    }

    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/ipbb.php' => config_path('ipbb.php'),
        ], 'ipbb-config');

        // Publish directories
        $this->publishes([
            __DIR__.'/../stubs/IPBB' => base_path('app/IPBB'),
            __DIR__.'/../stubs/js/IPBB' => resource_path('js/IPBB'),
        ], 'ipbb-stubs');

        // Publish stubs
        $this->publishes([
            __DIR__.'/Console/Commands/stubs' => base_path('stubs/vendor/ipbb'),
        ], 'ipbb-stubs');

        // Publish ServiceProvider
        $this->publishes([
            __DIR__.'/stubs/IPBBServiceProvider.stub' => app_path('Providers/IPBBServiceProvider.php'),
        ], 'ipbb-provider');

        // Register routes
        $this->registerRoutes();

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ipbb');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateBlockCommand::class,
            ]);
        }
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('ipbb.route_prefix', 'ipbb'),
            'middleware' => config('ipbb.middleware', ['web', 'auth']),
        ];
    }
}