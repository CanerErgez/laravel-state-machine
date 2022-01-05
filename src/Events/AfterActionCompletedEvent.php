<?php

namespace Caner\StateMachine\Events;

use Caner\StateMachine\Concerns\BaseAfterAction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AfterActionCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public string $afterAction)
    {
        //
    }
}
