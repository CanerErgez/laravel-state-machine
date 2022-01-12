
# Laravel State Machine

[![Latest Version on Packagist](https://img.shields.io/packagist/v/caner/state-machine.svg?style=flat-square)](https://packagist.org/packages/caner/state-machine) 
[![Total Downloads](https://img.shields.io/packagist/dt/caner/state-machine.svg?style=flat-square)](https://packagist.org/packages/caner/state-machine) 
[![run-tests](https://github.com/CanerErgez/laravel-state-machine/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/CanerErgez/laravel-state-machine/actions/workflows/main.yml)

This package helps you to create State Machine data model-based services.

## Installation

You can install the package via composer:

```bash 
composer require caner/state-machine  
```   
## Usage

In `config/app.php`;
```php 
\Caner\StateMachine\StateMachineServiceProvider::class,  
```  

and publish config file;
```php 
php artisan vendor:publish --tag=caner-state-machine-config  
```  

### Step by step documentation

#### Introducing Concept
A state machine is a mathematical abstraction used to design algorithms. A state machine reads a set of inputs and changes to a different state based on those inputs.

A state is a description of the status of a system waiting to execute a transition. A transition is a set of actions to execute when a condition is fulfilled or an event received. In a state diagram, circles represent each possible state and arrows represent transitions between states.

Basically, we are building a `state` for each `status` and running related `transition` when changing the `status`.

![Sample State Change Workflow](https://github.com/CanerErgez/laravel-state-machine/raw/main/docs/img/1.png)

Each `Transition` should consist of 3 parts. These are the `guards`, `action` and `afterActions` methods.

![Sample Transition Workflow](https://github.com/CanerErgez/laravel-state-machine/raw/main/docs/img/2.png)

I prefer to use package in complex status changes.

Preferred Directory Tree;

- app
- - Services
- - - YourStateMachine
- - - - AfterActions
- - - - Guards
- - - - States
- - - - Transitions
- - - - YourStateMachine.php
- - - AnotherStateMachine
- - - - AfterActions
- - - - Guards
- - - - States
- - - - Transitions
- - - - AnotherStateMachine.php

In future versions, we will be added, create state machine parts in artisan commands. This time commands run like this directory tree.


[1-) Create First State Machine ](https://github.com/CanerErgez/laravel-state-machine/tree/main/docs/first_state_machine.md)  
[2-) Create First State  ](https://github.com/CanerErgez/laravel-state-machine/tree/main/docs/first_state.md)  
[3-) Create First Transition  ](https://github.com/CanerErgez/laravel-state-machine/tree/main/docs/first_transition.md)   
[4-) Create First Guard   ](https://github.com/CanerErgez/laravel-state-machine/tree/main/docs/first_guard.md)  
[5-) Create First AfterAction   ](https://github.com/CanerErgez/laravel-state-machine/tree/main/docs/first_after_action.md)   
[6-) Example Transition in Created State Machine   ](https://github.com/CanerErgez/laravel-state-machine/tree/main/docs/example_transition.md)  
[7-) Create Another State Machine   ](https://github.com/CanerErgez/laravel-state-machine/tree/main/docs/create_another_state_machine.md)

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please create an issue on github issues section.

## Credits

Special thanks for [Tarfin Labs](https://github.com/tarfin-labs)

- [Caner Ergez](https://github.com/CanerErgez)
- Will be update soon.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
