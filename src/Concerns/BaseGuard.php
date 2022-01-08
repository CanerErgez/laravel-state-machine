<?php

namespace Caner\StateMachine\Concerns;

use Caner\StateMachine\Events\GuardCompletedEvent;
use Caner\StateMachine\Interfaces\BaseGuardInterface;
use Illuminate\Http\Request;

abstract class BaseGuard implements BaseGuardInterface
{
    public BaseStateMachine $baseStateMachine;
    public ?Request $request;
    public array $data;

    /**
     * BaseGuard constructor.
     * @param BaseStateMachine $stateMachine
     * @param Request|null $request
     * @param array $data
     */
    public function __construct(
        BaseStateMachine $stateMachine,
        ?Request $request = null,
        array $data = []
    ) {
        $this->baseStateMachine     = $stateMachine;
        $this->request              = $request;
        $this->data                 = $data;
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
