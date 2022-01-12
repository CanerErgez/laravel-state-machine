<?php

namespace Caner\StateMachine\Tests\Stubs\Transitions;

use Caner\StateMachine\Concerns\BaseTransition;
use Illuminate\Database\Eloquent\Model;

class SecondStateToFirstStateTransition extends BaseTransition
{

    public function guards()
    {
        return [
            //
        ];
    }

    public function action(): Model
    {
        // TODO: Implement action() method.
    }

    public function afterActions()
    {
        return [
            //
        ];
    }
}
