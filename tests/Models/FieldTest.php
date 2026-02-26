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
        ];

        $field = Field::make($data);

        $this->assertSame($data, $field->toArray());
    }

    public function test_make_with_empty_data(): void
    {
        $field = Field::make();

        $this->assertSame('', $field->getId());
        $this->assertSame('', $field->getKey());
        $this->assertSame('', $field->getName());
        $this->assertFalse($field->getCustom());
        $this->assertFalse($field->getOrderable());
        $this->assertFalse($field->getNavigable());
        $this->assertFalse($field->getSearchable());
        $this->assertSame([], $field->getClauseNames());
        $this->assertSame([], $field->getSchema());
    }

    public function test_make_with_partial_data(): void
    {
        $field = Field::make([
            'id' => 'summary',
            'name' => 'Summary',
            'custom' => false,
        ]);

        $this->assertSame('summary', $field->getId());
        $this->assertSame('Summary', $field->getName());
        $this->assertFalse($field->getCustom());
        $this->assertSame('', $field->getKey());
    }
}
