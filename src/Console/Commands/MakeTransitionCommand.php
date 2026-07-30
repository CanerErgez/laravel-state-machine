<?php

namespace Caner\StateMachine\Console\Commands;

use Caner\StateMachine\Console\Commands\Concerns\GeneratesStateMachineClasses;
use Illuminate\Console\GeneratorCommand;

final class MakeTransitionCommand extends GeneratorCommand
{
    use GeneratesStateMachineClasses;

    protected $name = 'make:transition';

    protected $description = 'Create a transition class';

    protected $type = 'Transition';

    protected string $stubName = 'transition.stub';
}
