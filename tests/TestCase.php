<?php

namespace Caner\StateMachine\Tests;

use Caner\StateMachine\StateMachineServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            StateMachineServiceProvider::class,
        ];
    }
}
