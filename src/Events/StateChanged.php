<?php

namespace Caner\StateMachine\Events;

use Caner\StateMachine\Support\TransitionContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;

final readonly class StateChanged
{
    use Dispatchable;

    public function __construct(
        public Model $model,
        public string $fromState,
        public string $toState,
        public TransitionContext $context,
    ) {
    }
}
