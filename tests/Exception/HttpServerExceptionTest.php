<?php

namespace CaptureHigherEd\LaravelJira\Tests\Exception;

use CaptureHigherEd\LaravelJira\Exception\HttpServerException;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class HttpServerExceptionTest extends TestCase
{
    use MocksHttpResponses;

    public function test_server_error_sets_status_code(): void
    {
        foreach ([500, 502, 503] as $status) {
            $response = $this->plainErrorResponse($status, '');
            $e = HttpServerException::serverError($response);

            $this->assertSame($status, $e->getCode(), "serverError() factory should set the exception code to $status");
            $this->assertStringContainsString((string) $status, $e->getMessage(), "serverError() factory should include the HTTP status code $status in the exception message");
        }
    }

    public function test_server_error_stores_response(): void
    {
        $response = $this->plainErrorResponse(500, '');
        $e = HttpServerException::serverError($response);

        $this->assertSame($response, $e->getResponse(), 'serverError() factory should store the original PSR response object');
    }

    public function test_server_error_stores_response_code(): void
    {
        $response = $this->plainErrorResponse(502, '');
        $e = HttpServerException::serverError($response);

        $this->assertSame(502, $e->getResponseCode(), 'getResponseCode() should return the HTTP status code');
    }

    public function test_constructor_parses_json_body(): void
    {
        $response = $this->jsonResponse(['message' => 'Internal error'], 500);
        $e = new HttpServerException('Server error', 500, $response);

        $this->assertSame(['message' => 'Internal error'], $e->getResponseBody(), 'Constructor should parse and store JSON response body');
    }

    public function test_constructor_stores_non_json_body_as_message(): void
    {
        $response = $this->plainErrorResponse(503, 'Service Unavailable');
        $e = new HttpServerException('Server error', 503, $response);

        $this->assertSame(['message' => 'Service Unavailable'], $e->getResponseBody(), 'Constructor should wrap plain-text response body in a message array');
    }
}
