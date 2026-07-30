<?php

namespace Caner\StateMachine\Interfaces;

interface BaseGuardInterface
{
    public function check(): self;

    /** @return array<string, mixed> */
    public function getRequestData(): array;

    public function completed(): void;
}
