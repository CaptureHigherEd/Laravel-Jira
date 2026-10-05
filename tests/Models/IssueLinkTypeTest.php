<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\IssueLinkType;
use PHPUnit\Framework\TestCase;

class IssueLinkTypeTest extends TestCase
{
    public function test_make_with_empty_data(): void
    {
        $type = IssueLinkType::make();

        $this->assertSame('', $type->getId(), 'IssueLinkType ID should default to an empty string when not provided');
        $this->assertSame('', $type->getName(), 'IssueLinkType name should default to an empty string when not provided');
        $this->assertSame('', $type->getInward(), 'IssueLinkType inward should default to an empty string when not provided');
        $this->assertSame('', $type->getOutward(), 'IssueLinkType outward should default to an empty string when not provided');
        $this->assertSame('', $type->getSelf(), 'IssueLinkType self URL should default to an empty string when not provided');
    }

    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => '10001',
            'name' => 'Blocks',
            'inward' => 'is blocked by',
            'outward' => 'blocks',
            'self' => 'https://example.atlassian.net/rest/api/3/issueLinkType/10001',
        ];

        $type = IssueLinkType::make($data);

        $this->assertSame($data, $type->toArray(), 'IssueLinkType::toArray() should return the same data passed to make()');
    }
}
