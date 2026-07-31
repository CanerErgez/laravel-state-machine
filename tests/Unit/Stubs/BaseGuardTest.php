<?php

namespace Caner\StateMachine\Tests\Unit\Stubs;

use Caner\StateMachine\Events\GuardCompletedEvent;
use Caner\StateMachine\Support\TransitionContext;
use Caner\StateMachine\Tests\Stubs\Guards\TestGuard;
use Caner\StateMachine\Tests\Stubs\Models\TestModel;
use Caner\StateMachine\Tests\Stubs\TestStateMachine;
use Caner\StateMachine\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;

class BaseGuardTest extends TestCase
{
    #[Test]
    public function it_exposes_context_data_without_an_http_request(): void
    {
        $model = TestModel::create(['status' => 1]);
        $context = new TransitionContext(data: ['test' => 'value']);
        $guard = new TestGuard(new TestStateMachine($model, 'status'), $context);

        $this->assertSame(['test' => 'value'], $guard->getRequestData());
    }

    #[Test]
    public function it_fires_the_legacy_completed_event(): void
    {
        Event::fake();
        $model = TestModel::create(['status' => 1]);
        $guard = new TestGuard(
            new TestStateMachine($model, 'status'),
            new TransitionContext,
        );

        $guard->completed();

        Event::assertDispatched(GuardCompletedEvent::class);
    }
}
