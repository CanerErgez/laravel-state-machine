# Create a Guard

Guards decide whether a transition may run. Put the validation logic in `check()` and always return `$this`.

The guard result must use one of these forms:

```php
// Allow the transition.
$this->data['result'] = true;

// Reject the transition and include an optional error message.
$this->data['result'] = [
    'error' => 'The post cannot be published.',
];
```

The transition stops and throws an exception unless the result is exactly `true`.

## Passing Data to Later Steps

Add values under `$this->data['data']` to pass them to subsequent guards, the transition action, and after actions:

```php
$this->data['data']['approved_by'] = auth()->id();
```

The package merges those values into the transition's root data array, where they are available as:

```php
$approvedBy = $this->data['approved_by'];
```

## Example

Create `app/Services/PostStateMachine/Guards/PostCanBeReviewed.php`:

```php
<?php

namespace App\Services\PostStateMachine\Guards;

use Caner\StateMachine\Concerns\BaseGuard;

class PostCanBeReviewed extends BaseGuard
{
    public function check(): BaseGuard
    {
        if ($this->baseStateMachine->getModel()->is_locked) {
            $this->data['result'] = [
                'error' => 'A locked post cannot be reviewed.',
            ];

            return $this;
        }

        $this->data['data']['review_started_at'] = now();
        $this->data['result'] = true;

        return $this;
    }
}
```

See the [sample project](https://github.com/CanerErgez/laravel-state-machine-sample-project) for a complete implementation.
