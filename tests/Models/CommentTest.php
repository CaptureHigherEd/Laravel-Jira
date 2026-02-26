<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => '10001',
            'body' => ['type' => 'doc', 'version' => 1, 'content' => []],
            'created' => '2024-01-01T00:00:00.000+0000',
            'updated' => '2024-01-02T00:00:00.000+0000',
            'self' => 'https://example.atlassian.net/rest/api/3/issue/KEY-1/comment/10001',
        ];

        $comment = Comment::make($data);

        $this->assertSame($data, $comment->toArray());
    }

    public function test_make_with_empty_data(): void
    {
        $comment = Comment::make();

        $this->assertSame('', $comment->getId());
        $this->assertSame([], $comment->getBody());
        $this->assertSame('', $comment->getCreated());
        $this->assertSame('', $comment->getUpdated());
        $this->assertSame('', $comment->getSelf());
    }
}
