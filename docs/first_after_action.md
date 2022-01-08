


# Create First After Action
After actions run after the `Guards` and `Action`.

The purpose of After Actions is to run notification 
and similar processes that will run after the state 
change is over.

**Strongly Prefered;**
- After Actions should be run async (Jobs, Queues, etc.).
  Because this fails should not be an error in the transition.
  If After Actions throw any Exception all transitions are rolled back.
  
- Sometimes, if you want to stop the process and rollback all changes
  you can use After Actions sync.


Important;

- Your logic should be in `handle()` method.
  Transition are run each After Actions `handle()` method.
  
Example After Action

In `App\Services\PostStateMachine\AfterActions\ExampleAfterAction.php`;

```php
    namespace App\Services\PostStateMachine\AfterActions;

    use Caner\StateMachine\Concerns\BaseAfterAction;

    class ExampleAfterAction extends BaseAfterAction
    {
        public function handle()
        {
            // Collection Instance
            $user = User::where('id', 1)->get();
    
            ExampleJob::dispatchSync($user);
            // Or
            Queue::later(10, new ExampleJob($user), $this->data);
            // Or
            Notification::send($users, new PostCreated($this->baseStateMachine, $this->request, $this->data));
        }
    }
```
