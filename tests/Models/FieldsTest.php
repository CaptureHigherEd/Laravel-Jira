<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Exception\CustomFieldDoesNotExistException;
use CaptureHigherEd\LaravelJira\Models\Field;
use CaptureHigherEd\LaravelJira\Models\Fields;
use PHPUnit\Framework\TestCase;

class FieldsTest extends TestCase
{
    /** @return array<int, array<string, mixed>> */
    private function fieldData(): array
    {
        return [
            [
                'id' => 'summary',
                'key' => 'summary',
                'name' => 'Summary',
                'custom' => false,
                'orderable' => true,
                'navigable' => true,
                'searchable' => true,
                'clauseNames' => ['summary'],
                'schema' => ['type' => 'string'],
            ],
            [
                'id' => 'customfield_10001',
                'key' => 'customfield_10001',
                'name' => 'Story Points',
                'custom' => true,
                'orderable' => true,
                'navigable' => true,
                'searchable' => true,
                'clauseNames' => ['cf[10001]'],
                'schema' => ['type' => 'number'],
            ],
            [
                'id' => 'customfield_10002',
                'key' => 'customfield_10002',
                'name' => 'Epic Link',
                'custom' => true,
                'orderable' => false,
                'navigable' => true,
                'searchable' => true,
                'clauseNames' => ['cf[10002]'],
                'schema' => ['type' => 'string'],
            ],
        ];
    }

    public function test_make_roundtrip(): void
    {
        $data = $this->fieldData();
        $fields = Fields::make($data);

        $this->assertCount(3, $fields->getFields());
        $this->assertContainsOnlyInstancesOf(Field::class, $fields->getFields());
        $this->assertSame($data, $fields->toArray());
    }

    public function test_make_with_empty_array(): void
    {
        $fields = Fields::make([]);

        $this->assertSame([], $fields->getFields());
        $this->assertSame([], $fields->toArray());
    }

    public function test_get_custom_fields_filters_by_key_prefix(): void
    {
        $fields = Fields::make($this->fieldData());

        $custom = $fields->getCustomFields();

        $this->assertCount(2, $custom);
        foreach ($custom as $field) {
            $this->assertStringStartsWith('customfield_', $field->getKey());
        }
    }

    public function test_get_custom_field_id_returns_id(): void
    {
        $fields = Fields::make($this->fieldData());

        $id = $fields->getCustomFieldId('Story Points');

        $this->assertSame('customfield_10001', $id);
    }

    public function test_get_custom_field_id_throws_when_not_found(): void
    {
        $fields = Fields::make($this->fieldData());

        $this->expectException(CustomFieldDoesNotExistException::class);

        $fields->getCustomFieldId('Nonexistent Field');
    }

    public function test_get_custom_field_returns_field(): void
    {
        $fields = Fields::make($this->fieldData());

        $field = $fields->getCustomField('Epic Link');

        $this->assertInstanceOf(Field::class, $field);
        $this->assertSame('customfield_10002', $field->getId());
    }

    public function test_get_custom_field_throws_when_not_found(): void
    {
        $fields = Fields::make($this->fieldData());

        $this->expectException(CustomFieldDoesNotExistException::class);

        $fields->getCustomField('Nonexistent Field');
    }

    public function test_get_custom_field_id_ignores_non_custom_fields(): void
    {
        // 'Summary' field has key 'summary' (not customfield_*), so it's not in custom fields
        $fields = Fields::make($this->fieldData());

        $this->expectException(CustomFieldDoesNotExistException::class);

        $fields->getCustomFieldId('Summary');
    }
}
