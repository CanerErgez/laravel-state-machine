<?php

namespace Caner\StateMachine;

use Caner\StateMachine\Console\Commands\MakeAfterActionCommand;
use Caner\StateMachine\Console\Commands\MakeGuardCommand;
use Caner\StateMachine\Console\Commands\MakeStateCommand;
use Caner\StateMachine\Console\Commands\MakeStateMachineCommand;
use Caner\StateMachine\Console\Commands\MakeTransitionCommand;
use Caner\StateMachine\Console\Commands\StateMachineDiagramCommand;
use Caner\StateMachine\History\DatabaseTransitionHistoryRecorder;
use Caner\StateMachine\History\TransitionHistoryRecorder;
use Illuminate\Support\ServiceProvider;

class StateMachineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('state-machine.php'),
            ], 'caner-state-machine-config');
            $this->publishes([
                __DIR__.'/../database/migrations/create_state_machine_history_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_state_machine_history_table.php'),
            ], 'caner-state-machine-migrations');
            $this->commands([
                MakeAfterActionCommand::class,
                MakeGuardCommand::class,
                MakeStateCommand::class,
                MakeStateMachineCommand::class,
                MakeTransitionCommand::class,
                StateMachineDiagramCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'state-machine');
        $this->app->bind(TransitionHistoryRecorder::class, DatabaseTransitionHistoryRecorder::class);
    }
}
