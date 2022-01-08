


# Create First State Machine
We create a first State Machine in this document.

For example, We have a `Post` model, and the model has a complex status map.

Note: I prefer to create state machine classes in the `app/Services` folder. If you don't have this folder create a new one.

In `app/Services/PostStateMachine/PostStateMachine.php`;

```php
    namespace App\Services\PostStateMachine;

    use Caner\StateMachine\Concerns\BaseStateMachine;
    
    class PostStateMachine extends BaseStateMachine  
    {
	    /** This is your State Machines initial state. */
    	public function initialState()  
    	{  
	    	return DraftState::class;  
    	}

		/** 
		* Your State Machines all States, 
		* detail in Create First State doc.
		*/
		public function states()  
		{  
		  return [  
			  PostEnums::DRAFT => DraftState::class,  
			  PostEnums::NEED_REVIEW => NeedPreviewState::class,  
			  PostEnums::APPROVED => ApprovedState::class,  
			  PostEnums::UNUSEFUL => UnUsefulState::class,  
		  ];  
		}

		/**
		* This section includes state transitions map.
		* 
		* Note: It should start to 'initialState()' you must
		* don't forget!
		*/
		public function transitions()  
		{  
		  return [  
			  self::class => [  
				  $this->initialState()   => DraftTransition::class,  
			  ],  
			  CurrentState::class => [  
				 TargetStateOne::class => CurrentStateToTargetStateOneTransition::class,  
				 TargetStateTwo::class => CurrentStateToTargetStateTwoTransition::class,  
			  ],
		  ];  
		}
    }
```

Example project coming soon.
