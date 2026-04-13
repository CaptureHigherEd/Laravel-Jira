<?php

namespace tests;

use CaptureHigherEd\LaravelJira\Models\Issue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IssueTest extends TestCase
{
    #[Test]
    public function get_description_sanitizes_empty_attrs_to_objects(): void
    {
        $description = [
            'type' => 'doc',
            'version' => 1,
            'content' => [
                [
                    'type' => 'table',
                    'attrs' => [],
                    'content' => [
                        [
                            'type' => 'tableRow',
                            'content' => [
                                [
                                    'type' => 'tableCell',
                                    'attrs' => [],
                                    'content' => [
                                        [
                                            'type' => 'paragraph',
                                            'content' => [
                                                ['type' => 'text', 'text' => 'Hello'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $issue = Issue::make([
            'key' => 'TEST-1',
            'fields' => ['description' => $description],
        ]);

        $result = $issue->getDescription();
        $json = json_encode($result);

        // Empty attrs should be encoded as {} (object), not [] (array)
        $this->assertStringNotContainsString('"attrs":[]', $json);
        $this->assertStringContainsString('"attrs":{}', $json);
    }

    #[Test]
    public function get_description_preserves_non_empty_attrs(): void
    {
        $description = [
            'type' => 'doc',
            'version' => 1,
            'content' => [
                [
                    'type' => 'table',
                    'attrs' => ['isNumberColumnEnabled' => false, 'layout' => 'default'],
                    'content' => [],
                ],
            ],
        ];

        $issue = Issue::make([
            'key' => 'TEST-2',
            'fields' => ['description' => $description],
        ]);

        $result = $issue->getDescription();
        $tableNode = $result['content'][0];

        $this->assertIsArray($tableNode['attrs']);
        $this->assertFalse($tableNode['attrs']['isNumberColumnEnabled']);
        $this->assertEquals('default', $tableNode['attrs']['layout']);
    }

    #[Test]
    public function get_description_sanitizes_empty_attrs_in_marks(): void
    {
        $description = [
            'type' => 'doc',
            'version' => 1,
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'link text',
                            'marks' => [
                                ['type' => 'link', 'attrs' => []],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $issue = Issue::make([
            'key' => 'TEST-3',
            'fields' => ['description' => $description],
        ]);

        $result = $issue->getDescription();
        $json = json_encode($result);

        $this->assertStringNotContainsString('"attrs":[]', $json);
        $this->assertStringContainsString('"attrs":{}', $json);
    }

    #[Test]
    public function get_description_returns_null_when_no_description(): void
    {
        $issue = Issue::make([
            'key' => 'TEST-4',
            'fields' => [],
        ]);

        $this->assertNull($issue->getDescription());
    }
}
