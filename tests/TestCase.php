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

        Schema::create('state_machine_history', function (Blueprint $table): void {
            $table->id();
            $table->string('model_type');
            $table->string('model_id');
            $table->string('attribute');
            $table->string('from_state');
            $table->string('to_state');
            $table->string('transition');
            $table->string('transition_name');
            $table->nullableMorphs('actor');
            $table->json('metadata')->nullable();
            $table->timestamp('created_at');
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
