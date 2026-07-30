# Create a State Machine

This example defines a state machine for a `Post` model whose state is stored in its `status` attribute.

Create `app/Services/PostStateMachine/PostStateMachine.php`:

```php
<?php

namespace App\Services\PostStateMachine;

use App\Enums\PostStatus;
use App\Services\PostStateMachine\States\ApprovedState;
use App\Services\PostStateMachine\States\DraftState;
use App\Services\PostStateMachine\States\NeedReviewState;
use App\Services\PostStateMachine\States\UnusableState;
use App\Services\PostStateMachine\Transitions\ApprovedToDraftTransition;
use App\Services\PostStateMachine\Transitions\DraftToNeedReviewTransition;
use App\Services\PostStateMachine\Transitions\NeedReviewToApprovedTransition;
use Caner\StateMachine\Concerns\BaseStateMachine;

class PostStateMachine extends BaseStateMachine
{
    public function initialState(): int|string|\BackedEnum
    {
        return PostStatus::DRAFT;
    }

    public function states(): array
    {
        return [
            PostStatus::DRAFT => DraftState::class,
            PostStatus::NEED_REVIEW => NeedReviewState::class,
            PostStatus::APPROVED => ApprovedState::class,
            PostStatus::UNUSABLE => UnusableState::class,
        ];
    }

    public function transitions(): array
    {
        return [
            DraftState::class => [
                NeedReviewState::class => DraftToNeedReviewTransition::class,
            ],
            NeedReviewState::class => [
                ApprovedState::class => NeedReviewToApprovedTransition::class,
            ],
            ApprovedState::class => [
                DraftState::class => ApprovedToDraftTransition::class,
            ],
        ];
    }
}
```

The `states()` keys are the values stored in the model attribute. The values are the corresponding state classes.

The `transitions()` map is organized as:

```php
[
    CurrentState::class => [
        TargetState::class => TransitionClass::class,
    ],
]
```

Only transitions declared in this map are allowed. Calling an undeclared transition throws a `TransitionNotFoundException`.

See the [sample project](https://github.com/CanerErgez/laravel-state-machine-sample-project) for a complete implementation.
