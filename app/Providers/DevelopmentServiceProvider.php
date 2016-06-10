<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider as BarryvdhIdeHelperServiceProvider;
use Barryvdh\Debugbar\ServiceProvider as BarryvdhDebugbarServiceProvider;

class DevelopmentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // APP_DEBUGがtrueの時だけ開発用プロバイダ読込
        if (env('APP_DEBUG', false)) {
            $this->app->register(BarryvdhIdeHelperServiceProvider::class);
            $this->app->register(BarryvdhDebugbarServiceProvider::class);
        }
    }
}
