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
                InstallAiRulesCommand::class,
            ]);
            
            $this->publishes([
                __DIR__.'/../config/ai-rules.php' => config_path('ai-rules.php'),
            ], 'ai-rules-config');

            $this->publishes([
                __DIR__.'/../stubs/ai-rules.stub' => base_path('stubs/ai-rules.stub'),
            ], 'ai-rules-stubs');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ai-rules.php', 'ai-rules'
        );
    }
}
