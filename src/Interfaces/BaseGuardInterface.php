<?php

namespace Caner\StateMachine\Interfaces;

use Caner\StateMachine\Concerns\BaseStateMachine;
use Illuminate\Http\Request;

interface BaseGuardInterface
{
    public function __construct(
        BaseStateMachine $stateMachine,
        ?Request $request = null,
        array $data = []
    );

    public function check(): self;

    public function getRequestData(): array;

    public function completed();
}
