

# Introducing Concept
A state machine is a mathematical abstraction used to design algorithms. A state machine reads a set of inputs and changes to a different state based on those inputs.

A state is a description of the status of a system waiting to execute a transition. A transition is a set of actions to execute when a condition is fulfilled or an event received. In a state diagram, circles represent each possible state and arrows represent transitions between states.

Basically we are build a `state` for each `status` and runs related `transition` when change the `status`.

![Sample State Change Workflow](https://github.com/CanerErgez/laravel-state-machine/tree/main/docs/img/1.png)

Each `Transition` should consist of 3 parts. These are the `guards`, `action` and `afterActions` methods.

![Sample Transition Workflow](https://github.com/CanerErgez/laravel-state-machine/tree/main/docs/img/1.png)

I prefer to use package in complex status change.
