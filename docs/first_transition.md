# Create a Transition

A transition defines its guards, main action, and after actions.

Create `app/Services/PostStateMachine/Transitions/DraftToNeedReviewTransition.php`:

```php
<?php

namespace App\Services\PostStateMachine\Transitions;

use App\Services\PostStateMachine\AfterActions\NotifyReviewers;
use App\Services\PostStateMachine\Guards\PostCanBeReviewed;
use Caner\StateMachine\Concerns\BaseTransition;
use Illuminate\Database\Eloquent\Model;

class DraftToNeedReviewTransition extends BaseTransition
{
    /**
     * When enabled, the package updates the model's state attribute to
     * the value associated with the target state after action() succeeds.
     */
    public bool $automaticStateUpdate = true;

    public function guards(): array
    {
        return [
            PostCanBeReviewed::class,
        ];
    }

    public function action(): Model
    {
        $post = $this->baseStateMachine->getModel();

        $post->update([
            'review_started_at' => $this->context->data['review_started_at'] ?? now(),
        ]);

        return $post;
    }

    public function afterActions(): array
    {
        return [
            NotifyReviewers::class,
        ];
    }
}
```

Set `$automaticStateUpdate` to `true` when the package should update the state attribute. Do not update that attribute again in `action()`. Leave the property as `false` if the action should control the state value itself.

Within a transition:

- `$this->baseStateMachine->getModel()` returns the model.
- `$this->context->data` contains input and values returned by guards.
- `$this->context->actor` contains the optional initiating user or service.
- `$this->context->metadata` contains tracing or integration metadata.

The complete transition, including guards and after actions, runs inside a
database transaction on the model's connection. Guard exceptions remain
catchable as guard exceptions. Unexpected failures are wrapped in a
`TransitionFailedException` whose `getPrevious()` value contains the original
exception.

See the [sample project](https://github.com/CanerErgez/laravel-state-machine-sample-project) for a complete implementation.
