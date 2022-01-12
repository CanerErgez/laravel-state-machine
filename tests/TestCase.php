<?php

namespace Caner\StateMachine\Tests;

use Caner\StateMachine\StateMachineServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            StateMachineServiceProvider::class,
        ];
    }
}
