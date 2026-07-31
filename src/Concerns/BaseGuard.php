<?php

namespace Caner\StateMachine\Concerns;

use Caner\StateMachine\Events\GuardCompletedEvent;
use Caner\StateMachine\Interfaces\BaseGuardInterface;
use Caner\StateMachine\Support\TransitionContext;

abstract class BaseGuard implements BaseGuardInterface
{
    /** @var array<string, mixed> */
    public array $data;

    public function __construct(
        public BaseStateMachine $baseStateMachine,
        public TransitionContext $context
    ) {
        $this->data = $context->data;
    }

    abstract public function check(): self;

    /** @return array<string, mixed> */
    public function getRequestData(): array
    {
        return $this->context->data;
    }

    public function completed(): void
    {
        event(new GuardCompletedEvent($this::class));
    }
}
