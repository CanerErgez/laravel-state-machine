<?php

namespace Caner\StateMachine\Concerns;

use Caner\StateMachine\Events\GuardCompletedEvent;
use Caner\StateMachine\Interfaces\BaseGuardInterface;
use Illuminate\Http\Request;

abstract class BaseGuard implements BaseGuardInterface
{
    /**
     * BaseGuard constructor.
     * @param BaseStateMachine $baseStateMachine
     * @param Request|null $request
     * @param array $data
     */
    public function __construct(
        public BaseStateMachine $baseStateMachine,
        public ?Request $request = null,
        public array $data = []
    ) {
    }

    abstract public function check(): self;

    /**
     * @return array
     */
    public function getRequestData(): array
    {
        return $this->request->toArray() ?? [];
    }

    /**
     * @return void
     */
    public function completed(): void
    {
        event(new GuardCompletedEvent(get_class($this)));
    }
}
