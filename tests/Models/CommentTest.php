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

        $this->assertSame($data, $comment->toArray(), 'Comment::toArray() should return the same data that was passed to make()');
    }

    public function test_make_with_empty_data(): void
    {
        $comment = Comment::make();

        $this->assertSame('', $comment->getId(), 'Comment ID should default to an empty string when not provided');
        $this->assertSame([], $comment->getBody(), 'Comment body should default to an empty array when not provided');
        $this->assertSame('', $comment->getCreated(), 'Comment created timestamp should default to an empty string when not provided');
        $this->assertSame('', $comment->getUpdated(), 'Comment updated timestamp should default to an empty string when not provided');
        $this->assertSame('', $comment->getSelf(), 'Comment self URL should default to an empty string when not provided');
    }
}
