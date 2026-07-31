<?php

namespace Caner\StateMachine\Interfaces;

interface BaseAfterActionInterface
{
    public function handle(): void;

    public function completed(): void;
}
