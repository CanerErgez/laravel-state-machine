<?php

namespace Caner\StateMachine\Concerns;

use Caner\StateMachine\Events\AfterActionCompletedEvent;
use Caner\StateMachine\Interfaces\BaseAfterActionInterface;
use Caner\StateMachine\Support\TransitionContext;

abstract class BaseAfterAction implements BaseAfterActionInterface
{
    public array $data;

    /**
     * BaseAfterAction constructor.
     * @param BaseStateMachine $baseStateMachine
     * @param Request|null $request
     * @param array $data
     */
    public function __construct(
        public BaseStateMachine $baseStateMachine,
        public TransitionContext $context
    ) {
        $this->data = $context->data;
    }

    abstract public function handle(): void;

    /**
     * @return void
     */
    public function completed(): void
    {
        event(new AfterActionCompletedEvent($this::class));
    }
}
