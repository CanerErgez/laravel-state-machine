<?php

namespace Caner\StateMachine\Console\Commands;

use Caner\StateMachine\Console\Commands\Concerns\GeneratesStateMachineClasses;
use Illuminate\Console\GeneratorCommand;
use InvalidArgumentException;

final class MakeStateCommand extends GeneratorCommand
{
    use GeneratesStateMachineClasses;

    protected $signature = 'make:state {name} {--machine= : Parent state machine class}';
    protected $description = 'Create a state class';
    protected $type = 'State';
    protected string $stubName = 'state.stub';

    protected function buildClass($name): string
    {
        $machine = $this->option('machine');

        if (!$machine) {
            throw new InvalidArgumentException('The --machine option is required.');
        }

        return str_replace('DummyStateMachine', ltrim($machine, '\\'), parent::buildClass($name));
    }
}
