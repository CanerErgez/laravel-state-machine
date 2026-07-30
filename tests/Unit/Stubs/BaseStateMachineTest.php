<?php

namespace Caner\StateMachine\Tests\Unit\Stubs;

use PHPUnit\Framework\Attributes\Test;

use Caner\StateMachine\Concerns\BaseStateMachine;
use Caner\StateMachine\Exceptions\TransitionFailedException;
use Caner\StateMachine\Exceptions\TransitionNotFoundException;
use Caner\StateMachine\Tests\Stubs\Enums\TestStateEnums;
use Caner\StateMachine\Tests\Stubs\Models\TestModel;
use Caner\StateMachine\Tests\Stubs\States\FirstState;
use Caner\StateMachine\Tests\Stubs\States\SecondState;
use Caner\StateMachine\Tests\Stubs\TestStateMachine;
use Caner\StateMachine\Tests\Stubs\Transitions\FirstStateToFirstStateTransition;
use Caner\StateMachine\Tests\Stubs\Transitions\FirstStateToSecondStateTransition;
use Caner\StateMachine\Tests\Stubs\Transitions\SecondStateToFirstStateTransition;
use Caner\StateMachine\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\MockObject\MockObject;

class BaseStateMachineTest extends TestCase
{
    /** @var MockObject $testModelMock */
    public MockObject $testModelMock;

    /** @var MockObject TestStateMachineMock */
    public MockObject $testStateMachineMock;

    /** @var BaseStateMachine TestStateMachineMock */
    public BaseStateMachine $testStateMachine;

    public function setUp(): void
    {
        $this->testModelMock = $this->createMock(TestModel::class);
        $this->testStateMachineMock = $this->getMockBuilder(TestStateMachine::class)
            ->setConstructorArgs([$this->testModelMock, 'status'])
            ->getMock();

        $this->testStateMachine = new TestStateMachine($this->testModelMock, 'status');

        parent::setUp();
    }

    #[Test]
    public function it_should_return_valid_initial_state_value(): void
    {
        $this->assertEquals($this->testStateMachine->initialState(), TestStateEnums::FirstState);
    }

    #[Test]
    public function it_should_return_valid_states(): void
    {
        $this->assertEquals($this->testStateMachine->states(), [
            TestStateEnums::FirstState      => FirstState::class,
            TestStateEnums::SecondState     => SecondState::class,
        ]);
    }

    #[Test]
    public function it_should_return_valid_transitions(): void
    {
        $this->assertEquals($this->testStateMachine->transitions(), [
            TestStateMachine::class => [
                $this->testStateMachine->initialState() => FirstStateToFirstStateTransition::class,
            ],
            FirstState::class => [
                SecondState::class => FirstStateToSecondStateTransition::class,
            ],
            SecondState::class => [
                FirstState::class => SecondStateToFirstStateTransition::class,
            ],
        ]);
    }

    #[Test]
    public function it_should_return_valid_model(): void
    {
        $this->assertEquals($this->testStateMachine->getModel(), $this->testModelMock);
    }

    #[Test]
    public function it_should_return_valid_state(): void
    {
        $this->assertNull($this->testStateMachine->getState());
    }

    #[Test]
    public function it_should_return_valid_possible_transitions(): void
    {
        $this->testStateMachine = new FirstState($this->testModelMock, 'status');

        $this->assertEquals($this->testStateMachine->getPossibleTransitions(), [
            SecondState::class,
        ]);
    }

    #[Test]
    public function it_should_throw_transition_not_found_exception(): void
    {
        $this->expectException(TransitionNotFoundException::class);

        $this->testStateMachine = new FirstState($this->testModelMock, 'status');
        $this->testStateMachine->transitionTo(FirstState::class);
    }

    #[Test]
    public function it_should_work_well_transition_to_method(): void
    {
        $this->testStateMachine = new FirstState($this->testModelMock, 'status');

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $this->assertEquals($this->testStateMachine->transitionTo(SecondState::class), $this->testModelMock);
    }

    #[Test]
    public function it_should_write_log_if_config_is_true(): void
    {
        $this->expectException(TransitionFailedException::class);

        $this->testStateMachine = new FirstState($this->testModelMock, 'status');

        DB::shouldReceive('beginTransaction')->andThrow(new \Exception());
        DB::shouldReceive('rollBack')->once();
        Config::shouldReceive('get')
            ->once()
            ->with('state-machine.error_logs', true)
            ->andReturn(true);
        Log::shouldReceive('error')
            ->once();

        $this->testStateMachine->transitionTo(SecondState::class);
    }

    #[Test]
    public function it_should_not_write_log_if_config_is_false(): void
    {
        $this->expectException(TransitionFailedException::class);

        $this->testStateMachine = new FirstState($this->testModelMock, 'status');

        DB::shouldReceive('beginTransaction')->andThrow(new \Exception());
        DB::shouldReceive('rollBack')->once();
        Config::shouldReceive('get')
            ->once()
            ->with('state-machine.error_logs', true)
            ->andReturn(false);
        Log::shouldReceive('error')
            ->never();

        $this->testStateMachine->transitionTo(SecondState::class);
    }
}
