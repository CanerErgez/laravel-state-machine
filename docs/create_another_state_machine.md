# Use Multiple State Machines

You may define multiple state machines for different models or for different state attributes on the same model.

For example, an `Order` model could use one state machine for `payment_status` and another for `fulfillment_status`:

```php
$order
    ->state(PaymentStateMachine::class, 'payment_status')
    ->transitionTo(PaidState::class);

$order
    ->state(FulfillmentStateMachine::class, 'fulfillment_status')
    ->transitionTo(ShippedState::class);
```

Keep each model attribute associated with one state machine. Using different state machines for the same attribute can produce conflicting state and transition mappings.

A clear directory structure keeps the workflows independent:

```text
app/
└── Services/
    ├── PaymentStateMachine/
    │   ├── AfterActions/
    │   ├── Guards/
    │   ├── States/
    │   ├── Transitions/
    │   └── PaymentStateMachine.php
    └── FulfillmentStateMachine/
        ├── AfterActions/
        ├── Guards/
        ├── States/
        ├── Transitions/
        └── FulfillmentStateMachine.php
```
