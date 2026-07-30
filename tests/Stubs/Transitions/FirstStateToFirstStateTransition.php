<?php

namespace Caner\StateMachine\Tests\Stubs\Transitions;

use Caner\StateMachine\Concerns\BaseTransition;
use Illuminate\Database\Eloquent\Model;

class FirstStateToFirstStateTransition extends BaseTransition
{

    public function guards(): array
    {
        return [
            //
        ];
    }

    public function action(): Model
    {
        return $this->baseStateMachine->getModel();
    }

    public function afterActions(): array
    {
        return [
            //
        ];
    }
}
