<?php

namespace Caner\StateMachine\Tests\Unit\Stubs;

use Caner\StateMachine\Events\AfterActionCompletedEvent;
use Caner\StateMachine\Events\GuardCompletedEvent;
use Caner\StateMachine\Exceptions\GuardErrorException;
use Caner\StateMachine\Exceptions\GuardResultNotFoundException;
use Caner\StateMachine\Tests\Stubs\AfterActions\TestAfterAction;
use Caner\StateMachine\Tests\Stubs\Guards\TestGuard;
use Caner\StateMachine\Tests\Stubs\Models\TestModel;
use Caner\StateMachine\Tests\Stubs\TestStateMachine;
use Caner\StateMachine\Tests\Stubs\Transitions\FirstStateToSecondStateTransition;
use Caner\StateMachine\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\MockObject\MockObject;

class BaseTransitionTest extends TestCase
{
    /** @var MockObject $testModelMock */
    public MockObject $testModelMock;

    /** @var MockObject $testStateMachineMock */
    public MockObject $testStateMachineMock;

    /** @var MockObject $testTransitionMock */
    public MockObject $testTransitionMock;

    /** @var FirstStateToSecondStateTransition $testTransition */
    public FirstStateToSecondStateTransition $testTransition;

    public function setUp(): void
    {
        $this->testModelMock = $this->createMock(TestModel::class);
        $this->testStateMachineMock = $this->getMockBuilder(TestStateMachine::class)
            ->setConstructorArgs([$this->testModelMock, 'status'])
            ->getMock();

        $this->testTransitionMock = $this->getMockBuilder(FirstStateToSecondStateTransition::class)
            ->setConstructorArgs([$this->testStateMachineMock])
            ->getMock();
        $this->testTransition = new FirstStateToSecondStateTransition($this->testStateMachineMock);

        parent::setUp();
    }

    /** @test */
    public function it_should_run_related_methods_and_return_valid_model()
    {
        $this->testTransitionMock = $this->getMockBuilder(FirstStateToSecondStateTransition::class)
            ->setConstructorArgs([$this->testStateMachineMock])
            ->onlyMethods(['runGuards', 'action', 'runAfterActions'])
            ->getMock();

        $this->testTransitionMock->expects($this->once())
            ->method('runGuards')
            ->willReturn(null);

        $this->testTransitionMock->expects($this->once())
            ->method('action')
            ->willReturn($this->testModelMock);

        $this->testTransitionMock->expects($this->once())
            ->method('runAfterActions')
            ->willReturn(null);

        $this->assertEquals($this->testTransitionMock->handle(), $this->testModelMock);
    }

    /** @test */
    public function it_should_run_return_valid_guards()
    {
        $this->assertEquals($this->testTransition->guards(), [
            TestGuard::class,
        ]);
    }

    /** @test */
    public function it_should_run_return_model_when_action_is_right()
    {
        $this->testStateMachineMock->expects($this->once())
            ->method('getModel')
            ->willReturn($this->testModelMock);

        $this->testTransition->action();
    }

    /** @test */
    public function it_should_run_return_valid_after_actions()
    {
        $this->assertEquals($this->testTransition->afterActions(), [
            TestAfterAction::class,
        ]);
    }

    /** @test */
    public function it_should_write_guard_logs_well_when_config_is_right()
    {
        Event::fake();

        Config::shouldReceive('get')
            ->twice()
            ->with('state-machine.guard_condition_logs', true)
            ->andReturn(true);
        Log::shouldReceive('debug')
            ->twice();

        $this->testTransition->runGuards();

        Event::assertDispatched(fn (GuardCompletedEvent $event) => $event->guard === TestGuard::class);
    }

    /** @test */
    public function it_should_not_write_guard_logs_well_when_config_is_wrong()
    {
        Event::fake();

        Config::shouldReceive('get')
            ->twice()
            ->with('state-machine.guard_condition_logs', true)
            ->andReturn(false);
        Log::shouldReceive('debug')
            ->never();

        $this->testTransition->runGuards();

        Event::assertDispatched(fn (GuardCompletedEvent $event) => $event->guard === TestGuard::class);
    }

    /** @test */
    public function it_should_write_after_action_logs_well_when_config_is_right()
    {
        Event::fake();

        Config::shouldReceive('get')
            ->twice()
            ->with('state-machine.after_action_logs', true)
            ->andReturn(true);
        Log::shouldReceive('debug')
            ->twice();

        $this->testTransition->runAfterActions();

        Event::assertDispatched(fn (AfterActionCompletedEvent $event) => $event->afterAction === TestAfterAction::class);
    }

    /** @test */
    public function it_should_not_write_after_action_logs_well_when_config_is_wrong()
    {
        Event::fake();

        Config::shouldReceive('get')
            ->twice()
            ->with('state-machine.after_action_logs', true)
            ->andReturn(false);
        Log::shouldReceive('debug')
            ->never();

        $this->testTransition->runAfterActions();

        Event::assertDispatched(fn (AfterActionCompletedEvent $event) => $event->afterAction === TestAfterAction::class);
    }

    /** @test */
    public function it_should_throw_guard_result_not_found_exception_when_guard_result_have_not_result()
    {
        $this->expectException(GuardResultNotFoundException::class);

        $testGuard = new TestGuard($this->testStateMachineMock);

        $obj = new \stdClass();
        $obj->data = null;

        $this->testTransition->checkGuardData($obj ,$testGuard);
    }

    /** @test */
    public function it_should_throw_guard_error_exception_when_guard_result_is_false()
    {
        $this->expectException(GuardErrorException::class);

        $testGuard = new TestGuard($this->testStateMachineMock);

        $obj = new \stdClass();
        $obj->data = ['result' => false];

        $this->testTransition->checkGuardData($obj ,$testGuard);
    }
}
