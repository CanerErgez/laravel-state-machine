<?php

namespace Caner\StateMachine\Console\Commands;

use Caner\StateMachine\Console\Commands\Concerns\GeneratesStateMachineClasses;
use Illuminate\Console\GeneratorCommand;

final class MakeStateMachineCommand extends GeneratorCommand
{
    use GeneratesStateMachineClasses;

    protected $name = 'make:state-machine';
    protected $description = 'Create a state machine class';
    protected $type = 'State machine';
    protected string $stubName = 'state-machine.stub';
}
