<?php

namespace Caner\StateMachine\Concerns;

use Caner\StateMachine\Events\GuardRejected;
use Caner\StateMachine\Events\StateChanged;
use Caner\StateMachine\Exceptions\GuardErrorException;
use Caner\StateMachine\Exceptions\GuardResultNotFoundException;
use Caner\StateMachine\Exceptions\StateNotFoundException;
use Caner\StateMachine\Interfaces\TransitionInterface;
use Caner\StateMachine\Support\TransitionContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class BaseTransition implements TransitionInterface
{
    public bool $isRunAllGuards = false;

    public bool $isRunAllAfterActions = false;

    public bool $automaticStateUpdate = false;

    public function __construct(
        public BaseStateMachine $baseStateMachine,
        public TransitionContext $context,
        public string $targetClass,
    ) {}

    public function handle(): Model
    {
        $this->runGuards();
        $model = $this->action();
        $this->updateState($model);

        // After actions run before commit. Implementations may dispatch a queued
        // job with ->afterCommit() when post-commit execution is required.
        $this->runAfterActions();

        return $model;
    }

    public function name(): string
    {
        return Str::of(class_basename($this))->beforeLast('Transition')->snake()->toString();
    }

    /** @return array<string, mixed> */
    public function metadata(): array
    {
        return [];
    }

    /** @return array<class-string<BaseGuard>> */
    abstract public function guards(): array;

    abstract public function action(): Model;

    /** @return array<class-string<BaseAfterAction>> */
    abstract public function afterActions(): array;

    public function runGuards(): void
    {
        foreach ($this->guards() as $guardClass) {
            if (config('state-machine.guard_condition_logs', true)) {
                Log::debug($guardClass.' Started.');
            }

            $guard = app()->make($guardClass, [
                'baseStateMachine' => $this->baseStateMachine,
                'context' => $this->context,
            ]);
            $result = $guard->check();

            try {
                $this->checkGuardData($result, $guardClass);
            } catch (GuardErrorException|GuardResultNotFoundException $exception) {
                event(new GuardRejected(
                    $this->baseStateMachine->getModel(),
                    $guardClass,
                    $this->context,
                    $exception,
                ));

                throw $exception;
            }

            if (isset($result->data['data'])) {
                $this->context = $this->context->mergeData($result->data['data']);
            }

            if (config('state-machine.guard_condition_logs', true)) {
                Log::debug($guardClass.' Success.');
            }
        }

        $this->isRunAllGuards = true;
    }

    public function runAfterActions(): void
    {
        foreach ($this->afterActions() as $afterActionClass) {
            if (config('state-machine.after_action_logs', true)) {
                Log::debug($afterActionClass.' Started.');
            }

            $afterAction = app()->make($afterActionClass, [
                'baseStateMachine' => $this->baseStateMachine,
                'context' => $this->context,
            ]);
            $afterAction->handle();

            if (config('state-machine.after_action_logs', true)) {
                Log::debug($afterActionClass.' Success.');
            }
        }

        $this->isRunAllAfterActions = true;
    }

    public function checkGuardData(mixed $result, string $guardClass): void
    {
        if (! isset($result->data['result'])) {
            throw new GuardResultNotFoundException(
                $guardClass.' did not return result data.'
            );
        }

        if ($result->data['result'] !== true) {
            $error = $result->data['result']['error'] ?? '';

            throw new GuardErrorException(
                trim($guardClass.' rejected the transition. '.$error)
            );
        }
    }

    public function updateState(Model $model): void
    {
        if (! $this->automaticStateUpdate) {
            return;
        }

        $targetStateValue = array_search(
            $this->targetClass,
            $this->baseStateMachine->states(),
            true,
        );

        if ($targetStateValue === false) {
            throw new StateNotFoundException(
                "Target state [{$this->targetClass}] is not defined."
            );
        }

        $fromState = $this->baseStateMachine->getState();
        $model->update([
            $this->baseStateMachine->mainAttribute => $targetStateValue,
        ]);
        $this->baseStateMachine->model = $model;

        event(new StateChanged(
            $model,
            $fromState,
            $this->targetClass,
            $this->context,
        ));
    }

    /** @deprecated Use updateState() */
    public function updateStatus(): void
    {
        $this->updateState($this->baseStateMachine->getModel());
    }
}
