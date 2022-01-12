<?php

namespace Caner\StateMachine\Tests\Stubs\Guards;

use Caner\StateMachine\Concerns\BaseGuard;

class TestGuard extends BaseGuard
{
    public function check(): BaseGuard
    {
        $this->data['result'] = true;

        $this->completed();

        return $this;
    }
}
