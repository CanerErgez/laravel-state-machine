<?php

namespace Caner\StateMachine\Concerns;

use Caner\StateMachine\Events\GuardCompletedEvent;
use Caner\StateMachine\Interfaces\BaseGuardInterface;
use Illuminate\Http\Request;

abstract class BaseGuard implements BaseGuardInterface
{
    public function __construct(
        public BaseStateMachine $baseStateMachine,
        public ?Request $request = null,
        public array $data = []
    ) {
    }

    abstract public function check(): self;

    public function getRequestData(): array
    {
        return $this->request?->toArray() ?? [];
    }

    public function completed(): void
    {
        event(new GuardCompletedEvent($this::class));
    }
}
