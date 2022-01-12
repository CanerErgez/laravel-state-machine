<?php

namespace Caner\StateMachine\Tests\Unit\Stubs;

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

    /** @test */
    public function it_should_return_valid_initial_state_value()
    {
        $this->assertEquals($this->testStateMachine->initialState(), TestStateEnums::FirstState);
    }

    /** @test */
    public function it_should_return_valid_states()
    {
        $this->assertEquals($this->testStateMachine->states(), [
            TestStateEnums::FirstState      => FirstState::class,
            TestStateEnums::SecondState     => SecondState::class,
        ]);
    }

    /** @test */
    public function it_should_return_valid_transitions()
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

    /** @test */
    public function it_should_return_valid_model()
    {
        $this->assertEquals($this->testStateMachine->getModel(), $this->testModelMock);
    }

    /** @test */
    public function it_should_return_valid_state()
    {
        $this->assertNull($this->testStateMachine->getState());
    }

    /** @test */
    public function it_should_return_valid_possible_transitions()
    {
        $this->testStateMachine = new FirstState($this->testModelMock, 'status');

        $this->assertEquals($this->testStateMachine->getPossibleTransitions(), [
            SecondState::class,
        ]);
    }

    /** @test */
    public function it_should_throw_transition_not_found_exception()
    {
        $this->expectException(TransitionNotFoundException::class);

        $this->testStateMachine = new FirstState($this->testModelMock, 'status');
        $this->testStateMachine->transitionTo(FirstState::class);
    }

    /** @test */
    public function it_should_work_well_transition_to_method()
    {
        $this->testStateMachine = new FirstState($this->testModelMock, 'status');

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $this->assertEquals($this->testStateMachine->transitionTo(SecondState::class), $this->testModelMock);
    }

    /** @test */
    public function it_should_write_log_if_config_is_true()
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

    /** @test */
    public function it_should_not_write_log_if_config_is_false()
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
