<?php

namespace Caner\StateMachine\Traits;

use Caner\StateMachine\Concerns\BaseStateMachine;

trait HasState
{
    /**
     * This trait returns current state
     *
     * @param string $baseStateMachine
     * @param string $mainAttributeName
     * @return BaseStateMachine
     */
    public function state(string $baseStateMachine, string $mainAttributeName): BaseStateMachine
    {
        $stateMachine = new $baseStateMachine($this, $mainAttributeName);

        $stateClass = $stateMachine->getState();

        return new $stateClass($this, $mainAttributeName);
    }

}
