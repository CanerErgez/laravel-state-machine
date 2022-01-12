<?php

namespace Caner\StateMachine\Tests\Stubs\AfterActions;

use Caner\StateMachine\Concerns\BaseAfterAction;

class TestAfterAction extends BaseAfterAction
{
    public function handle()
    {
        $this->completed();

        return true;
    }
}
