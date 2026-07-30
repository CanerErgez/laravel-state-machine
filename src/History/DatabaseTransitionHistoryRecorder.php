<?php

namespace Caner\StateMachine\History;

use Caner\StateMachine\Concerns\BaseTransition;
use Caner\StateMachine\Support\TransitionContext;
use Illuminate\Database\Eloquent\Model;

final class DatabaseTransitionHistoryRecorder implements TransitionHistoryRecorder
{
    public function record(
        Model $model,
        string $attribute,
        string $fromState,
        string $toState,
        BaseTransition $transition,
        TransitionContext $context,
    ): void {
        if (!config('state-machine.history.enabled', false)) {
            return;
        }

        $model->getConnection()->table((string) config('state-machine.history.table'))->insert([
            'model_type' => $model->getMorphClass(),
            'model_id' => (string) $model->getKey(),
            'attribute' => $attribute,
            'from_state' => $fromState,
            'to_state' => $toState,
            'transition' => $transition::class,
            'transition_name' => $transition->name(),
            'actor_type' => $context->actor instanceof Model ? $context->actor->getMorphClass() : null,
            'actor_id' => $context->actor instanceof Model ? (string) $context->actor->getKey() : null,
            'metadata' => json_encode(array_merge($transition->metadata(), $context->metadata), JSON_THROW_ON_ERROR),
            'created_at' => now(),
        ]);
    }
}
