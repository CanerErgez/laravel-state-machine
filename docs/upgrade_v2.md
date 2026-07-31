# Upgrade from v1 to v2

Version 2 is a major release. It supports Laravel 12–13 and PHP 8.2+.

## Replace Request and Data Arguments

`transitionTo()` no longer accepts an HTTP request and a separate data array.
Pass a framework-agnostic context instead:

```php
use Caner\StateMachine\Support\TransitionContext;

$state->transitionTo(
    PublishedState::class,
    new TransitionContext(
        data: $request->validated(),
        actor: $request->user(),
        metadata: ['trace_id' => $request->header('X-Trace-Id')],
    ),
);
```

In transitions, guards, and after actions, replace `$this->request` and
`$this->data` reads with `$this->context`.

## Add Return Types

State machines and transition steps now use typed contracts:

```php
public function initialState(): int|string|\BackedEnum;
public function states(): array;
public function transitions(): array;
public function guards(): array;
public function afterActions(): array;
public function handle(): void; // after actions
```

## Review Automatic State Updates

With `$automaticStateUpdate = true`, v2 runs `action()` first and updates the
state only after the action succeeds. Do not update the state attribute again
inside `action()`.

## Review Exception Handling

Guard failures are no longer converted to `TransitionFailedException`.
Unexpected failures are wrapped and retain the original exception:

```php
try {
    $state->transitionTo(PublishedState::class);
} catch (GuardErrorException $exception) {
    // A guard rejected the transition.
} catch (TransitionFailedException $exception) {
    report($exception->getPrevious());
}
```

## Move Queue Work to After Actions

Transitions no longer implement `ShouldQueue`; execution is synchronous. After
actions also run synchronously before commit. For retryable post-commit work,
dispatch a job from an after action:

```php
CreateInvoiceJob::dispatch($order->getKey())->afterCommit();
```

## Use the New Query API

```php
$state->canTransitionTo(PublishedState::class);
$state->allowedTransitions();
```

These methods inspect the transition map. They do not run guards.

## Optional v2 features

History is disabled by default. To use it, publish the migration, migrate, and
enable `state-machine.history.enabled`. Row locking is enabled by default and
may be disabled with `state-machine.locking.enabled` when the database driver
does not support row locks.

Transitions may override `name()` and `metadata()`. Use
`allowedTransitionDetails()` when clients need these values.
