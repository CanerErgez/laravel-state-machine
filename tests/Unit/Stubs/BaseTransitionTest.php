<?php

namespace Caner\StateMachine\Tests\Unit\Stubs;

use Caner\StateMachine\Events\AfterActionCompletedEvent;
use Caner\StateMachine\Events\GuardCompletedEvent;
use Caner\StateMachine\Exceptions\GuardErrorException;
use Caner\StateMachine\Exceptions\GuardResultNotFoundException;
use Caner\StateMachine\Support\TransitionContext;
use Caner\StateMachine\Tests\Stubs\Guards\TestGuard;
use Caner\StateMachine\Tests\Stubs\Models\TestModel;
use Caner\StateMachine\Tests\Stubs\States\FirstState;
use Caner\StateMachine\Tests\Stubs\States\SecondState;
use Caner\StateMachine\Tests\Stubs\TestStateMachine;
use Caner\StateMachine\Tests\Stubs\Transitions\FirstStateToSecondStateTransition;
use Caner\StateMachine\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

class BaseTransitionTest extends TestCase
{
    #[Test]
    public function it_runs_guards_action_state_update_and_after_actions(): void
    {
        Event::fake();
        $model = TestModel::create(['status' => 1]);
        $machine = new FirstState($model, 'status');
        $transition = new FirstStateToSecondStateTransition(
            $machine,
            new TransitionContext(),
            SecondState::class,
        );

        $result = $transition->handle();

        $this->assertTrue($transition->isRunAllGuards);
        $this->assertTrue($transition->isRunAllAfterActions);
        $this->assertSame(2, $result->refresh()->status);
        Event::assertDispatched(GuardCompletedEvent::class);
        Event::assertDispatched(AfterActionCompletedEvent::class);
    }

    #[Test]
    public function it_rejects_a_missing_guard_result(): void
    {
        $this->expectException(GuardResultNotFoundException::class);
        $transition = $this->transition();
        $result = new stdClass();
        $result->data = [];

        $transition->checkGuardData($result, TestGuard::class);
    }

    #[Test]
    public function it_rejects_a_false_guard_result(): void
    {
        $this->expectException(GuardErrorException::class);
        $transition = $this->transition();
        $result = new stdClass();
        $result->data = ['result' => false];

        $transition->checkGuardData($result, TestGuard::class);
    }

    private function transition(): FirstStateToSecondStateTransition
    {
        $model = TestModel::create(['status' => 1]);

        return new FirstStateToSecondStateTransition(
            new TestStateMachine($model, 'status'),
            new TransitionContext(),
            SecondState::class,
        );
    }
}
