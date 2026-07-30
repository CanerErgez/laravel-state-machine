<?php

namespace Caner\StateMachine\Console\Commands;

use Caner\StateMachine\Console\Commands\Concerns\GeneratesStateMachineClasses;
use Illuminate\Console\GeneratorCommand;

final class MakeGuardCommand extends GeneratorCommand
{
    use GeneratesStateMachineClasses;

    protected $name = 'make:guard';

    protected $description = 'Create a state transition guard';

    protected $type = 'Guard';

    protected string $stubName = 'guard.stub';
}
