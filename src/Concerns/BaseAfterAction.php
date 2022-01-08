<?php

namespace Caner\StateMachine\Concerns;

use Caner\StateMachine\Events\AfterActionCompletedEvent;
use Caner\StateMachine\Interfaces\BaseAfterActionInterface;
use Illuminate\Http\Request;

abstract class BaseAfterAction implements BaseAfterActionInterface
{
    public BaseStateMachine $baseStateMachine;
    public ?Request $request;
    public array $data;

    /**
     * BaseAfterAction constructor.
     * @param BaseStateMachine $baseStateMachine
     * @param Request|null $request
     * @param array $data
     */
    public function __construct(BaseStateMachine $baseStateMachine, ?Request $request = null, array $data = [])
    {
        $this->baseStateMachine = $baseStateMachine;
        $this->request = $request;
        $this->data = $data;
    }

    abstract public function handle();

    /**
     * @return void
     */
    public function completed(): void
    {
        event(new AfterActionCompletedEvent(get_class($this)));
    }
}
