# Laravel-Jira

Laravel REST API v3 client for Jira (Atlassian Cloud).

## Architecture

Layered: `Jira` → `Api/` (HttpApi subclasses) → `Models/` (DTOs implementing `ApiResponse`)

- **`Jira.php`** — entry point; exposes `issues()`, `fields()`, `users()`
- **`Api/HttpApi.php`** — base class; wraps Guzzle, handles error dispatch and response hydration
- **`Models/`** — DTOs with static `make(array $data = []): self` factory + `toArray(): array`
- **`Providers/IntegrationServiceProvider.php`** — deferred Laravel service provider; auto-discovered
- **`config/jira.php`** — reads `JIRA_API_EMAIL`, `JIRA_API_TOKEN`, `JIRA_API_DOMAIN`

## Commands

```sh
composer install
vendor/bin/phpunit          # tests
vendor/bin/phpstan analyse  # static analysis (level 6)
vendor/bin/pint             # code style
```

## After Every Change

Always run these three commands before committing:

```sh
vendor/bin/phpunit          # must pass
vendor/bin/phpstan analyse  # must show [OK] No errors
vendor/bin/pint             # auto-fixes style; run --test to check only
```

## Conventions

- PHP 8.1+, strict types expected
- Use `===` for comparisons, never `==`
- The singleton returns `null` when `JIRA_API_EMAIL`/`JIRA_API_TOKEN` are not set — callers should null-check before use
- HTTP errors throw `HttpClientException` — add new status codes to `handleErrors()` and as factory methods on the exception class
- Keep DTOs free of service locator calls (`app()`) — business logic belongs in `Api/` classes
