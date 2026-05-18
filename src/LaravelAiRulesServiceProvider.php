<?php

namespace Drose\LaravelAiRules;

use Illuminate\Support\ServiceProvider;
use Drose\LaravelAiRules\Console\Commands\PublishAiRulesCommand;

class LaravelAiRulesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishAiRulesCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
