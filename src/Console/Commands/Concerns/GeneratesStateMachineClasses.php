<?php

namespace Caner\StateMachine\Console\Commands\Concerns;

trait GeneratesStateMachineClasses
{
    protected function getStub(): string
    {
        return __DIR__.'/../../../../stubs/'.$this->stubName;
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\StateMachines';
    }
}
