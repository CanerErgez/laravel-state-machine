
# Create Another State Machine
You can create **unlimited** State Machines 
for **different models** or **different model attributes**.

But you should be `careful`. _**Each State Machine
can have a different workflow.**_

You should run one State Machine for one model
one attribute. Because if you use the same model's same
attribute, but multiple state machine, workflows
`can be mixed` with `each other`.

You should use the right State Machine for the 
right model and right model attribute.

**Don't forget:** Multiple State Machines are 
`not related!`

**Strongly Preferred Directory Tree for 
Multiple State Machines;**

- app
- - Services
- - - YourStateMachine
- - - - AfterActions
- - - - Guards
- - - - States
- - - - Transitions
- - - - YourStateMachine.php
- - - AnotherStateMachine
- - - - AfterActions
- - - - Guards
- - - - States
- - - - Transitions
- - - - AnotherStateMachine.php
