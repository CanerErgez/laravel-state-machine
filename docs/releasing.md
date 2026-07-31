# Releasing v2.0.0

## Before tagging

1. Run `composer quality`.
2. Confirm every GitHub Actions matrix job passes.
3. Build the independent example application against the release candidate.
4. Verify install, config publish, migration publish, generators, audit history,
   concurrency behavior, queued `afterCommit()` work, and diagram generation.
5. Review `docs/upgrade_v2.md` and remove `Unreleased` from the changelog.
6. Confirm Packagist points to `CanerErgez/laravel-state-machine`.

## Release

Create an annotated `v2.0.0` tag from `main`, publish GitHub release notes from
the changelog, and verify that Packagist receives the tag. Do not tag a commit
whose CI is incomplete or failing.

## After release

Install `^2.0` in a clean Laravel 12 application and a clean Laravel 13
application. Keep the example repository pinned to a tagged release rather than
the development branch.
