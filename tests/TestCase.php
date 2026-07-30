<?php

namespace Caner\StateMachine\Tests;

use Caner\StateMachine\StateMachineServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('test_models', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedTinyInteger('status');
            $table->timestamps();
        });
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            StateMachineServiceProvider::class,
        ];
    }
}
