<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Field;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => 'customfield_10001',
            'key' => 'customfield_10001',
            'name' => 'Story Points',
            'custom' => true,
            'orderable' => true,
            'navigable' => true,
            'searchable' => true,
            'clauseNames' => ['cf[10001]', 'Story Points'],
            'schema' => ['type' => 'number', 'custom' => 'com.atlassian.jira.plugin.system.customfieldtypes:float'],
            'scope' => ['type' => 'PROJECT', 'project' => ['id' => '10000']],
        ];

        $field = Field::make($data);

        $this->assertSame($data, $field->toArray(), 'Field::toArray() should return the same data that was passed to make()');
    }

    public function test_make_with_empty_data(): void
    {
        $field = Field::make();

        $this->assertSame('', $field->getId(), 'Field ID should default to an empty string when not provided');
        $this->assertSame('', $field->getKey(), 'Field key should default to an empty string when not provided');
        $this->assertSame('', $field->getName(), 'Field name should default to an empty string when not provided');
        $this->assertFalse($field->getCustom(), 'Field custom flag should default to false when not provided');
        $this->assertFalse($field->getOrderable(), 'Field orderable flag should default to false when not provided');
        $this->assertFalse($field->getNavigable(), 'Field navigable flag should default to false when not provided');
        $this->assertFalse($field->getSearchable(), 'Field searchable flag should default to false when not provided');
        $this->assertSame([], $field->getClauseNames(), 'Field clauseNames should default to an empty array when not provided');
        $this->assertSame([], $field->getSchema(), 'Field schema should default to an empty array when not provided');
        $this->assertSame([], $field->getScope(), 'Field scope should default to an empty array when not provided');
    }

    public function test_make_with_partial_data(): void
    {
        $field = Field::make([
            'id' => 'summary',
            'name' => 'Summary',
            'custom' => false,
        ]);

        $this->assertSame('summary', $field->getId(), 'Field ID should match the provided id value');
        $this->assertSame('Summary', $field->getName(), 'Field name should match the provided name value');
        $this->assertFalse($field->getCustom(), 'Field custom flag should be false when explicitly set to false');
        $this->assertSame('', $field->getKey(), 'Field key should default to an empty string when not included in partial data');
    }
}
