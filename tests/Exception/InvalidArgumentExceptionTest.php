<?php

namespace CaptureHigherEd\LaravelJira\Tests\Exception;

use CaptureHigherEd\LaravelJira\Exception\InvalidArgumentException;
use CaptureHigherEd\LaravelJira\Exception\JiraException;
use PHPUnit\Framework\TestCase;

class InvalidArgumentExceptionTest extends TestCase
{
    public function test_implements_jira_exception(): void
    {
        $e = new InvalidArgumentException('bad input');

        $this->assertInstanceOf(JiraException::class, $e, 'InvalidArgumentException should implement JiraException');
    }

    public function test_extends_invalid_argument_exception(): void
    {
        $e = new InvalidArgumentException('bad input');

        $this->assertInstanceOf(\InvalidArgumentException::class, $e, 'InvalidArgumentException should extend \InvalidArgumentException');
    }

    public function test_stores_message(): void
    {
        $e = new InvalidArgumentException('bad input here');

        $this->assertSame('bad input here', $e->getMessage(), 'Exception should store the provided message');
    }
}
