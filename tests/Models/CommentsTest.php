<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Comment;
use CaptureHigherEd\LaravelJira\Models\Comments;
use PHPUnit\Framework\TestCase;

class CommentsTest extends TestCase
{
    public function test_make_with_empty_data(): void
    {
        $collection = Comments::make();

        $this->assertSame([], $collection->getComments(), 'Comments should default to an empty comments array when not provided');
        $this->assertSame(0, $collection->getTotal(), 'Comments total should default to 0 when not provided');
        $this->assertSame(0, $collection->getMaxResults(), 'Comments maxResults should default to 0 when not provided');
        $this->assertSame(0, $collection->getStartAt(), 'Comments startAt should default to 0 when not provided');
    }

    public function test_make_hydrates_comments_and_pagination(): void
    {
        $data = [
            'comments' => [
                ['id' => '1', 'body' => [], 'created' => '', 'updated' => '', 'self' => '', 'jsdPublic' => true, 'visibility' => []],
                ['id' => '2', 'body' => [], 'created' => '', 'updated' => '', 'self' => '', 'jsdPublic' => true, 'visibility' => []],
            ],
            'total' => 10,
            'maxResults' => 50,
            'startAt' => 0,
        ];

        $collection = Comments::make($data);

        $this->assertCount(2, $collection->getComments(), 'Comments should hydrate the correct number of comments');
        $this->assertInstanceOf(Comment::class, $collection->getComments()[0], 'Each comment should be hydrated as a Comment instance');
        $this->assertSame(10, $collection->getTotal(), 'Comments total should be hydrated correctly');
        $this->assertSame(50, $collection->getMaxResults(), 'Comments maxResults should be hydrated correctly');
        $this->assertSame(0, $collection->getStartAt(), 'Comments startAt should be hydrated correctly');
    }

    public function test_to_array_roundtrip(): void
    {
        $data = [
            'comments' => [
                ['id' => '1', 'body' => [], 'created' => '', 'updated' => '', 'self' => '', 'author' => null, 'updateAuthor' => null, 'jsdPublic' => true, 'visibility' => []],
            ],
            'total' => 1,
            'maxResults' => 50,
            'startAt' => 0,
        ];

        $collection = Comments::make($data);

        $this->assertSame($data, $collection->toArray(), 'Comments::toArray() should return the same data passed to make()');
    }
}
