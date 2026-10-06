<?php

namespace CaptureHigherEd\LaravelJira\Tests;

use CaptureHigherEd\LaravelJira\Assert;
use CaptureHigherEd\LaravelJira\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AssertTest extends TestCase
{
    public function test_string_not_empty_passes_for_non_empty_string(): void
    {
        Assert::stringNotEmpty('value', 'Must not be empty');

        $this->addToAssertionCount(1);
    }

    public function test_string_not_empty_throws_for_empty_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Must not be empty');

        Assert::stringNotEmpty('', 'Must not be empty');
    }

    public function test_string_not_empty_uses_provided_message(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Issue ID must not be empty.');

        Assert::stringNotEmpty('', 'Issue ID must not be empty.');
    }
}
