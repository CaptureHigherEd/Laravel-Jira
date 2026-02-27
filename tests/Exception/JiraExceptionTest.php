<?php

namespace CaptureHigherEd\LaravelJira\Tests\Exception;

use CaptureHigherEd\LaravelJira\Exception\CustomFieldDoesNotExistException;
use CaptureHigherEd\LaravelJira\Exception\HttpClientException;
use CaptureHigherEd\LaravelJira\Exception\HttpServerException;
use CaptureHigherEd\LaravelJira\Exception\HydrationException;
use CaptureHigherEd\LaravelJira\Exception\JiraException;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class JiraExceptionTest extends TestCase
{
    use MocksHttpResponses;

    public function test_http_client_exception_implements_jira_exception(): void
    {
        $response = $this->plainErrorResponse(404, '');
        $e = HttpClientException::notFound($response);

        $this->assertInstanceOf(JiraException::class, $e, 'HttpClientException should implement JiraException');
    }

    public function test_http_server_exception_implements_jira_exception(): void
    {
        $response = $this->plainErrorResponse(500, '');
        $e = HttpServerException::serverError($response);

        $this->assertInstanceOf(JiraException::class, $e, 'HttpServerException should implement JiraException');
    }

    public function test_hydration_exception_implements_jira_exception(): void
    {
        $e = HydrationException::jsonDecodeFailed('bad json');

        $this->assertInstanceOf(JiraException::class, $e, 'HydrationException should implement JiraException');
    }

    public function test_custom_field_exception_implements_jira_exception(): void
    {
        $e = new CustomFieldDoesNotExistException('myField');

        $this->assertInstanceOf(JiraException::class, $e, 'CustomFieldDoesNotExistException should implement JiraException');
    }
}
