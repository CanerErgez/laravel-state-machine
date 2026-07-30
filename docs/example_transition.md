# Run a Transition

## 1. Add `HasState` to the Model

The trait adds a `state()` method that resolves the model's current state.

```php
<?php

namespace App\Models;

use Caner\StateMachine\Traits\HasState;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasState;
}
```

The method takes:

1. The state machine class.
2. The model attribute that stores the state value.

```php
$state = $post->state(PostStateMachine::class, 'status');
```

## 2. Run an Allowed Transition

Call `transitionTo()` with:

1. The target state class.
2. An optional `TransitionContext`.

```php
use Caner\StateMachine\Support\TransitionContext;

$updatedPost = $post
    ->state(PostStateMachine::class, 'status')
    ->transitionTo(
        NeedReviewState::class,
        new TransitionContext(
            data: $request->validated(),
            actor: $request->user(),
            metadata: ['source' => 'admin'],
        ),
    );
```

A controller action may look like this:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostStateMachine\PostStateMachine;
use App\Services\PostStateMachine\States\NeedReviewState;
use Caner\StateMachine\Support\TransitionContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubmitPostForReviewController extends Controller
{
    public function __invoke(Post $post, Request $request): JsonResponse
    {
        $updatedPost = $post
            ->state(PostStateMachine::class, 'status')
            ->transitionTo(
                NeedReviewState::class,
                new TransitionContext(
                    data: $request->validated(),
                    actor: $request->user(),
                ),
            );

        return response()->json($updatedPost);
    }
}
```

Prefer mapping validated request values to known state classes on the server. Do not accept an arbitrary fully qualified class name from user input.

See the [sample project](https://github.com/CanerErgez/laravel-state-machine-sample-project) for a complete implementation.
