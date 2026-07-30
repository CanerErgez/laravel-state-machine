<?php

namespace Caner\StateMachine\Interfaces;

use BackedEnum;
use Caner\StateMachine\Support\TransitionContext;
use Illuminate\Database\Eloquent\Model;

interface StateMachineInterface
{
    public function getModel(): Model;

    public function initialState(): int|string|BackedEnum;

    /** @return array<int|string, class-string> */
    public function states(): array;

    /** @return array<class-string, array<class-string, class-string>> */
    public function transitions(): array;

    public function canTransitionTo(string $targetClass): bool;

    /** @return array<class-string> */
    public function allowedTransitions(): array;

    /** @return array<int, array{name: string, state: class-string, transition: class-string, metadata: array}> */
    public function allowedTransitionDetails(?TransitionContext $context = null): array;

    public function transitionTo(string $targetClass, ?TransitionContext $context = null): Model;
}
