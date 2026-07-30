<?php

namespace Caner\StateMachine\Console\Commands;

use Caner\StateMachine\Console\Commands\Concerns\GeneratesStateMachineClasses;
use Illuminate\Console\GeneratorCommand;

final class MakeAfterActionCommand extends GeneratorCommand
{
    use GeneratesStateMachineClasses;

    protected $name = 'make:after-action';

    protected $description = 'Create a state transition after action';

    protected $type = 'After action';

    protected string $stubName = 'after-action.stub';
}
