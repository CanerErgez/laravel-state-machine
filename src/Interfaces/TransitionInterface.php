<?php

namespace Caner\StateMachine\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface TransitionInterface
{
    public function handle(): Model;

    public function action(): Model;

    public function guards(): array;

    public function runGuards(): void;

    public function afterActions(): array;

    public function runAfterActions(): void;
}
