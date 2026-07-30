# Changelog

All notable changes to `state-machine` will be documented in this file.

## 2.0.0 - Unreleased

- Added Laravel 12 and Laravel 13 support.
- Raised the minimum PHP version to 8.2.
- Updated Testbench, PHPUnit, and the GitHub Actions test matrix.
- Replaced the HTTP request dependency with a typed `TransitionContext`.
- Added container resolution for state machines, transitions, guards, and after actions.
- Added model-connection transactions and preserved original exception chains.
- Added transition query methods and lifecycle events.
- Changed automatic state updates to run after the transition action.
- Kept after actions synchronous and before commit; jobs may opt into `afterCommit()`.
- Removed the unused queue contract and traits from transitions.
- Added strict missing-state validation and backed-enum value handling.
- Added opt-in transition audit history with actor and metadata support.
- Added row locking to prevent transitions from stale model state.
- Added transition names, metadata, and detailed transition discovery.
- Added generators for machines, states, transitions, guards, and after actions.
- Added Mermaid state diagram generation.
- Added Pint and Larastan quality gates.
- Expanded integration coverage and release documentation.
- Removed the obsolete manual service-provider registration step from the documentation.

## 1.1.0

- Laravel 9 compatibility added.
- Automatic state update added.
- Docs Updated and example project link added each doc file.

## 1.0.0

- Initial Release.
