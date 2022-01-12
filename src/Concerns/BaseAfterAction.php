<?php

namespace Caner\StateMachine\Concerns;

use Caner\StateMachine\Events\AfterActionCompletedEvent;
use Caner\StateMachine\Interfaces\BaseAfterActionInterface;
use Illuminate\Http\Request;

abstract class BaseAfterAction implements BaseAfterActionInterface
{
    /**
     * BaseAfterAction constructor.
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

    abstract public function handle();

    /**
     * @return void
     */
    public function completed(): void
    {
        event(new AfterActionCompletedEvent(get_class($this)));
    }
}
