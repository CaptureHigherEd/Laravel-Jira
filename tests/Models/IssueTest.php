<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Models\IssueType;
use CaptureHigherEd\LaravelJira\Models\Priority;
use CaptureHigherEd\LaravelJira\Models\Project;
use CaptureHigherEd\LaravelJira\Models\Resolution;
use CaptureHigherEd\LaravelJira\Models\Status;
use CaptureHigherEd\LaravelJira\Models\User;
use CaptureHigherEd\LaravelJira\Tests\Concerns\UsesTestbench;
use Orchestra\Testbench\TestCase;

class IssueTest extends TestCase
{
    use UsesTestbench;

    // ── make & toArray ────────────────────────────────────────────────────

    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => '10001',
            'key' => 'KEY-1',
            'self' => 'https://example.atlassian.net/rest/api/3/issue/10001',
            'fields' => ['summary' => 'Test Issue'],
        ];

        $issue = Issue::make($data);

        $this->assertSame('10001', $issue->getId(), 'Issue ID should match the input id value');
        $this->assertSame('KEY-1', $issue->getKey(), 'Issue key should match the input key value');
        $this->assertSame('https://example.atlassian.net/rest/api/3/issue/10001', $issue->getSelf(), 'Issue self URL should match the input self value');
        $this->assertSame('Test Issue', $issue->getSummary(), 'Issue summary should match the summary field from the input data');
    }

    public function test_make_with_empty_data(): void
    {
        $issue = Issue::make();

        $this->assertSame('', $issue->getId(), 'Issue ID should default to an empty string when not provided');
        $this->assertSame('', $issue->getKey(), 'Issue key should default to an empty string when not provided');
        $this->assertSame([], $issue->getFields(), 'Issue fields should default to an empty array when not provided');
        $this->assertNull($issue->getSummary(), 'Issue summary should be null when fields are not provided');
    }

    // ── Field filtering ───────────────────────────────────────────────────

    public function test_make_filters_null_fields(): void
    {
        $issue = Issue::make([
            'id' => '1',
            'key' => 'KEY-1',
            'fields' => [
                'summary' => 'Test',
                'description' => null,
                'priority' => null,
            ],
        ]);

        $fields = $issue->getFields();

        $this->assertArrayHasKey('summary', $fields, 'Issue fields should retain the summary key when its value is not null');
        $this->assertArrayNotHasKey('description', $fields, 'Issue fields should remove the description key when its value is null');
        $this->assertArrayNotHasKey('priority', $fields, 'Issue fields should remove the priority key when its value is null');
    }

    public function test_make_preserves_falsy_non_null_values(): void
    {
        $issue = Issue::make([
            'id' => '1',
            'key' => 'KEY-1',
            'fields' => [
                'count' => 0,
                'label' => '',
                'active' => false,
            ],
        ]);

        $fields = $issue->getFields();

        $this->assertArrayHasKey('count', $fields, 'Issue fields should retain the count key even when the value is 0');
        $this->assertArrayHasKey('label', $fields, 'Issue fields should retain the label key even when the value is an empty string');
        $this->assertArrayHasKey('active', $fields, 'Issue fields should retain the active key even when the value is false');
        $this->assertSame(0, $fields['count'], 'Issue fields count value should remain 0 after null filtering');
        $this->assertSame('', $fields['label'], 'Issue fields label value should remain an empty string after null filtering');
        $this->assertFalse($fields['active'], 'Issue fields active value should remain false after null filtering');
    }

    // ── Field setters ─────────────────────────────────────────────────────

    /** @dataProvider customFieldByValueProvider */
    public function test_set_custom_field_by_value(string $value, ?bool $multiSelect, mixed $expected, string $message): void
    {
        $issue = Issue::make();
        $issue->setCustomFieldByValue('customfield_10001', $value, $multiSelect);

        $this->assertSame($expected, $issue->getFields()['customfield_10001'], $message);
    }

    /** @return array<string, array{string, ?bool, mixed, string}> */
    public static function customFieldByValueProvider(): array
    {
        return [
            'multi-select' => ['Option A', true,  [['value' => 'Option A']], 'Multi-select custom field should be stored as an array of value objects'],
            'single-select' => ['Option B', false, ['value' => 'Option B'],   'Single-select custom field should be stored as a single value object'],
            'null acts as single' => ['Option C', null,  ['value' => 'Option C'],   'Custom field with null multi-select flag should be treated as single-select'],
        ];
    }

    public function test_set_project_by_key(): void
    {
        $issue = Issue::make();
        $issue->setProjectByKey('CBE4');

        $this->assertSame(['key' => 'CBE4'], $issue->getFields()['project'], 'setProjectByKey() should store the project as an array with a key property');
    }

    public function test_set_description_wraps_in_adf(): void
    {
        $issue = Issue::make();
        $content = [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello']]]];
        $issue->setDescription($content);

        $description = $issue->getFields()['description'];

        $this->assertSame($content, $description['content'], 'setDescription() should embed the provided content inside the ADF document wrapper');
        $this->assertSame('doc', $description['type'], 'setDescription() should set the ADF document type to "doc"');
        $this->assertSame(1, $description['version'], 'setDescription() should set the ADF document version to 1');
    }

    public function test_set_reporter_wraps_in_id(): void
    {
        $issue = Issue::make();
        $issue->setReporter('abc123');

        $this->assertSame(['id' => 'abc123'], $issue->getFields()['reporter'], 'setReporter() should store the reporter as an array with an id property');
    }

    // ── Typed field getters ───────────────────────────────────────────────

    public function test_get_status_returns_status_model(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'K-1', 'fields' => [
            'status' => ['id' => '1', 'name' => 'In Progress', 'description' => '', 'iconUrl' => '', 'self' => '', 'statusCategory' => []],
        ]]);

        $this->assertInstanceOf(Status::class, $issue->getStatus(), 'getStatus() should return a Status instance when status field is present');
        $this->assertSame('In Progress', $issue->getStatus()?->getName(), 'getStatus() should hydrate the status name correctly');
    }

    public function test_get_status_returns_null_when_absent(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'K-1', 'fields' => []]);

        $this->assertNull($issue->getStatus(), 'getStatus() should return null when the status field is not present');
    }

    public function test_get_assignee_returns_user_model(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'K-1', 'fields' => [
            'assignee' => ['accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => 'alice@example.com', 'active' => true],
        ]]);

        $this->assertInstanceOf(User::class, $issue->getAssignee(), 'getAssignee() should return a User instance when assignee field is present');
        $this->assertSame('u1', $issue->getAssignee()?->getKey(), 'getAssignee() should hydrate the accountId correctly');
    }

    public function test_get_reporter_returns_user_model(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'K-1', 'fields' => [
            'reporter' => ['accountId' => 'u2', 'displayName' => 'Bob', 'emailAddress' => 'bob@example.com', 'active' => true],
        ]]);

        $this->assertInstanceOf(User::class, $issue->getReporter(), 'getReporter() should return a User instance when reporter field is present');
        $this->assertSame('u2', $issue->getReporter()?->getKey(), 'getReporter() should hydrate the accountId correctly');
    }

    public function test_get_priority_returns_priority_model(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'K-1', 'fields' => [
            'priority' => ['id' => '2', 'name' => 'High', 'description' => '', 'iconUrl' => '', 'self' => '', 'statusColor' => '', 'isDefault' => false, 'avatarId' => 0],
        ]]);

        $this->assertInstanceOf(Priority::class, $issue->getPriority(), 'getPriority() should return a Priority instance when priority field is present');
        $this->assertSame('High', $issue->getPriority()?->getName(), 'getPriority() should hydrate the priority name correctly');
    }

    public function test_get_issue_type_returns_issue_type_model(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'K-1', 'fields' => [
            'issuetype' => ['id' => '10001', 'name' => 'Bug', 'description' => '', 'subtask' => false, 'iconUrl' => '', 'self' => ''],
        ]]);

        $this->assertInstanceOf(IssueType::class, $issue->getIssueType(), 'getIssueType() should return an IssueType instance when issuetype field is present');
        $this->assertSame('Bug', $issue->getIssueType()?->getName(), 'getIssueType() should hydrate the issue type name correctly');
    }

    public function test_get_project_returns_project_model(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'K-1', 'fields' => [
            'project' => ['id' => '10000', 'key' => 'TEST', 'name' => 'Test Project', 'self' => '', 'projectTypeKey' => 'software', 'simplified' => false, 'avatarUrls' => []],
        ]]);

        $this->assertInstanceOf(Project::class, $issue->getProject(), 'getProject() should return a Project instance when project field is present');
        $this->assertSame('TEST', $issue->getProject()?->getKey(), 'getProject() should hydrate the project key correctly');
    }

    public function test_get_resolution_returns_resolution_model(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'K-1', 'fields' => [
            'resolution' => ['id' => '1', 'name' => 'Done', 'description' => 'Work is done.', 'self' => ''],
        ]]);

        $this->assertInstanceOf(Resolution::class, $issue->getResolution(), 'getResolution() should return a Resolution instance when resolution field is present');
        $this->assertSame('Done', $issue->getResolution()?->getName(), 'getResolution() should hydrate the resolution name correctly');
    }

    public function test_get_scalar_field_getters(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'K-1', 'fields' => [
            'created' => '2024-01-01T00:00:00.000+0000',
            'updated' => '2024-01-02T00:00:00.000+0000',
            'duedate' => '2024-06-30',
            'resolutiondate' => '2024-01-15T12:00:00.000+0000',
            'labels' => ['backend', 'urgent'],
        ]]);

        $this->assertSame('2024-01-01T00:00:00.000+0000', $issue->getCreated(), 'getCreated() should return the created timestamp from fields');
        $this->assertSame('2024-01-02T00:00:00.000+0000', $issue->getUpdated(), 'getUpdated() should return the updated timestamp from fields');
        $this->assertSame('2024-06-30', $issue->getDueDate(), 'getDueDate() should return the due date from fields');
        $this->assertSame('2024-01-15T12:00:00.000+0000', $issue->getResolutionDate(), 'getResolutionDate() should return the resolution date from fields');
        $this->assertSame(['backend', 'urgent'], $issue->getLabels(), 'getLabels() should return the labels array from fields');
    }

    public function test_typed_getters_return_null_when_fields_absent(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'K-1', 'fields' => []]);

        $this->assertNull($issue->getAssignee(), 'getAssignee() should return null when assignee field is not present');
        $this->assertNull($issue->getReporter(), 'getReporter() should return null when reporter field is not present');
        $this->assertNull($issue->getPriority(), 'getPriority() should return null when priority field is not present');
        $this->assertNull($issue->getIssueType(), 'getIssueType() should return null when issuetype field is not present');
        $this->assertNull($issue->getProject(), 'getProject() should return null when project field is not present');
        $this->assertNull($issue->getResolution(), 'getResolution() should return null when resolution field is not present');
        $this->assertNull($issue->getCreated(), 'getCreated() should return null when created field is not present');
        $this->assertNull($issue->getUpdated(), 'getUpdated() should return null when updated field is not present');
        $this->assertNull($issue->getDueDate(), 'getDueDate() should return null when duedate field is not present');
        $this->assertNull($issue->getResolutionDate(), 'getResolutionDate() should return null when resolutiondate field is not present');
        $this->assertNull($issue->getLabels(), 'getLabels() should return null when labels field is not present');
    }

    // ── Computed properties ───────────────────────────────────────────────

    public function test_get_link_uses_config_domain(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'KEY-99', 'fields' => []]);

        $this->assertSame('https://test.atlassian.net/browse/KEY-99', $issue->getLink(), 'getLink() should construct the browse URL using the configured Jira domain and the issue key');
    }

    public function test_get_summary_returns_null_when_not_set(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'KEY-1', 'fields' => []]);

        $this->assertNull($issue->getSummary(), 'getSummary() should return null when the summary field is not present in the issue fields');
    }
}
