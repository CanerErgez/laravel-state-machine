<?php

namespace Caner\StateMachine\Tests\Unit\Stubs;

use Caner\StateMachine\Events\AfterActionCompletedEvent;
use Caner\StateMachine\Tests\Stubs\AfterActions\TestAfterAction;
use Caner\StateMachine\Tests\Stubs\Models\TestModel;
use Caner\StateMachine\Tests\Stubs\TestStateMachine;
use Caner\StateMachine\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\MockObject\MockObject;

class BaseAfterActionTest extends TestCase
{
    /** @var MockObject $testModelMock */
    public MockObject $testModelMock;

    /** @var MockObject $testStateMachineMock */
    public MockObject $testStateMachineMock;

    public function setUp(): void
    {
        $this->testModelMock = $this->createMock(TestModel::class);
        $this->testStateMachineMock = $this->getMockBuilder(TestStateMachine::class)
            ->setConstructorArgs([$this->testModelMock, 'status'])
            ->getMock();

        parent::setUp();
    }

    /**
     * @test
     */
    public function it_should_fire_completed_event()
    {
        Event::fake();

        $testAfterActionMock = $this->getMockBuilder(TestAfterAction::class)
            ->setConstructorArgs([$this->testStateMachineMock])
            ->onlyMethods(['handle'])
            ->getMock();

        $testAfterActionMock->completed();

        Event::assertDispatched(AfterActionCompletedEvent::class);
    }

    /**
     * @test
     */
    public function it_should_return_right_check_result()
    {
        $requestArray = new Request(['test' => 'test']);

        $testAfterAction= $this->getMockBuilder(TestAfterAction::class)
            ->setConstructorArgs([$this->testStateMachineMock, $requestArray])
            ->onlyMethods(['completed'])
            ->getMock();

        $result = $testAfterAction->handle();

        $this->assertEquals($result, true);
    }
}
