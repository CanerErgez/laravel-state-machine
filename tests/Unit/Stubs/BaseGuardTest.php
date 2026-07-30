<?php

namespace Caner\StateMachine\Tests\Unit\Stubs;

use Caner\StateMachine\Events\GuardCompletedEvent;
use Caner\StateMachine\Tests\Stubs\Guards\TestGuard;
use Caner\StateMachine\Tests\Stubs\Models\TestModel;
use Caner\StateMachine\Tests\Stubs\TestStateMachine;
use Caner\StateMachine\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;

class BaseGuardTest extends TestCase
{
    public MockObject $testModelMock;
    public MockObject $testStateMachineMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->testModelMock = $this->createMock(TestModel::class);
        $this->testStateMachineMock = $this->getMockBuilder(TestStateMachine::class)
            ->setConstructorArgs([$this->testModelMock, 'status'])
            ->getMock();
    }

    #[Test]
    public function it_should_return_valid_request_data(): void
    {
        $request = new Request(['test' => 'test']);
        $guard = new TestGuard($this->testStateMachineMock, $request);

        $this->assertSame($request->toArray(), $guard->getRequestData());
    }

    #[Test]
    public function it_should_return_empty_request_data_when_request_is_null(): void
    {
        $guard = new TestGuard($this->testStateMachineMock);

        $this->assertSame([], $guard->getRequestData());
    }

    #[Test]
    public function it_should_fire_completed_event(): void
    {
        Event::fake();

        $guard = new TestGuard($this->testStateMachineMock);
        $guard->completed();

        Event::assertDispatched(GuardCompletedEvent::class);
    }

    #[Test]
    public function it_should_return_right_check_result(): void
    {
        Event::fake();

        $guard = new TestGuard($this->testStateMachineMock);
        $result = $guard->check();

        $this->assertTrue($result->data['result']);
    }
}
