# Laravel State Machine

[![Latest Version on Packagist](https://img.shields.io/packagist/v/caner/state-machine.svg?style=flat-square)](https://packagist.org/packages/caner/state-machine)
[![Total Downloads](https://img.shields.io/packagist/dt/caner/state-machine.svg?style=flat-square)](https://packagist.org/packages/caner/state-machine)
[![run-tests](https://github.com/CanerErgez/laravel-state-machine/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/CanerErgez/laravel-state-machine/actions/workflows/main.yml)

Transaction-safe, guard-driven state machines for Laravel Eloquent models.

The package is designed for business workflows such as orders, payments,
subscriptions, approvals, and fulfilment. A model may use multiple independent
state machines.

## Requirements

- PHP 8.2+
- Laravel 12 or 13

## Installation

```bash
composer require caner/state-machine
php artisan vendor:publish --tag=caner-state-machine-config
```

Laravel package discovery registers the provider automatically.

## Five-minute example

Generate the building blocks:

```bash
php artisan make:state-machine Order/OrderStateMachine
php artisan make:state Order/States/Pending --machine="App\\StateMachines\\Order\\OrderStateMachine"
php artisan make:state Order/States/Paid --machine="App\\StateMachines\\Order\\OrderStateMachine"
php artisan make:transition Order/Transitions/MarkAsPaid
php artisan make:guard Order/Guards/PaymentCaptured
php artisan make:after-action Order/AfterActions/SendReceipt
```

Define the machine:

```php
final class OrderStateMachine extends BaseStateMachine
{
    public function initialState(): int|string|BackedEnum
    {
        return OrderStatus::Pending;
    }

    public function states(): array
    {
        return [
            OrderStatus::Pending->value => Pending::class,
            OrderStatus::Paid->value => Paid::class,
        ];
    }

    public function transitions(): array
    {
        return [
            Pending::class => [
                Paid::class => MarkAsPaid::class,
            ],
        ];
    }
}
```

Use `HasState` on the model and run a transition:

```php
$order = $order
    ->state(OrderStateMachine::class, 'status')
    ->transitionTo(
        Paid::class,
        new TransitionContext(
            data: $request->validated(),
            actor: $request->user(),
            metadata: ['source' => 'checkout'],
        ),
    );
```

## Execution guarantees

Each transition runs on the model's own database connection:

```text
row lock → guards → action → state update → after actions → audit → commit
```

- A rejected guard stops the transition.
- An exception in the action or a synchronous after action rolls everything back.
- The row lock prevents two workers from applying transitions from the same stale state.
- Work that must happen only after commit should be dispatched from an after action
  with `Job::dispatch(...)->afterCommit()`.
- Unexpected exceptions are wrapped in `TransitionFailedException` and retained as
  `getPrevious()`. Guard and concurrency exceptions remain directly catchable.

## Transition names and metadata

Transitions get a snake-case name automatically. Override it or provide static
metadata when exposing actions to an API:

```php
public function name(): string
{
    return 'capture_payment';
}

public function metadata(): array
{
    return ['label' => 'Capture payment', 'destructive' => false];
}
```

```php
$machine->canTransitionTo(Paid::class);
$machine->allowedTransitions();
$machine->allowedTransitionDetails();
```

The query methods inspect the transition graph and never execute guards.

## Audit history

Publish and run the package migration:

```bash
php artisan vendor:publish --tag=caner-state-machine-migrations
php artisan migrate
```

Then enable history in `config/state-machine.php`. Audit rows are written inside
the same transaction and contain the model, attribute, states, transition,
actor, and merged transition/context metadata.

```php
'history' => ['enabled' => true],
```

```php
$order->stateTransitionHistory()->latest()->get();
```

You can replace the recorder by binding your own implementation of
`TransitionHistoryRecorder`.

## Mermaid diagrams

```bash
php artisan state-machine:diagram \
  "App\\StateMachines\\Order\\OrderStateMachine" \
  "App\\Models\\Order" 42 status \
  --output=docs/order-workflow.mmd
```

## Multiple workflows

Use a different machine and attribute for each workflow:

```php
$order->state(OrderStateMachine::class, 'status');
$order->state(PaymentStateMachine::class, 'payment_status');
```

## Documentation

- [State machine](docs/first_state_machine.md)
- [States](docs/first_state.md)
- [Transitions](docs/first_transition.md)
- [Guards](docs/first_guard.md)
- [After actions](docs/first_after_action.md)
- [Running transitions](docs/example_transition.md)
- [Multiple state machines](docs/create_another_state_machine.md)
- [Upgrade from v1](docs/upgrade_v2.md)
- [Release checklist](docs/releasing.md)

## Development

```bash
composer quality
```

This runs Pint, Larastan, and the PHPUnit suite. CI tests every supported
Laravel/PHP combination.

## Security

Please follow the [security policy](SECURITY.md).

## License

The MIT License. See [LICENSE.md](LICENSE.md).
