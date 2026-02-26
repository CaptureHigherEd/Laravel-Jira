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

    // ── make & toArray ────────────────────────────────────────────────────

    public function test_make_roundtrip(): void
    {
        $data = $this->fieldData();
        $fields = Fields::make($data);

        $this->assertCount(3, $fields->getFields(), 'Fields collection should contain exactly 3 items matching the input array');
        $this->assertContainsOnlyInstancesOf(Field::class, $fields->getFields(), 'All items in the Fields collection should be hydrated as Field instances');
        $this->assertSame($data, $fields->toArray(), 'Fields::toArray() should return the same data that was passed to make()');
    }

    public function test_make_with_empty_array(): void
    {
        $fields = Fields::make([]);

        $this->assertSame([], $fields->getFields(), 'Fields collection should be an empty array when constructed with no data');
        $this->assertSame([], $fields->toArray(), 'Fields::toArray() should return an empty array when no fields are present');
    }

    // ── Custom field lookup ───────────────────────────────────────────────

    public function test_get_custom_fields_filters_by_key_prefix(): void
    {
        $fields = Fields::make($this->fieldData());

        $custom = $fields->getCustomFields();

        $this->assertCount(2, $custom, 'getCustomFields() should return only the 2 fields with a customfield_ key prefix');
        foreach ($custom as $field) {
            $this->assertStringStartsWith('customfield_', $field->getKey(), 'Each custom field returned by getCustomFields() should have a key starting with "customfield_"');
        }
    }

    public function test_get_custom_field_id_returns_id(): void
    {
        $fields = Fields::make($this->fieldData());

        $id = $fields->getCustomFieldId('Story Points');

        $this->assertSame('customfield_10001', $id, 'getCustomFieldId() should return the correct field ID for "Story Points"');
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

        $this->assertInstanceOf(Field::class, $field, 'getCustomField() should return a Field instance for a known custom field name');
        $this->assertSame('customfield_10002', $field->getId(), 'getCustomField() should return the field with the correct ID for "Epic Link"');
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
