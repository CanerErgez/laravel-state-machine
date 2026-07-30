<?php

namespace Caner\StateMachine\Tests\Unit\Stubs;

use Caner\StateMachine\Events\StateChanged;
use Caner\StateMachine\Events\TransitionCompleted;
use Caner\StateMachine\Events\TransitionStarting;
use Caner\StateMachine\Exceptions\TransitionNotFoundException;
use Caner\StateMachine\Support\TransitionContext;
use Caner\StateMachine\Tests\Stubs\Enums\TestStateEnums;
use Caner\StateMachine\Tests\Stubs\Models\TestModel;
use Caner\StateMachine\Tests\Stubs\States\FirstState;
use Caner\StateMachine\Tests\Stubs\States\SecondState;
use Caner\StateMachine\Tests\Stubs\TestStateMachine;
use Caner\StateMachine\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class BaseStateMachineTest extends TestCase
{
    #[Test]
    public function it_resolves_the_current_state_and_allowed_transitions(): void
    {
        $machine = $this->machine();

        $this->assertSame(FirstState::class, $machine->getState());
        $this->assertSame([SecondState::class], $machine->allowedTransitions());
        $this->assertTrue($machine->canTransitionTo(SecondState::class));
        $this->assertFalse($machine->canTransitionTo(FirstState::class));
    }

    #[Test]
    public function it_throws_when_a_transition_is_not_defined(): void
    {
        $this->expectException(TransitionNotFoundException::class);

        $this->machine()->transitionTo(FirstState::class);
    }

    #[Test]
    public function it_runs_a_transition_and_dispatches_lifecycle_events(): void
    {
        Event::fake();
        $machine = $this->machine();
        $context = new TransitionContext(
            data: ['source' => 'test'],
            actor: 42,
            metadata: ['trace_id' => 'abc'],
        );

        $model = $machine->transitionTo(SecondState::class, $context);

        $this->assertSame(TestStateEnums::SecondState, $model->refresh()->status);
        Event::assertDispatched(TransitionStarting::class);
        Event::assertDispatched(StateChanged::class);
        Event::assertDispatched(TransitionCompleted::class);
    }

    #[Test]
    public function the_model_trait_resolves_the_state_machine_through_the_container(): void
    {
        $model = TestModel::create(['status' => TestStateEnums::FirstState]);

        $this->assertInstanceOf(
            FirstState::class,
            $model->state(TestStateMachine::class, 'status'),
        );
    }

    #[Test]
    public function it_exposes_named_transition_details(): void
    {
        $details = $this->machine()->allowedTransitionDetails();

        $this->assertSame('first_state_to_second_state', $details[0]['name']);
        $this->assertSame(SecondState::class, $details[0]['state']);
        $this->assertSame([], $details[0]['metadata']);
    }

    #[Test]
    public function it_records_transition_history_when_enabled(): void
    {
        config()->set('state-machine.history.enabled', true);
        $machine = $this->machine();

        $model = $machine->transitionTo(
            SecondState::class,
            new TransitionContext(metadata: ['source' => 'test']),
        );

        $history = DB::table('state_machine_history')->first();

        $this->assertSame((string) $model->getKey(), $history->model_id);
        $this->assertSame('status', $history->attribute);
        $this->assertSame(FirstState::class, $history->from_state);
        $this->assertSame(SecondState::class, $history->to_state);
        $this->assertSame('first_state_to_second_state', $history->transition_name);
        $this->assertSame(['source' => 'test'], json_decode($history->metadata, true));
    }

    private function machine(): FirstState
    {
        $model = TestModel::create([
            'status' => TestStateEnums::FirstState,
        ]);

        return new FirstState($model, 'status');
    }
}
