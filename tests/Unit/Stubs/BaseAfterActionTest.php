<?php

namespace Caner\StateMachine\Tests\Unit\Stubs;

use Caner\StateMachine\Events\AfterActionCompletedEvent;
use Caner\StateMachine\Support\TransitionContext;
use Caner\StateMachine\Tests\Stubs\AfterActions\TestAfterAction;
use Caner\StateMachine\Tests\Stubs\Models\TestModel;
use Caner\StateMachine\Tests\Stubs\TestStateMachine;
use Caner\StateMachine\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;

class BaseAfterActionTest extends TestCase
{
    #[Test]
    public function it_receives_context_and_fires_the_legacy_completed_event(): void
    {
        Event::fake();
        $model = TestModel::create(['status' => 1]);
        $action = new TestAfterAction(
            new TestStateMachine($model, 'status'),
            new TransitionContext(data: ['order_id' => 10]),
        );

        $this->assertSame(['order_id' => 10], $action->data);
        $action->handle();

        Event::assertDispatched(AfterActionCompletedEvent::class);
    }
}
