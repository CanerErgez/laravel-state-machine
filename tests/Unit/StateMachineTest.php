<?php

namespace Caner\StateMachine\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Caner\StateMachine\Tests\TestCase;

class StateMachineTest extends TestCase
{
    #[Test]
    public function package_boots(): void
    {
        $this->assertTrue($this->app->providerIsLoaded(
            \Caner\StateMachine\StateMachineServiceProvider::class
        ));
    }
}
