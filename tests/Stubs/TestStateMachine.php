<?php

namespace Caner\StateMachine\Tests\Stubs;

use Caner\StateMachine\Concerns\BaseStateMachine;
use Caner\StateMachine\Tests\Stubs\Enums\TestStateEnums;
use Caner\StateMachine\Tests\Stubs\States\FirstState;
use Caner\StateMachine\Tests\Stubs\States\SecondState;
use Caner\StateMachine\Tests\Stubs\Transitions\FirstStateToFirstStateTransition;
use Caner\StateMachine\Tests\Stubs\Transitions\FirstStateToSecondStateTransition;
use Caner\StateMachine\Tests\Stubs\Transitions\SecondStateToFirstStateTransition;

class TestStateMachine extends BaseStateMachine
{
    public function initialState()
    {
        return TestStateEnums::FirstState;
    }

    public function states()
    {
        return [
            TestStateEnums::FirstState      => FirstState::class,
            TestStateEnums::SecondState     => SecondState::class,
        ];
    }

    public function transitions()
    {
        return [
            self::class => [
                $this->initialState() => FirstStateToFirstStateTransition::class,
            ],
            FirstState::class => [
                SecondState::class => FirstStateToSecondStateTransition::class,
            ],
            SecondState::class => [
                FirstState::class => SecondStateToFirstStateTransition::class,
            ],
        ];
    }
}
