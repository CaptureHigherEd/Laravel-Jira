# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0] - Unreleased

### Breaking Changes

- **`Issues::attach()` return type changed** from `Issue` to `Attachments` — update any code that accesses the returned object
- **`Issues::comment()` return type changed** from `Issue` to `Comment` — update any code that accesses the returned object
- **`Field::getOptions()` removed** — use `Api\Fields::getFieldOptions(Field $field, string $projectKey, string $issueTypeName)` instead
- **PHP minimum version raised** from `^8.0.2` to `^8.1`
- **Laravel minimum version raised** from `^8.0||^9.0` to `^10.0||^11.0||^12.0`

### Added

- `Attachment` and `Attachments` models with full `make()` / `toArray()` support
- `Comment` model with full `make()` / `toArray()` support
- `Api\Fields::getFieldOptions(Field $field, string $projectKey, string $issueTypeName): array` — replaces `Field::getOptions()`
- `toArray()` implemented on `Field`, `Fields`, `Search`, `User`, `Users`
- HTTP error handling for status codes 422 (Unprocessable Entity), 500, 502, 503
- CI matrix via GitHub Actions: PHP 8.2 / 8.3 / 8.4 × Laravel 10 / 11 / 12
- PHPStan (level 6) and Pint (Laravel preset) configuration
- Comprehensive test suite: 115 tests across models, API classes, exceptions, and the service provider

### Fixed

- `array_filter` on issue fields now preserves falsy non-null values (`0`, `''`, `false`) — previously dropped them
- `HttpClientException` constructor now rewinds the response body stream before reading, preventing double-consume
- Guzzle requests now use `['json' => $params]` instead of `['body' => json_encode($params)]`
- Service provider now returns `null` when `jira.token` is not set instead of throwing a `RuntimeException`

### Changed

- All model getters and setters are now fully typed
- `User::$active` is now strictly typed as `bool`
- `HttpApi` constructor now accepts `GuzzleHttp\ClientInterface` (PSR) instead of the concrete `Client`
- All `HttpClientException` factory methods now declare `self` as their return type

## [1.0.5] - 2025-09-02

### Added

- `Issue::setCustomFieldByValue()` now accepts a `$is_multi_select` parameter to control single vs. multi-select field format

## [1.0.4] - 2023-07-17

### Fixed

- Minor fix to `Issues.php`

## [1.0.3] - 2023-06-xx

### Changed

- Changes to support Aletheia usage (CBE4-3814)
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

[Unreleased]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/1.1.0...HEAD
[1.1.0]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/1.0.5...1.1.0
[1.0.5]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.4...1.0.5
[1.0.4]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.3...v1.0.4
[1.0.3]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/CaptureHigherEd/Laravel-Jira/releases/tag/v1.0.0
