<?php


namespace Caner\StateMachine\Concerns;


use Caner\StateMachine\Exceptions\GuardErrorException;
use Caner\StateMachine\Exceptions\GuardResultNotFoundException;
use Caner\StateMachine\Interfaces\TransitionInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

abstract class BaseTransition implements TransitionInterface, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public bool $isRunAllGuards = false;
    public bool $isRunAllAfterActions = false;

    public BaseStateMachine $baseStateMachine;
    public ?Request $request;
    public array $data;

    /**
     * BaseTransition constructor.
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

    /**
     * @return Model
     * @throws GuardErrorException
     * @throws GuardResultNotFoundException
     */
    public function handle(): Model
    {
        $this->runGuards();

        $model = $this->action();

        $this->runAfterActions();

        return $model;
    }

    abstract public function guards();

    /**
     * @return Model
     */
    abstract public function action(): Model;

    abstract public function afterActions();

    /**
     * @throws GuardErrorException
     * @throws GuardResultNotFoundException
     */
    public function runGuards()
    {
        foreach ($this->guards() as $guard) {
            if (config('state-machine.guard_condition_logs', true)) {
                Log::debug($guard. ' Started.');
            }

            $result = (new $guard($this->baseStateMachine, $this->request, $this->data))->check();

            /** It checks guard result is valid and true */
            $this->checkGuardData($result, $guard);

            /** If result have an any data, merged the data */
            if (isset($result->data['data'])) {
                $this->data = array_merge($this->data, $result->data['data']);
            }

            if (config('state-machine.guard_condition_logs', true)) {
                Log::debug($guard. ' Success.');
            }
        }

        $this->isRunAllGuards = true;
    }

    public function runAfterActions()
    {
        foreach ($this->afterActions() as $afterAction) {
            if (config('state-machine.after_action_logs', true)) {
                Log::debug($afterAction. ' Started.');
            }

            (new $afterAction($this->baseStateMachine, $this->request, $this->data))->handle();

            if (config('state-machine.after_action_logs', true)) {
                Log::debug($afterAction. ' Success.');
            }
        }

        $this->isRunAllAfterActions = true;
    }

    /**
     * @param mixed $result
     * @param $guard
     * @throws GuardErrorException
     * @throws GuardResultNotFoundException
     */
    public function checkGuardData(mixed $result, $guard): void
    {
        /** If data have not any result, return error */
        if (!isset($result->data['result'])) {
            $errorMessage = $guard::class . ' are not return any result data. Please check this guard.';
            throw new GuardResultNotFoundException($errorMessage);
        }

        /** If result are not true, finish guards and throw new Exception */
        if ($result->data['result'] !== true) {
            $errorMessage = $guard::class . ' are not return true result.';
            throw new GuardErrorException($errorMessage);
        }
    }
}
