# Contributing

Contributions are welcome.

## Development Setup

Fork the repository, create a branch from `main`, and install dependencies:

```bash
composer install
```

Run the complete quality suite before submitting a pull request:

```bash
composer quality
```

This checks Pint formatting, Larastan static analysis, and PHPUnit.

## Pull Requests

- Keep each pull request focused on one change.
- Add or update tests when behavior changes.
- Update the documentation when the public API or supported versions change.
- Describe the reason for the change and any compatibility impact.
- Make sure all GitHub Actions checks pass.

By contributing, you agree that your contribution will be licensed under the project's MIT license.
