# Create an After Action

After actions run synchronously after the transition action and before the surrounding database transaction commits.

Use them for work that must be part of the transition. If an after action throws an exception, the transition is rolled back.

For notifications or other background work, dispatch a queued job and use `afterCommit()` so the worker cannot process it before the transition commits.

Create `app/Services/PostStateMachine/AfterActions/NotifyReviewers.php`:

```php
<?php

namespace App\Services\PostStateMachine\AfterActions;

use App\Jobs\NotifyReviewersJob;
use Caner\StateMachine\Concerns\BaseAfterAction;

class NotifyReviewers extends BaseAfterAction
{
    public function handle(): void
    {
        $post = $this->baseStateMachine->getModel();

        NotifyReviewersJob::dispatch($post->getKey())->afterCommit();
    }
}
```

Put the after action's logic in `handle()`. The model, optional request, and transition data are available through `$this->baseStateMachine`, `$this->request`, and `$this->data`.

See the [sample project](https://github.com/CanerErgez/laravel-state-machine-sample-project) for a complete implementation.
