<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Comment;
use CaptureHigherEd\LaravelJira\Models\User;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function test_make_roundtrip(): void
    {
        $authorData = [
            'accountId' => 'abc123',
            'displayName' => 'Jane Smith',
            'emailAddress' => 'jane@example.com',
            'active' => true,
            'self' => '',
            'accountType' => '',
            'timeZone' => '',
            'locale' => '',
            'avatarUrls' => [],
        ];
        $data = [
            'id' => '10001',
            'body' => ['type' => 'doc', 'version' => 1, 'content' => []],
            'created' => '2024-01-01T00:00:00.000+0000',
            'updated' => '2024-01-02T00:00:00.000+0000',
            'self' => 'https://example.atlassian.net/rest/api/3/issue/KEY-1/comment/10001',
            'author' => $authorData,
            'updateAuthor' => $authorData,
            'jsdPublic' => true,
            'visibility' => [],
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
        $this->assertNull($comment->getAuthor(), 'Comment author should default to null when not provided');
        $this->assertNull($comment->getUpdateAuthor(), 'Comment updateAuthor should default to null when not provided');
        $this->assertTrue($comment->getJsdPublic(), 'Comment jsdPublic should default to true when not provided');
        $this->assertSame([], $comment->getVisibility(), 'Comment visibility should default to an empty array when not provided');
    }

    public function test_author_is_hydrated_as_user(): void
    {
        $comment = Comment::make([
            'id' => '1',
            'author' => ['accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => 'alice@example.com', 'active' => true],
        ]);

        $this->assertInstanceOf(User::class, $comment->getAuthor(), 'Comment author should be hydrated as a User instance');
        $this->assertSame('u1', $comment->getAuthor()?->getKey(), 'Comment author accountId should be hydrated correctly');
    }

    public function test_make_without_author_returns_null(): void
    {
        $comment = Comment::make(['id' => '1']);

        $this->assertNull($comment->getAuthor(), 'Comment author should be null when not present in the response data');
        $this->assertNull($comment->getUpdateAuthor(), 'Comment updateAuthor should be null when not present in the response data');
    }
}
