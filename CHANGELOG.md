# Changelog

## [Unreleased](https://github.com/CaptureHigherEd/Laravel-Jira/compare/v2.0.0...HEAD)

## [v2.0.0](https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.5...v2.0.0) - Unreleased

* [2.x] Add `Api\HttpClient` escape hatch for arbitrary API calls; expose via `Jira::httpClient()` by [@mpetty](https://github.com/mpetty) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/17
* [2.x] Add `HttpServerException::networkError()` factory to wrap PSR-18 `NetworkExceptionInterface` transport failures by [@mpetty](https://github.com/mpetty) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/17
* [2.x] Add `HttpServerException::unknownHttpResponseCode()` factory for unrecognized non-4xx responses by [@mpetty](https://github.com/mpetty) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/17
* [2.x] Extract `Exception\Concerns\ParsesResponseBody` trait to eliminate duplicated body-parsing logic across exception classes by [@mpetty](https://github.com/mpetty) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/17
* [2.x] Catch `NetworkExceptionInterface` in all `sendRequest()` calls and rethrow as `HttpServerException::networkError()` by [@mpetty](https://github.com/mpetty) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/17
* [2.x] Fix `handleErrors()` default branch to route unknown 4xx codes to `HttpClientException` and all other unrecognized codes to `HttpServerException` by [@mpetty](https://github.com/mpetty) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/17
* [2.x] Mark `HttpClientConnector` and `Http\RequestBuilder` as `final` by [@mpetty](https://github.com/mpetty) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/17
* [2.x] Add `declare(strict_types=1)` to all source files by [@mpetty](https://github.com/mpetty) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/17
* [2.x] Replace `strpos(...) !== 0` with `str_starts_with()` throughout exception classes by [@mpetty](https://github.com/mpetty) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/17
* [2.x] Add `Api\Projects` with `index()` and `show()` by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Api\Comments` with `index()`, `show()`, `create()`, `update()`, `delete()` by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Api\Worklogs` with `index()`, `create()`, `update()`, `delete()` by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Api\IssueLinks` with `create()`, `show()`, `delete()`, `getTypes()` by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Api\Attachments` with `show()`, `delete()`, `getMeta()` by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Api\Fields::getLabels()` for label retrieval by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Issues::getTransitions()`, `transition()`, `assign()`, `getWatchers()`, `addWatcher()`, `removeWatcher()` by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Users::search()` and `myself()` by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Jira` entry point methods: `projects()`, `comments()`, `worklogs()`, `issueLinks()`, `attachments()` by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Paginated` interface, `HasPagination` trait, and `HttpApi::paginateGet()` generator for automatic pagination by [@mpetty](https://github.com/mpetty)
* [2.x] Add 10 new models: `Transition`, `Transitions`, `Projects`, `Watchers`, `Comments`, `Worklog`, `Worklogs`, `IssueLink`, `IssueLinkType`, `IssueLinkTypes` by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Attachment` and `Attachments` models by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Comment` model by [@mpetty](https://github.com/mpetty)
* [2.x] Add `FieldMeta` and `FieldMetas` models for createmeta responses by [@mpetty](https://github.com/mpetty)
* [2.x] Add `IssueTypes` collection model by [@mpetty](https://github.com/mpetty)
* [2.x] Add abstract `Model` base class implementing `ApiResponse` by [@mpetty](https://github.com/mpetty)
* [2.x] Add typed getters to `Issue` for nested models (`getStatus()`, `getAssignee()`, `getReporter()`, etc.) by [@mpetty](https://github.com/mpetty)
* [2.x] Add `Api\Fields::getFieldOptions()` replacing removed `Field::getOptions()` by [@mpetty](https://github.com/mpetty)
* [2.x] Add `toArray()` to `Field`, `Fields`, `Search`, `User`, `Users` by [@mpetty](https://github.com/mpetty)
* [2.x] Add HTTP error handling for status codes 422, 500, 502, 503 by [@mpetty](https://github.com/mpetty)
* [2.x] Add `HttpApi::httpDelete()` query parameter support by [@mpetty](https://github.com/mpetty)
* [2.x] Add CI matrix via GitHub Actions: PHP 8.2 / 8.3 / 8.4 × Laravel 10 / 11 / 12 by [@mpetty](https://github.com/mpetty)
* [2.x] Add `getLastResponse(): ?ResponseInterface` to `HttpApi` by [@mpetty](https://github.com/mpetty)
* [2.x] Migrate to PSR-18 HTTP client with `HttpClientConfig`, `RequestBuilder`, and `HttpClientConnector` by [@mpetty](https://github.com/mpetty)
* [2.x] Add hydrator strategy pattern: `ModelHydrator`, `ArrayHydrator`, `NoopHydrator` by [@mpetty](https://github.com/mpetty)
* [2.x] Raise PHP minimum from `^8.0.2` to `^8.1` and Laravel minimum from `^8.0||^9.0` to `^10.0||^11.0||^12.0` by [@mpetty](https://github.com/mpetty)
* [2.x] Fix `array_filter` on issue fields to preserve falsy non-null values (`0`, `''`, `false`) by [@mpetty](https://github.com/mpetty)
* [2.x] Fix `HttpClientException` constructor to rewind response body before reading by [@mpetty](https://github.com/mpetty)
* [2.x] Fix Guzzle requests to use `['json' => $params]` instead of `['body' => json_encode($params)]` by [@mpetty](https://github.com/mpetty)
* [2.x] Fix service provider to return `null` instead of throwing `RuntimeException` when API token is unset by [@mpetty](https://github.com/mpetty)
* [2.x] Migrate `Issues::search()` to `/rest/api/3/search/jql` endpoint by [@mpetty](https://github.com/mpetty)
* [2.x] Remove `Field::getOptions()` — use `Api\Fields::getFieldOptions()` instead by [@mpetty](https://github.com/mpetty)
* [2.x] Remove all model `const` declarations by [@mpetty](https://github.com/mpetty)

## [v1.2.0](https://github.com/CaptureHigherEd/Laravel-Jira/compare/1.1.0...1.2.0) - 2025-09-03

* [1.x] Update Laravel compatibility to support >= 8 by [@mpetty](https://github.com/mpetty)

## [v1.1.0](https://github.com/CaptureHigherEd/Laravel-Jira/compare/1.0.5...1.1.0) - 2024-04-11

* [1.x] Update allowed versions of Laravel (8, 9, 10, 11) by [@mpetty](https://github.com/mpetty) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/4

## [v1.0.5](https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.4...1.0.5) - 2023-12-27

* [1.x] Add `$is_multi_select` parameter to `Issue::setCustomFieldByValue()` by [@adugatkin](https://github.com/adugatkin) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/2

## [v1.0.4](https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.3...v1.0.4) - 2023-07-17

* [1.x] Minor fix to `Issues.php` by [@adugatkin](https://github.com/adugatkin)

## [v1.0.3](https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.2...v1.0.3) - 2023-07-13

* [1.x] Updates to allow for further flexibility and functionality by [@adugatkin](https://github.com/adugatkin) in https://github.com/CaptureHigherEd/Laravel-Jira/pull/1

## [v1.0.2](https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.1...v1.0.2) - 2023-06-27

* [1.x] Update `composer.json` by [@adugatkin](https://github.com/adugatkin)

## [v1.0.1](https://github.com/CaptureHigherEd/Laravel-Jira/compare/v1.0.0...v1.0.1) - 2023-06-20

* [1.x] Initial public release updates by [@adugatkin](https://github.com/adugatkin)

## v1.0.0 - 2023-06-20

* Initial release by [@adugatkin](https://github.com/adugatkin)
