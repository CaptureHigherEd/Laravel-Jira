<?php

namespace CaptureHigherEd\LaravelJira\Tests\Exception;

use CaptureHigherEd\LaravelJira\Exception\CustomFieldDoesNotExistException;
use PHPUnit\Framework\TestCase;

class CustomFieldDoesNotExistExceptionTest extends TestCase
{
    public function test_message_includes_field_name(): void
    {
        $e = new CustomFieldDoesNotExistException('My Field');

        $this->assertStringContainsString('My Field', $e->getMessage());
    }

    public function test_message_without_field_name(): void
    {
        $e = new CustomFieldDoesNotExistException;

        $this->assertSame('Custom field does not exist.', $e->getMessage());
    }

    public function test_message_with_empty_string(): void
    {
        $e = new CustomFieldDoesNotExistException('');

        $this->assertSame('Custom field does not exist.', $e->getMessage());
    }
}
