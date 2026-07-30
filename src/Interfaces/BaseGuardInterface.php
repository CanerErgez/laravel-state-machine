<?php

namespace Caner\StateMachine\Interfaces;

interface BaseGuardInterface
{
    public function check(): self;

    public function getRequestData(): array;

    public function completed(): void;
}
