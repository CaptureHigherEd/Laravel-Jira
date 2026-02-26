<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\Fields;
use CaptureHigherEd\LaravelJira\Models\Field;
use CaptureHigherEd\LaravelJira\Models\Fields as ModelsFields;
use CaptureHigherEd\LaravelJira\Providers\IntegrationServiceProvider;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use Orchestra\Testbench\TestCase;

class FieldsTest extends TestCase
{
    use MocksHttpResponses;

    protected function getPackageProviders($app): array
    {
        return [IntegrationServiceProvider::class];
    }

    /** @return array<string, mixed> */
    private function createMetaResponse(): array
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

    public function test_index(): void
    {
        $fieldData = [
            ['id' => 'summary', 'key' => 'summary', 'name' => 'Summary', 'custom' => false, 'orderable' => true, 'navigable' => true, 'searchable' => true, 'clauseNames' => [], 'schema' => []],
        ];
        $response = $this->jsonResponse($fieldData);
        $client = $this->mockClientExpecting('GET', 'field', ['query' => []], $response);
        $api = new Fields($client);

        $result = $api->index();

        $this->assertInstanceOf(ModelsFields::class, $result);
        $this->assertCount(1, $result->getFields());
    }

    public function test_get_field_options_happy_path(): void
    {
        $meta = $this->createMetaResponse();
        $response = $this->jsonResponse($meta);
        $client = $this->mockClientExpecting('GET', 'issue/createmeta', ['query' => ['expand' => 'projects.issuetypes.fields']], $response);
        $api = new Fields($client);

        $field = Field::make(['id' => 'customfield_10001', 'key' => 'customfield_10001', 'name' => 'Priority', 'custom' => true, 'orderable' => false, 'navigable' => false, 'searchable' => false, 'clauseNames' => [], 'schema' => []]);
        $result = $api->getFieldOptions($field, 'CBE4', 'Bug');

        $this->assertSame(['Option A' => 'Option A', 'Option B' => 'Option B', 'Option C' => 'Option C'], $result);
    }

    public function test_get_field_options_project_not_found(): void
    {
        $meta = $this->createMetaResponse();
        $response = $this->jsonResponse($meta);
        $client = $this->mockClientExpecting('GET', 'issue/createmeta', ['query' => ['expand' => 'projects.issuetypes.fields']], $response);
        $api = new Fields($client);

        $field = Field::make(['id' => 'customfield_10001', 'key' => 'customfield_10001', 'name' => 'Priority', 'custom' => true, 'orderable' => false, 'navigable' => false, 'searchable' => false, 'clauseNames' => [], 'schema' => []]);
        $result = $api->getFieldOptions($field, 'NONEXISTENT', 'Bug');

        $this->assertSame([], $result);
    }

    public function test_get_field_options_issue_type_not_found(): void
    {
        $meta = $this->createMetaResponse();
        $response = $this->jsonResponse($meta);
        $client = $this->mockClientExpecting('GET', 'issue/createmeta', ['query' => ['expand' => 'projects.issuetypes.fields']], $response);
        $api = new Fields($client);

        $field = Field::make(['id' => 'customfield_10001', 'key' => 'customfield_10001', 'name' => 'Priority', 'custom' => true, 'orderable' => false, 'navigable' => false, 'searchable' => false, 'clauseNames' => [], 'schema' => []]);
        $result = $api->getFieldOptions($field, 'CBE4', 'Story');

        $this->assertSame([], $result);
    }

    public function test_get_field_options_field_not_found(): void
    {
        $meta = $this->createMetaResponse();
        $response = $this->jsonResponse($meta);
        $client = $this->mockClientExpecting('GET', 'issue/createmeta', ['query' => ['expand' => 'projects.issuetypes.fields']], $response);
        $api = new Fields($client);

        $field = Field::make(['id' => 'customfield_99999', 'key' => 'customfield_99999', 'name' => 'Nonexistent', 'custom' => true, 'orderable' => false, 'navigable' => false, 'searchable' => false, 'clauseNames' => [], 'schema' => []]);
        $result = $api->getFieldOptions($field, 'CBE4', 'Bug');

        $this->assertSame([], $result);
    }
}
