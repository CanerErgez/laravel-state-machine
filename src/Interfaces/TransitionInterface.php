<?php

namespace Caner\StateMachine\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface TransitionInterface
{
    public function handle(): Model;

    public function action(): Model;

    /** @return array<class-string> */
    public function guards(): array;

    public function runGuards(): void;

    /** @return array<class-string> */
    public function afterActions(): array;

    public function runAfterActions(): void;
}
