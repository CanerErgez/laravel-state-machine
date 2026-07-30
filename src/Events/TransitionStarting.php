<?php

namespace Caner\StateMachine\Events;

use Caner\StateMachine\Support\TransitionContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;

final readonly class TransitionStarting
{
    use Dispatchable;

    public function __construct(
        public Model $model,
        public string $fromState,
        public string $toState,
        public string $transition,
        public TransitionContext $context,
    ) {}
}
