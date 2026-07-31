<?php

namespace Caner\StateMachine\Events;

use Caner\StateMachine\Support\TransitionContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Throwable;

final readonly class GuardRejected
{
    use Dispatchable;

    public function __construct(
        public Model $model,
        public string $guard,
        public TransitionContext $context,
        public Throwable $exception,
    ) {}
}
