


# Example Transition in Created State Machine
We created a sample State Machine in previous docs.

Let's code first transition in this state machine.

### Step 1: Add `HasState` trait to your Model.

HasState trait add a `state()` method in your model.
`state()` method return current state.

Example;

    namespace App\Models;

    use Caner\StateMachine\Traits\HasState;

    class ExampleModel extends Model
    {
        use HasState;
    }

Important;
- `state()` method should wants 2 parameter.
  Because we can use more than one state machine in one model.
- - First parameter is your StateMachine class. // Which State Machine to run ?
- - The second parameter is your model attribute. // Which Model Attribute to run ?

Example in Step 2.

### Step 2: Add transition move method in any Controller

Model's `state()` method are return State Class

    $model->state(PostStateMachine::class, 'status')

This code will be return `new ExampleState()`

and you can use `transitionTo()` method for change state.

Important;
- `transitionTo()` method should wants 3 parameter.
- - First parameter is your `Target State`.
- - Second parameter is your `$request` (Optional).
- - Third parameter is your custom `$data` array (Optional).
    
You can use this `$data` array to `Guards`, `Action` and
`AfterActions` in `Transitions`.

Simple usage;

    $exampleState->transitionTo(TargetState::class, $request, $data);

Example Full Transition;

    namespace App\Http\Controllers;

    use App\Services\PostStateMachine\PostStateMachine;

    class ExampleController extends Controller
    {
        public function update(Model $model, Request $request)
        {
            /**
            * In this code, we get model's state for model's status value.
            * 
            * And transition to target. Target is in request parameters.
            */
            $model->state(PostStateMachine::class, 'status')
                ->transitionTo($request->target, $request);
    
            return response()->json(['success' => true]);
        }
    }

You can get the target state in request. But you should use the full path.

Example Request Body;

    {
        "target": "App\\Services\\PostStateMachine\\States\\ExampleState"
    }
