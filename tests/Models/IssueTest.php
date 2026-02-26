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

        $this->assertSame('10001', $issue->getId());
        $this->assertSame('KEY-1', $issue->getKey());
        $this->assertSame('Test Issue', $issue->getSummary());
    }

    public function test_make_with_empty_data(): void
    {
        $issue = Issue::make();

        $this->assertSame('', $issue->getId());
        $this->assertSame('', $issue->getKey());
        $this->assertSame([], $issue->getFields());
        $this->assertNull($issue->getSummary());
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

        $this->assertArrayHasKey('summary', $fields);
        $this->assertArrayNotHasKey('description', $fields);
        $this->assertArrayNotHasKey('priority', $fields);
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

        $this->assertArrayHasKey('count', $fields);
        $this->assertArrayHasKey('label', $fields);
        $this->assertArrayHasKey('active', $fields);
        $this->assertSame(0, $fields['count']);
        $this->assertSame('', $fields['label']);
        $this->assertFalse($fields['active']);
    }

    public function test_set_custom_field_by_value_multi_select(): void
    {
        $issue = Issue::make();
        $issue->setCustomFieldByValue('customfield_10001', 'Option A', true);

        $this->assertSame([['value' => 'Option A']], $issue->getFields()['customfield_10001']);
    }

    public function test_set_custom_field_by_value_single_select(): void
    {
        $issue = Issue::make();
        $issue->setCustomFieldByValue('customfield_10001', 'Option B', false);

        $this->assertSame(['value' => 'Option B'], $issue->getFields()['customfield_10001']);
    }

    public function test_set_custom_field_by_value_null_acts_as_single(): void
    {
        // null is falsy so treated as single-select
        $issue = Issue::make();
        $issue->setCustomFieldByValue('customfield_10001', 'Option C', null);

        $this->assertSame(['value' => 'Option C'], $issue->getFields()['customfield_10001']);
    }

    public function test_set_project_by_key(): void
    {
        $issue = Issue::make();
        $issue->setProjectByKey('CBE4');

        $this->assertSame(['key' => 'CBE4'], $issue->getFields()['project']);
    }

    public function test_set_description_wraps_in_adf(): void
    {
        $issue = Issue::make();
        $content = [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello']]]];
        $issue->setDescription($content);

        $description = $issue->getFields()['description'];

        $this->assertSame($content, $description['content']);
        $this->assertSame('doc', $description['type']);
        $this->assertSame(1, $description['version']);
    }

    public function test_set_reporter_wraps_in_id(): void
    {
        $issue = Issue::make();
        $issue->setReporter('abc123');

        $this->assertSame(['id' => 'abc123'], $issue->getFields()['reporter']);
    }

    public function test_get_link_uses_config_domain(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'KEY-99', 'fields' => []]);

        $this->assertSame('https://test.atlassian.net/browse/KEY-99', $issue->getLink());
    }

    public function test_get_summary_returns_null_when_not_set(): void
    {
        $issue = Issue::make(['id' => '1', 'key' => 'KEY-1', 'fields' => []]);

        $this->assertNull($issue->getSummary());
    }
}
