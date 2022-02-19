# Create First Transition
Transitions are so IMPORTANT!

Because all state changes work in the Transition system.

Let's look at example transition.

In `App/Services/PostStateMachine/Transitions/ExampleTransition.php`;

```php
	namespace App\Services\PostStateMachine\Transitions;

	use Caner\StateMachine\Concerns\BaseTransition;

	class ExampleTransition extends BaseTransition  
	{
		/** 
		* This control variable can update 
		* your model main attribute to expected 
		* target state value.
		* 
		* If you enable this feature,
		* the main attribute update in the action
		* method is not needed.
		*/
		public bool $automaticStateUpdate = true;
	
		/** 
		* This section includes all guards
		* when run in state change.
		*  
		* It's basically control classes about 
		* the state change.
		*/
		public function guards()  
		{  
		  return [  
			 ExampleGuard::class,
			 AnotherGuard::class,  
		  ];  
		}

		/**
		* This section includes main logic.
		*  
		* You have some data in this class;
		*  
		* $this->baseStateMachine->getModel() : It returns model data
		* $this->baseStateMachine->request : It returns request data or null
		* $this->baseStateMachine->data : It returns array
		*  
		* $data variable is important, because you use guards returned datas
		* in this section when you want.
		*/
		public function action(): Model  
		{  
		  $post = $this->baseStateMachine->getModel();  
		  
		  $post->update([  
			  'status' => PostEnums::UNUSEFUL,  
		  ]);  

		  //  $this->baseStateMachine->data['ExampleGuardReturnedData'];
		  return $post;  
		}
		
		/** 
		* This section includes all afterActions
		* when run in **after** state change.
		* 
		* If any After Action fails,
		* state change effect this fail.
		* In future verisons not effect state change.
		*/
		public function afterActions()  
		{  
		  return [  
			  ExampleAfterAction::class,
			  AnotherAfterAction::class,
		  ];  
		}
	}
```

[Please check example project](https://github.com/CanerErgez/laravel-state-machine-sample-project)
