# Create a State

Create one state class for each value that the model's state attribute can contain. Keeping the values in constants or an enum makes the mapping easier to maintain.

For example:

```php
<?php

namespace App\Enums;

final class PostStatus
{
    public const DRAFT = 1;
    public const NEED_REVIEW = 2;
    public const APPROVED = 3;
    public const UNUSABLE = 4;
}
```

Map each value to a state in your state machine:

```php
public function initialState(): string
{
    return DraftState::class;
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
```

A state class extends your state machine and does not need additional methods:

```php
<?php

namespace App\Services\PostStateMachine\States;

use App\Services\PostStateMachine\PostStateMachine;

class DraftState extends PostStateMachine
{
}
```

Create the other state classes in the same way.

See the [sample project](https://github.com/CanerErgez/laravel-state-machine-sample-project) for a complete implementation.
