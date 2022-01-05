<?php

namespace Caner\StateMachine\Tests\Unit\Stubs;

use Caner\StateMachine\Events\GuardCompletedEvent;
use Caner\StateMachine\Tests\Stubs\Guards\TestGuard;
use Caner\StateMachine\Tests\Stubs\Models\TestModel;
use Caner\StateMachine\Tests\Stubs\TestStateMachine;
use Caner\StateMachine\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\MockObject\MockObject;

class BaseGuardTest extends TestCase
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
    public function it_should_return_valid_request_data()
    {
        $requestArray = new Request(['test' => 'test']);

        $testGuardMock = $this->getMockBuilder(TestGuard::class)
            ->setConstructorArgs([$this->testStateMachineMock, $requestArray])
            ->onlyMethods(['check'])
            ->getMock();

        $this->assertEquals($testGuardMock->getRequestData(), $requestArray->toArray());
    }

    /**
     * @test
     */
    public function it_should_fire_completed_event()
    {
        Event::fake();

        $requestArray = new Request(['test' => 'test']);

        $testGuardMock = $this->getMockBuilder(TestGuard::class)
            ->setConstructorArgs([$this->testStateMachineMock, $requestArray])
            ->onlyMethods(['check'])
            ->getMock();

        $testGuardMock->completed();

        Event::assertDispatched(GuardCompletedEvent::class);
    }

    /**
     * @test
     */
    public function it_should_return_right_check_result()
    {
        Event::fake();

        $requestArray = new Request(['test' => 'test']);

        $testGuardMock = $this->getMockBuilder(TestGuard::class)
            ->setConstructorArgs([$this->testStateMachineMock, $requestArray])
            ->onlyMethods(['completed'])
            ->getMock();

        $result = $testGuardMock->check();

        $this->assertEquals($result->data['result'], true);
    }
}
