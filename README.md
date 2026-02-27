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

---

### Issues

```php
// Search issues (JQL)
$search = $jira->issues()->index(['jql' => 'project = PROJ AND status = "To Do"']);
foreach ($search->getIssues() as $issue) {
    echo $issue->getKey() . ': ' . $issue->getSummary();
}

// Get a single issue
$issue = $jira->issues()->show('PROJ-123');
echo $issue->getLink(); // https://yourcompany.atlassian.net/browse/PROJ-123

// Create an issue
use CaptureHigherEd\LaravelJira\Models\Issue;

$payload = Issue::make()
    ->setProjectByKey('PROJ')
    ->setIssueTypeByName('Bug')
    ->setSummary('Something is broken')
    ->setDescription([/* Atlassian Document Format content */]);

$created = $jira->issues()->create($payload->toArray());

// Update an issue
$jira->issues()->update('PROJ-123', ['fields' => ['summary' => 'Updated title']]);

// Delete an issue
$jira->issues()->delete('PROJ-123');
```

#### Transitions

```php
// Get available transitions
$transitions = $jira->issues()->getTransitions('PROJ-123');
foreach ($transitions->getTransitions() as $transition) {
    echo $transition->getId() . ': ' . $transition->getName();
}

// Transition an issue to a new status
$jira->issues()->transition('PROJ-123', ['transition' => ['id' => '31']]);
```

#### Assignment

```php
$jira->issues()->assign('PROJ-123', $accountId);
```

#### Watchers

```php
// Get watchers
$watchers = $jira->issues()->getWatchers('PROJ-123');
echo $watchers->getWatchCount();

// Add / remove a watcher
$jira->issues()->addWatcher('PROJ-123', $accountId);
$jira->issues()->removeWatcher('PROJ-123', $accountId);
```

#### Attachments

```php
// Attach a file
$attachments = $jira->issues()->attach('PROJ-123', [
    ['name' => 'file', 'contents' => fopen('/path/to/file.pdf', 'r'), 'filename' => 'file.pdf']
]);
```

#### Create Metadata

```php
// Get issue types available for a project
$issueTypes = $jira->issues()->getCreateMetaIssueTypes('PROJ');
foreach ($issueTypes->getIssueTypes() as $type) {
    echo $type->getId() . ': ' . $type->getName();
}

// Get field metadata for a project + issue type
$fields = $jira->issues()->getCreateMetaFields('PROJ', '10001');
foreach ($fields->getFields() as $field) {
    echo $field->getFieldId() . ': ' . $field->getName();
}
```

---

### Projects

```php
// Get all projects
$projects = $jira->projects()->index();
foreach ($projects->getProjects() as $project) {
    echo $project->getKey() . ': ' . $project->getName();
}

// Get a single project
$project = $jira->projects()->show('PROJ');
echo $project->getId();
```

---

### Comments

```php
// Get all comments for an issue
$comments = $jira->comments()->index('PROJ-123');
foreach ($comments->getComments() as $comment) {
    echo $comment->getId();
}

// Get a single comment
$comment = $jira->comments()->show('PROJ-123', '10001');

// Add a comment
$comment = $jira->comments()->create('PROJ-123', [
    'body' => ['type' => 'doc', 'version' => 1, 'content' => [
        ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello!']]]
    ]]
]);

// Update a comment
$jira->comments()->update('PROJ-123', '10001', ['body' => [/* updated ADF */]]);

// Delete a comment
$jira->comments()->delete('PROJ-123', '10001');

// Shorthand via issues()
$comment = $jira->issues()->comment('PROJ-123', ['body' => [/* ADF */]]);
```

---

### Worklogs

```php
// Get all worklogs for an issue
$worklogs = $jira->worklogs()->index('PROJ-123');
foreach ($worklogs->getWorklogs() as $worklog) {
    echo $worklog->getTimeSpent();
}

// Add a worklog
$worklog = $jira->worklogs()->create('PROJ-123', [
    'timeSpent' => '2h',
    'started' => '2024-01-15T09:00:00.000+0000',
]);

// Update a worklog
$jira->worklogs()->update('PROJ-123', '10001', ['timeSpent' => '3h']);

// Delete a worklog
$jira->worklogs()->delete('PROJ-123', '10001');
```

---

### Issue Links

```php
// Link two issues
$jira->issueLinks()->create([
    'type' => ['name' => 'Blocks'],
    'inwardIssue' => ['key' => 'PROJ-123'],
    'outwardIssue' => ['key' => 'PROJ-456'],
]);

// Get a link
$link = $jira->issueLinks()->show('10001');
echo $link->getType()->getName(); // e.g. "Blocks"

// Delete a link
$jira->issueLinks()->delete('10001');

// Get all available link types
$types = $jira->issueLinks()->getTypes();
foreach ($types->getIssueLinkTypes() as $type) {
    echo $type->getName();
}
```

---

### Attachments

```php
// Get attachment metadata
$attachment = $jira->attachments()->show('10001');
echo $attachment->getFilename();

// Delete an attachment
$jira->attachments()->delete('10001');

// Get global attachment settings (enabled, max file size)
$meta = $jira->attachments()->getMeta();
```

---

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

// Get all labels
$labels = $jira->fields()->getLabels();
```

---

### Users

```php
// Get all users
$users = $jira->users()->index();

// Get active users only
foreach ($users->getActiveUsers() as $user) {
    echo $user->getKey() . ': ' . $user->getName() . ' <' . $user->getEmail() . '>';
}

// Get a user by account ID
$user = $jira->users()->show($accountId);

// Search for users
$results = $jira->users()->search(['query' => 'alice']);

// Get the currently authenticated user
$me = $jira->users()->myself();
echo $me->getEmail();
```

---

### Pagination

Paginated endpoints implement the `Paginated` interface, which exposes `getTotal()`, `getMaxResults()`, `getStartAt()`, `hasMore()`, and `getNextStartAt()`.

**Single page with manual pagination:**

```php
$page = $jira->issues()->index(['jql' => 'project = PROJ', 'maxResults' => 50]);

echo $page->getTotal();      // total matching issues
echo $page->hasMore();       // true if more pages exist
echo $page->getNextStartAt(); // offset to pass for the next page
```

**Auto-pagination with generators:**

`paginate()` methods lazily fetch all pages, yielding one model per HTTP response. Use a `foreach` loop to process pages as they arrive:

```php
// Paginate all search results
foreach ($jira->issues()->paginate(['jql' => 'project = PROJ']) as $page) {
    foreach ($page->getIssues() as $issue) {
        // process issue
    }
}

// Paginate comments on an issue
foreach ($jira->comments()->paginate('PROJ-123') as $page) {
    foreach ($page->getComments() as $comment) {
        // process comment
    }
}

// Paginate worklogs on an issue
foreach ($jira->worklogs()->paginate('PROJ-123') as $page) {
    foreach ($page->getWorklogs() as $worklog) {
        // process worklog
    }
}

// Paginate create-meta issue types
foreach ($jira->issues()->paginateCreateMetaIssueTypes('PROJ') as $page) {
    foreach ($page->getIssueTypes() as $type) {
        // process issue type
    }
}

// Paginate create-meta fields for a specific issue type
foreach ($jira->issues()->paginateCreateMetaFields('PROJ', '10001') as $page) {
    foreach ($page->getFields() as $field) {
        // process field
    }
}
```

You can also break early to avoid fetching unnecessary pages:

```php
foreach ($jira->issues()->paginate(['jql' => 'project = PROJ']) as $page) {
    foreach ($page->getIssues() as $issue) {
        if ($issue->getKey() === 'PROJ-42') {
            break 2;
        }
    }
}
```

---

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
