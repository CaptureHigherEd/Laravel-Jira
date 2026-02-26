# Laravel Jira

A Laravel package providing a clean client for the **Jira REST API v3** (Atlassian Cloud).

## Requirements

- PHP 8.1+
- Laravel 10, 11, or 12

## Installation

```sh
composer require capturehighered/laravel-jira
```

The service provider is auto-discovered via Laravel's package auto-discovery. No manual registration needed.

## Configuration

Publish the config file:

```sh
php artisan vendor:publish --provider="CaptureHigherEd\LaravelJira\Providers\IntegrationServiceProvider"
```

Add these to your `.env`:

```env
JIRA_API_EMAIL=you@example.com
JIRA_API_TOKEN=your_atlassian_api_token
JIRA_API_DOMAIN=https://yourcompany.atlassian.net
```

To generate an Atlassian API token: https://id.atlassian.com/manage-profile/security/api-tokens

## Usage

Resolve the `Jira` service from the container, or inject it:

```php
use CaptureHigherEd\LaravelJira\Jira;

$jira = app(Jira::class);
```

### Issues

```php
// Search issues (JQL)
$search = $jira->issues()->index(['jql' => 'project = CBE4 AND status = "To Do"']);
foreach ($search->getIssues() as $issue) {
    echo $issue->getKey() . ': ' . $issue->getSummary();
}

// Get a single issue
$issue = $jira->issues()->show('CBE4-123');
echo $issue->getLink(); // https://yourcompany.atlassian.net/browse/CBE4-123

// Create an issue
use CaptureHigherEd\LaravelJira\Models\Issue;

$payload = Issue::make()
    ->setProjectByKey('CBE4')
    ->setIssueTypeByName('Bug')
    ->setSummary('Something is broken')
    ->setDescription([/* Atlassian Document Format content */]);

$created = $jira->issues()->create($payload->toArray());

// Update an issue
$jira->issues()->update('CBE4-123', ['fields' => ['summary' => 'Updated title']]);

// Add a comment
$comment = $jira->issues()->comment('CBE4-123', [
    'body' => ['type' => 'doc', 'version' => 1, 'content' => [
        ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello!']]]
    ]]
]);

// Attach a file
$attachment = $jira->issues()->attach('CBE4-123', [
    ['name' => 'file', 'contents' => fopen('/path/to/file.pdf', 'r'), 'filename' => 'file.pdf']
]);

// Delete an issue
$jira->issues()->delete('CBE4-123');
```

### Fields

```php
// Get all fields
$fields = $jira->fields()->index();

// Get only custom fields
foreach ($fields->getCustomFields() as $field) {
    echo $field->getId() . ': ' . $field->getName();
}

// Look up a custom field ID by name
$fieldId = $fields->getCustomFieldId('My Custom Field');

// Get allowed values for a field (project + issue type context)
$field = $fields->getCustomField('Priority');
$options = $jira->fields()->getFieldOptions($field, 'CBE4', 'Bug');
// ['High' => 'High', 'Medium' => 'Medium', 'Low' => 'Low']
```

### Users

```php
// Get all users
$users = $jira->users()->index();

// Get active users only
$activeUsers = $users->getActiveUsers();

foreach ($activeUsers as $user) {
    echo $user->getKey() . ': ' . $user->getName() . ' <' . $user->getEmail() . '>';
}
```

## Error Handling

All HTTP errors throw `CaptureHigherEd\LaravelJira\Exception\HttpClientException`:

```php
use CaptureHigherEd\LaravelJira\Exception\HttpClientException;

try {
    $issue = $jira->issues()->show('INVALID-999');
} catch (HttpClientException $e) {
    echo $e->getResponseCode(); // 404
    echo $e->getMessage();      // The endpoint you have tried to access does not exist.
    print_r($e->getResponseBody());
}
```

Covered status codes: 400, 401, 402, 403, 404, 409, 413, 422, 429, 500, 502, 503.

## License

[MIT](LICENSE.md)
