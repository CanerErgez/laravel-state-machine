<?php

namespace Caner\StateMachine\Concerns;

use BackedEnum;
use Caner\StateMachine\Events\TransitionCompleted;
use Caner\StateMachine\Events\TransitionFailed;
use Caner\StateMachine\Events\TransitionStarting;
use Caner\StateMachine\Exceptions\GuardErrorException;
use Caner\StateMachine\Exceptions\GuardResultNotFoundException;
use Caner\StateMachine\Exceptions\StateNotFoundException;
use Caner\StateMachine\Exceptions\TransitionFailedException;
use Caner\StateMachine\Exceptions\TransitionNotFoundException;
use Caner\StateMachine\Interfaces\StateMachineInterface;
use Caner\StateMachine\Support\TransitionContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseStateMachine implements StateMachineInterface
{
    public function __construct(
        public Model $model,
        public string $mainAttribute,
    ) {
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    abstract public function initialState(): int|string|BackedEnum;

    /** @return array<int|string, class-string<BaseStateMachine>> */
    abstract public function states(): array;

    /** @return array<class-string, array<class-string, class-string<BaseTransition>>> */
    abstract public function transitions(): array;

    /** @return class-string<BaseStateMachine> */
    public function getState(): string
    {
        $value = $this->model->getAttribute($this->mainAttribute) ?? $this->initialState();
        $value = $value instanceof BackedEnum ? $value->value : $value;
        $state = $this->states()[$value] ?? null;

        if ($state === null) {
            throw new StateNotFoundException("State [{$value}] is not defined.");
        }

        return $state;
    }

    /** @return array<class-string> */
    public function allowedTransitions(): array
    {
        return array_keys($this->transitions()[$this->getState()] ?? []);
    }

    /** @deprecated Use allowedTransitions() */
    public function getPossibleTransitions(): ?array
    {
        $transitions = $this->allowedTransitions();

        return $transitions === [] ? null : $transitions;
    }

    public function canTransitionTo(string $targetClass): bool
    {
        return isset($this->transitions()[$this->getState()][$targetClass]);
    }

    public function transitionTo(string $targetClass, ?TransitionContext $context = null): Model
    {
        $fromState = $this->getState();
        $transitionClass = $this->transitions()[$fromState][$targetClass] ?? null;

        if ($transitionClass === null) {
            throw new TransitionNotFoundException(
                "Transition not found: {$fromState} to {$targetClass}."
            );
        }

        $context ??= new TransitionContext();
        $transition = app()->make($transitionClass, [
            'baseStateMachine' => $this,
            'context' => $context,
            'targetClass' => $targetClass,
        ]);

        event(new TransitionStarting(
            $this->model,
            $fromState,
            $targetClass,
            $transitionClass,
            $context,
        ));

        try {
            $this->model = $this->model->getConnection()->transaction(
                fn (): Model => $transition->handle()
            );
        } catch (GuardErrorException|GuardResultNotFoundException $exception) {
            event(new TransitionFailed(
                $this->model,
                $fromState,
                $targetClass,
                $transitionClass,
                $context,
                $exception,
            ));

            throw $exception;
        } catch (Throwable $exception) {
            if (config('state-machine.error_logs', true)) {
                Log::error('State transition failed.', [
                    'exception' => $exception,
                    'from' => $fromState,
                    'to' => $targetClass,
                    'transition' => $transitionClass,
                ]);
            }

            event(new TransitionFailed(
                $this->model,
                $fromState,
                $targetClass,
                $transitionClass,
                $context,
                $exception,
            ));

            throw new TransitionFailedException(
                'Transition failed.',
                previous: $exception,
            );
        }

        event(new TransitionCompleted(
            $this->model,
            $fromState,
            $targetClass,
            $transitionClass,
            $context,
        ));

        return $this->model;
    }
}
