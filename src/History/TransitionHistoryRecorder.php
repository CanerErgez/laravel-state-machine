<?php

namespace Caner\StateMachine\History;

use Caner\StateMachine\Concerns\BaseTransition;
use Caner\StateMachine\Support\TransitionContext;
use Illuminate\Database\Eloquent\Model;

interface TransitionHistoryRecorder
{
    public function record(
        Model $model,
        string $attribute,
        string $fromState,
        string $toState,
        BaseTransition $transition,
        TransitionContext $context,
    ): void;
}
