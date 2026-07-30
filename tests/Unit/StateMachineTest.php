<?php

namespace Caner\StateMachine\Tests\Unit;

use Caner\StateMachine\StateMachineServiceProvider;
use Caner\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class StateMachineTest extends TestCase
{
    #[Test]
    public function package_boots(): void
    {
        $this->assertTrue($this->app->providerIsLoaded(
            StateMachineServiceProvider::class
        ));
    }
}
