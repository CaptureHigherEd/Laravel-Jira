# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.0] - Unreleased

### Breaking Changes

- **`Issues::attach()` return type changed** from `Issue` to `Attachments` — update any code that accesses the returned object
- **`Issues::comment()` return type changed** from `Issue` to `Comment` — update any code that accesses the returned object
- **`Field::getOptions()` removed** — use `Api\Fields::getFieldOptions(Field $field, string $projectKey, string $issueTypeName)` instead
- **PHP minimum version raised** from `^8.0.2` to `^8.1`
- **Laravel minimum version raised** from `^8.0||^9.0` to `^10.0||^11.0||^12.0`
- **`Issues::comment()` now delegates to `Comments::create()`** — behavior unchanged but internal routing differs
- **All model `const` declarations removed** — any code referencing e.g. `User::NAME` or `Issue::FIELDS` must use string literals
- **All models now extend `Model` base class** instead of implementing `ApiResponse` directly

### Added

- `Api\HttpClient` — escape hatch for arbitrary Jira API calls not yet covered by a specific `Api` class; accessible via `Jira::httpClient()`
- `Jira::httpClient(): Api\HttpClient` — entry point for the raw HTTP escape hatch
- `HttpServerException::networkError(\Throwable $previous): self` — wraps PSR-18 `NetworkExceptionInterface` transport failures (timeouts, DNS, connection reset); `getResponseCode()` returns `0`, `getResponse()` returns `null`
- `HttpServerException::unknownHttpResponseCode(ResponseInterface $response): self` — thrown for unexpected non-4xx responses in the default `handleErrors()` branch
- `Exception\Concerns\ParsesResponseBody` trait — shared response-body parsing logic (rewind, read, JSON decode, content-type check) extracted from both exception classes; also provides `getResponse()`, `getResponseBody()`, `getResponseCode()`
- `Attachment` and `Attachments` models with full `make()` / `toArray()` support
- `Comment` model with full `make()` / `toArray()` support
- `Api\Fields::getFieldOptions(Field $field, string $projectKey, string $issueTypeName): array` — replaces `Field::getOptions()`
- `toArray()` implemented on `Field`, `Fields`, `Search`, `User`, `Users`
- HTTP error handling for status codes 422 (Unprocessable Entity), 500, 502, 503
- CI matrix via GitHub Actions: PHP 8.2 / 8.3 / 8.4 × Laravel 10 / 11 / 12
- PHPStan (level 6) and Pint (Laravel preset) configuration
- Comprehensive test suite: 115 tests across models, API classes, exceptions, and the service provider
- `Api\Projects` — `index()`, `show()`
- `Api\Comments` — `index()`, `show()`, `create()`, `update()`, `delete()`
- `Api\Worklogs` — `index()`, `create()`, `update()`, `delete()`
- `Api\IssueLinks` — `create()`, `show()`, `delete()`, `getTypes()`
- `Api\Attachments` — `show()`, `delete()`, `getMeta()`
- `Api\Fields::getLabels()` for label retrieval
- `Issues::getTransitions()`, `transition()`, `assign()`, `getWatchers()`, `addWatcher()`, `removeWatcher()`
- `Users::search()`, `myself()`
- `Jira` entry point methods: `projects()`, `comments()`, `worklogs()`, `issueLinks()`, `attachments()`
- 10 new models: `Transition`, `Transitions`, `Projects`, `Watchers`, `Comments` (collection), `Worklog`, `Worklogs`, `IssueLink`, `IssueLinkType`, `IssueLinkTypes`
- Abstract `Model` base class implementing `ApiResponse`
- `Issue` typed getters: `getStatus()`, `getAssignee()`, `getReporter()`, `getPriority()`, `getIssueType()`, `getProject()`, `getResolution()`, etc.
- `FieldMeta` and `FieldMetas` models for createmeta responses
- `IssueTypes` collection model
- `HttpApi::httpDelete()` now accepts query parameters
- Test suite expanded to 199 tests

### Fixed

- `array_filter` on issue fields now preserves falsy non-null values (`0`, `''`, `false`) — previously dropped them
- `HttpClientException` constructor now rewinds the response body stream before reading, preventing double-consume
- Guzzle requests now use `['json' => $params]` instead of `['body' => json_encode($params)]`
- Service provider now returns `null` when `jira.token` is not set instead of throwing a `RuntimeException`
- PSR-18 `NetworkExceptionInterface` (transport-level failures) was previously unhandled; all `sendRequest()` calls now catch it and rethrow as `HttpServerException::networkError()`
- `handleErrors()` default branch now correctly classifies unrecognized 4xx codes as `HttpClientException` and routes all other unrecognized codes to `HttpServerException::unknownHttpResponseCode()` — previously all unrecognized codes threw `HttpClientException`

### Changed

- All model getters and setters are now fully typed
- `User::$active` is now strictly typed as `bool`
- `HttpApi` constructor now accepts `GuzzleHttp\ClientInterface` (PSR) instead of the concrete `Client`
- All `HttpClientException` factory methods now declare `self` as their return type
- All 27 models refactored: constructor promotion, `const` declarations removed, `make()` uses named args
- Collection models use `array_map` instead of `foreach` loops
- `Issues::search()` migrated to `/rest/api/3/search/jql` endpoint
- `HttpClientConnector` and `Http\RequestBuilder` are now marked `final`
- `declare(strict_types=1)` added to all source files
- `str_starts_with()` replaces `strpos(...) !== 0` in exception classes (PHP 8.1+)

## [1.0.5] - 2025-09-02

### Added

- `Issue::setCustomFieldByValue()` now accepts a `$is_multi_select` parameter to control single vs. multi-select field format

## [1.0.4] - 2023-07-17

### Fixed

- Minor fix to `Issues.php`

## [1.0.3] - 2023-06-xx

### Changed

- Removed hardcoded values; issue link URL is now generated from config

## [1.0.2] - 2023-xx-xx

### Changed

- `composer.json` updates

## [1.0.1] - 2023-xx-xx

### Changed

- Initial public release updates

## [1.0.0] - 2023-xx-xx

### Added

- Initial release

[Unreleased]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/2.0.0...HEAD
[2.0.0]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/1.0.5...2.0.0
[1.0.5]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.4...1.0.5
[1.0.4]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.3...v1.0.4
[1.0.3]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/CaptureHigherEd/Laravel-Jira/releases/tag/v1.0.0
