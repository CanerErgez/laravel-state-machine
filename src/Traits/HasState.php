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
        $stateMachine = app()->make($baseStateMachine, [
            'model' => $this,
            'mainAttribute' => $mainAttributeName,
        ]);

        $stateClass = $stateMachine->getState();

        return app()->make($stateClass, [
            'model' => $this,
            'mainAttribute' => $mainAttributeName,
        ]);
    }

}
