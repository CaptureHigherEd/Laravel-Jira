<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\Fields;
use CaptureHigherEd\LaravelJira\Models\Field;
use CaptureHigherEd\LaravelJira\Models\Fields as ModelsFields;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use CaptureHigherEd\LaravelJira\Tests\Concerns\UsesTestbench;
use Orchestra\Testbench\TestCase;

class FieldsTest extends TestCase
{
    use MocksHttpResponses;
    use UsesTestbench;

    /** @return array<string, mixed> */
    private function createMetaData(): array
    {
        return [
            'projects' => [
                [
                    'key' => 'CBE4',
                    'issuetypes' => [
                        [
                            'name' => 'Bug',
                            'fields' => [
                                'customfield_10001' => [
                                    'allowedValues' => [
                                        ['value' => 'Option A'],
                                        ['value' => 'Option B'],
                                        ['value' => 'Option C'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'OTHER',
                    'issuetypes' => [],
                ],
            ],
        ];
    }

    private function makeCustomField(string $id): Field
    {
        return Field::make(['id' => $id, 'key' => $id, 'name' => $id, 'custom' => true, 'orderable' => false, 'navigable' => false, 'searchable' => false, 'clauseNames' => [], 'schema' => []]);
    }

    private function makeFieldsApiWithCreateMeta(): Fields
    {
        $response = $this->jsonResponse($this->createMetaData());
        $client = $this->mockClientExpecting('GET', 'issue/createmeta', ['query' => ['expand' => 'projects.issuetypes.fields']], $response);

        return new Fields($client);
    }

    // ── index ─────────────────────────────────────────────────────────────

    public function test_index(): void
    {
        $fieldData = [
            ['id' => 'summary', 'key' => 'summary', 'name' => 'Summary', 'custom' => false, 'orderable' => true, 'navigable' => true, 'searchable' => true, 'clauseNames' => [], 'schema' => []],
        ];
        $response = $this->jsonResponse($fieldData);
        $client = $this->mockClientExpecting('GET', 'field', ['query' => []], $response);
        $api = new Fields($client);

        $result = $api->index();

        $this->assertInstanceOf(ModelsFields::class, $result, 'Fields::index() should return a Fields model instance');
        $this->assertCount(1, $result->getFields(), 'Fields::index() should return exactly 1 field from the response');
    }

    // ── getFieldOptions ───────────────────────────────────────────────────

    public function test_get_field_options_happy_path(): void
    {
        $api = $this->makeFieldsApiWithCreateMeta();
        $field = $this->makeCustomField('customfield_10001');

        $result = $api->getFieldOptions($field, 'CBE4', 'Bug');

        $this->assertSame(['Option A' => 'Option A', 'Option B' => 'Option B', 'Option C' => 'Option C'], $result, 'getFieldOptions() should return allowed values keyed and valued by their value string');
    }

    /** @dataProvider fieldOptionsNotFoundProvider */
    public function test_get_field_options_not_found(string $fieldId, string $projectKey, string $issueType, string $message): void
    {
        $api = $this->makeFieldsApiWithCreateMeta();
        $field = $this->makeCustomField($fieldId);

        $result = $api->getFieldOptions($field, $projectKey, $issueType);

        $this->assertSame([], $result, $message);
    }

    /** @return array<string, array{string, string, string, string}> */
    public static function fieldOptionsNotFoundProvider(): array
    {
        return [
            'project not found' => [
                'customfield_10001',
                'NONEXISTENT',
                'Bug',
                'getFieldOptions() should return an empty array when the specified project key does not exist in the metadata',
            ],
            'issue type not found' => [
                'customfield_10001',
                'CBE4',
                'Story',
                'getFieldOptions() should return an empty array when the specified issue type does not exist in the project',
            ],
            'field not found' => [
                'customfield_99999',
                'CBE4',
                'Bug',
                'getFieldOptions() should return an empty array when the field ID does not exist in the issue type fields',
            ],
        ];
    }
}
