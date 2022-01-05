<?php

namespace Caner\StateMachine\Concerns;

use Caner\StateMachine\Exceptions\TransitionFailedException;
use Caner\StateMachine\Exceptions\TransitionNotFoundException;
use Caner\StateMachine\Interfaces\StateMachineInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseStateMachine implements StateMachineInterface
{
    /**
     * State Machine Base Model.
     *
     * @var Model|null
     */
    public ?Model $model;

    /**
     * State Machine Main Model Attribute.
     *
     * @var string|null
     */
    public ?string $mainAttribute;

    /**
     * StateMachine constructor.
     * @param Model|null $model
     * @param string|null $mainAttribute
     */
    public function __construct(?Model $model = null, ?string $mainAttribute = null)
    {
        $this->model = $model;
        $this->mainAttribute = $mainAttribute;
    }

    /**
     * It returns selected model.
     *
     * @return Model|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Initial State Method.
     *
     * @return mixed
     */
    abstract public function initialState();

    /**
     * All used states definition.
     *
     * @return mixed
     */
    abstract public function states();

    /**
     * All used transitions definition.
     *
     * @return mixed
     */
    abstract public function transitions();

    /**
     * It returns selected model's state.
     *
     * @return mixed
     */
    public function getState()
    {
        return $this->states()[$this?->getModel()?->{$this->mainAttribute}] ?? null;
    }

    /**
     * It returns possible transitions.
     *
     * @return null|array
     */
    public function getPossibleTransitions(): ?array
    {
        if (isset($this->transitions()[get_class($this)])) {
            return array_keys($this->transitions()[get_class($this)]);
        }

        return null;
    }

    /**
     * It runs state change logic.
     *
     * @param string $targetClass
     * @param Request|null $request
     * @param array $data
     * @throws TransitionNotFoundException
     * @throws TransitionFailedException
     */
    public function transitionTo(string $targetClass, ?Request $request = null, array $data = [])
    {
        /**
         * It should throw 'TransitionNotFoundException' when transition is not found
         */
        if (!isset($this->transitions()[get_class($this)][$targetClass])) {
            $error = 'Transition Not Found : Class '.get_class($this).' to '.$targetClass;
            throw new TransitionNotFoundException($error);
        }

        /**
         * Get current state to target state transition
         */
        $transitionClass = $this->transitions()[get_class($this)][$targetClass];

        /**
         * Transition Instance Getting.
         */
        $transitionInstance = new $transitionClass($this, $request, $data);

        /**
         * Transition are running
         */
        try {
            DB::beginTransaction();

            $this->model = $transitionInstance->handle();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $errorMessage = "{$e->getMessage()} ({$e->getFile()}:{$e->getLine()}){$e->getTraceAsString()}";

            if (config('state-machine.error_logs', true)) {
                Log::error($errorMessage);
            }

            throw new TransitionFailedException($errorMessage);
        }

        return $this->model;
    }
}
