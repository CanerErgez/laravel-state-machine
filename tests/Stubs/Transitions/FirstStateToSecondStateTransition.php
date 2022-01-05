<?php

namespace Caner\StateMachine\Tests\Stubs\Transitions;

use Caner\StateMachine\Concerns\BaseTransition;
use Caner\StateMachine\Tests\Stubs\AfterActions\TestAfterAction;
use Caner\StateMachine\Tests\Stubs\Guards\TestGuard;
use Illuminate\Database\Eloquent\Model;

class FirstStateToSecondStateTransition extends BaseTransition
{

    public function guards()
    {
        return [
            TestGuard::class,
        ];
    }

    public function action(): Model
    {
        return $this->baseStateMachine->getModel();
    }

    public function afterActions()
    {
        return [
            TestAfterAction::class,
        ];
    }
}
