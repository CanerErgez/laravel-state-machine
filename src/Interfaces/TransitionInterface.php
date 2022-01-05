<?php

namespace Caner\StateMachine\Interfaces;

use Caner\StateMachine\Concerns\BaseStateMachine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface TransitionInterface
{
    public function __construct(BaseStateMachine $stateMachine, ?Request $request = null, array $data = []);

    public function handle(): Model;

    public function action(): Model;

    public function guards();

    public function runGuards();

    public function afterActions();

    public function runAfterActions();
}
