<?php

namespace Caner\StateMachine\Traits;

use Caner\StateMachine\Concerns\BaseStateMachine;

trait HasState
{
    public function state(string $baseStateMachine, string $mainAttributeName): BaseStateMachine
    {
        $stateMachine = new $baseStateMachine($this, $mainAttributeName);

        $stateClass = $stateMachine->getState();

        return new $stateClass($this, $mainAttributeName);
    }

}
