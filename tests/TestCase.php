<?php

namespace Drose\LaravelAiRules\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Drose\LaravelAiRules\LaravelAiRulesServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelAiRulesServiceProvider::class,
        ];
    }
}
