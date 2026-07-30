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
     * the value associated with the target state before action() runs.
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
            'review_started_at' => $this->data['review_started_at'] ?? now(),
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
- `$this->request` contains the optional request.
- `$this->data` contains custom input and values returned by guards.

The complete transition, including guards and after actions, runs inside a database transaction. If any step throws, the transaction is rolled back and a `TransitionFailedException` is thrown.

See the [sample project](https://github.com/CanerErgez/laravel-state-machine-sample-project) for a complete implementation.
