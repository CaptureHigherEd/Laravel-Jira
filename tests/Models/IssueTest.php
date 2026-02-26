<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Providers\IntegrationServiceProvider;
use Orchestra\Testbench\TestCase;

class IssueTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [IntegrationServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('jira.token', base64_encode('test@example.com:fake-token'));
        $app['config']->set('jira.domain', 'https://test.atlassian.net');
    }

    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => '10001',
            'key' => 'KEY-1',
            'fields' => ['summary' => 'Test Issue', 'priority' => 'High'],
        ];

        $issue = Issue::make($data);

        $this->assertSame('10001', $issue->getId(), 'Issue ID should match the input id value');
        $this->assertSame('KEY-1', $issue->getKey(), 'Issue key should match the input key value');
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

    public function test_set_custom_field_by_value_multi_select(): void
    {
        $issue = Issue::make();
        $issue->setCustomFieldByValue('customfield_10001', 'Option A', true);

        $this->assertSame([['value' => 'Option A']], $issue->getFields()['customfield_10001'], 'Multi-select custom field should be stored as an array of value objects');
    }

    public function test_set_custom_field_by_value_single_select(): void
    {
        $issue = Issue::make();
        $issue->setCustomFieldByValue('customfield_10001', 'Option B', false);

        $this->assertSame(['value' => 'Option B'], $issue->getFields()['customfield_10001'], 'Single-select custom field should be stored as a single value object');
    }

    public function test_set_custom_field_by_value_null_acts_as_single(): void
    {
        // null is falsy so treated as single-select
        $issue = Issue::make();
        $issue->setCustomFieldByValue('customfield_10001', 'Option C', null);

        $this->assertSame(['value' => 'Option C'], $issue->getFields()['customfield_10001'], 'Custom field with null multi-select flag should be treated as single-select');
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
