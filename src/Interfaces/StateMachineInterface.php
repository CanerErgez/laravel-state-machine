<?php

namespace Caner\StateMachine\Interfaces;

use Illuminate\Http\Request;

interface StateMachineInterface
{
    public function __construct();

    public function getModel();

    public function initialState();

    public function states();

    public function transitions();

    public function transitionTo(string $targetClass, ?Request $request = null, array $data = []);
}
