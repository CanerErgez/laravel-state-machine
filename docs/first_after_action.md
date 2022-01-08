


# Create First After Action
After actions runs after the `Guards` and `Action`.

The purpose of After Actions is to run notification 
and similar processes that will run after the state 
change is over.

**Strongly Prefered;**
- After Actions should be run async (Jobs, Queues etc.).
  Because this fails should not be error the transition.
  If After Actions throw any Exception all transitions are rolled back.
  
- Sometimes, if you want stop the process in and rollback all changes
you can use After Actions sync.
  
İmportant;

- Your logic should be in `handle()` method.
  Transition are run each After Actions `handle()` method.
  
Example After Action

In `App\Services\PostStateMachine\AfterActions\ExampleAfterAction.php`;

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
