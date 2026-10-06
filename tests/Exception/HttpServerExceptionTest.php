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

    // ── networkError factory ──────────────────────────────────────────────

    public function test_network_error_sets_code_zero(): void
    {
        $cause = new \RuntimeException('Connection refused');
        $e = HttpServerException::networkError($cause);

        $this->assertSame(0, $e->getCode(), 'networkError() should set exception code to 0');
        $this->assertSame(0, $e->getResponseCode(), 'networkError() should report response code 0');
    }

    public function test_network_error_has_null_response(): void
    {
        $cause = new \RuntimeException('Timeout');
        $e = HttpServerException::networkError($cause);

        $this->assertNull($e->getResponse(), 'networkError() should return null from getResponse()');
    }

    public function test_network_error_wraps_previous_exception(): void
    {
        $cause = new \RuntimeException('DNS failure');
        $e = HttpServerException::networkError($cause);

        $this->assertSame($cause, $e->getPrevious(), 'networkError() should chain the original exception as previous');
    }

    public function test_network_error_returns_empty_response_body(): void
    {
        $e = HttpServerException::networkError(new \RuntimeException('err'));

        $this->assertSame([], $e->getResponseBody(), 'networkError() should return empty array from getResponseBody()');
    }

    // ── unknownHttpResponseCode factory ───────────────────────────────────

    public function test_unknown_http_response_code_sets_status(): void
    {
        $response = $this->mockResponse(302, '');
        $e = HttpServerException::unknownHttpResponseCode($response);

        $this->assertSame(302, $e->getCode(), 'unknownHttpResponseCode() should preserve the HTTP status code');
        $this->assertSame(302, $e->getResponseCode(), 'unknownHttpResponseCode() should report the correct response code');
        $this->assertStringContainsString('302', $e->getMessage(), 'unknownHttpResponseCode() should include the status code in the message');
    }

    public function test_unknown_http_response_code_stores_response(): void
    {
        $response = $this->mockResponse(504, '');
        $e = HttpServerException::unknownHttpResponseCode($response);

        $this->assertSame($response, $e->getResponse(), 'unknownHttpResponseCode() should store the original response');
    }
}
