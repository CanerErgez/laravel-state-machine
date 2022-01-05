<?php

namespace Caner\StateMachine;

use Illuminate\Support\ServiceProvider;

class StateMachineServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('state-machine.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'state-machine');

        $this->app->singleton('state-machine', function () {
            return new StateMachine;
        });
    }
}
