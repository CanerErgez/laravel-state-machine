<?php

namespace Caner\StateMachine\Interfaces;

use Caner\StateMachine\Concerns\BaseStateMachine;
use Illuminate\Http\Request;

interface BaseAfterActionInterface
{
    public function __construct(
        BaseStateMachine $stateMachine,
        ?Request $request = null,
        array $data = []
    );

    public function handle();

    public function completed();
}
