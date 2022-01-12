
# Create First State
Each state must be extended in your State Machine.

You must define a state for each main attribute of a model.
For example `status`. This is not required, but recommended.

For example;

My model have a 5 `status` and i store statuses in enums.

For example;

```php
    namespace App\Enums\ExampleEnums;

    class ExampleEnum
    {
        const EXAMPLE_ONE = 1;
        const EXAMPLE_TWO = 2;
        const EXAMPLE_THREE = 3;
        const EXAMPLE_FOUR = 4;
        const EXAMPLE_FİVE = 5;
    }
```

If you have this `status` model, you should create 5 
state for each `status`.

For example;

```php
    ExampleOneState::class,
    ExampleTwoState::class,
    ExampleThreeState::class,
    ExampleFourState::class,
    ExampleFiveState::class,
```

So each `status` will define a state.

You should define which `status` will work with which 
`state` in the your state machine method of states.

For example;

```php
    class ExampleStateMachine extends BaseStateMachine
    {
        /** 
        * if the model has not been created yet, 
        * we get the state of the model as initalState.
        */
        public function initialState()
        {
            return ExampleOneState::class;
        }

        public function states(): array
        {
            return [
                ExampleEnum::EXAMPLE_ONE   => ExampleOneState::class,
                ExampleEnum::EXAMPLE_TWO   => ExampleTwoState::class,
                ExampleEnum::EXAMPLE_THREE => ExampleThreeState::class,
                ExampleEnum::EXAMPLE_FOUR  => ExampleFourState::class,
                ExampleEnum::EXAMPLE_FIVE  => ExampleFiveState::class,
            ];
        }

        // ...
    }
```

In the previous doc, we create PostStateMachine, so we'll 
create new states in this state machine base.

We don't write anything in the created state.

In `App\Services\PostStateMachine\States\ExampleState.php`;

```php
	namespace App\Services\PostStateMachine\States;

	use App\Services\PostStateMachine\PostStateMachine;

	class ExampleState extends PostStateMachine
	{
		//Nothing
	}
```

Example project coming soon.
