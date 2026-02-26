<?php

namespace CaptureHigherEd\LaravelJira\Tests\Exception;

use CaptureHigherEd\LaravelJira\Exception\HttpClientException;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class HttpClientExceptionTest extends TestCase
{
    use MocksHttpResponses;

    // ── Constructor branching ────────────────────────────────────────────

    public function test_constructor_parses_json_body(): void
    {
        $response = $this->jsonResponse(['message' => 'Bad input', 'status' => 400]);
        $e = new HttpClientException('Test', 400, $response);

        $this->assertSame(['message' => 'Bad input', 'status' => 400], $e->getResponseBody());
    }

    public function test_constructor_stores_non_json_body_as_message(): void
    {
        $response = $this->plainErrorResponse(400, 'Plain text error');
        $e = new HttpClientException('Test', 400, $response);

        $this->assertSame(['message' => 'Plain text error'], $e->getResponseBody());
    }

    public function test_constructor_handles_empty_json_body(): void
    {
        $response = $this->jsonResponse([], 200);
        $e = new HttpClientException('Test', 200, $response);

        $this->assertSame([], $e->getResponseBody());
    }

    public function test_constructor_stores_response_code(): void
    {
        $response = $this->jsonResponse([], 404);
        $e = new HttpClientException('Test', 404, $response);

        $this->assertSame(404, $e->getResponseCode());
    }

    public function test_constructor_stores_response(): void
    {
        $response = $this->jsonResponse([], 200);
        $e = new HttpClientException('Test', 200, $response);

        $this->assertSame($response, $e->getResponse());
    }

    // ── Factory: badRequest ──────────────────────────────────────────────

    public function test_bad_request_extracts_json_message(): void
    {
        $response = $this->jsonErrorResponse(400, ['message' => 'Invalid field value']);
        $e = HttpClientException::badRequest($response);

        $this->assertSame(400, $e->getCode());
        $this->assertStringContainsString('Invalid field value', $e->getMessage());
    }

    public function test_bad_request_uses_raw_body_when_non_json(): void
    {
        $response = $this->plainErrorResponse(400, 'raw error body');
        $e = HttpClientException::badRequest($response);

        $this->assertStringContainsString('raw error body', $e->getMessage());
    }

    public function test_bad_request_falls_back_when_no_message_key(): void
    {
        $response = $this->jsonErrorResponse(400, ['errorMessages' => ['Something failed']]);
        $e = HttpClientException::badRequest($response);

        // Falls back to raw JSON string since no 'message' key
        $this->assertSame(400, $e->getCode());
        $this->assertStringContainsString('parameters passed to the API', $e->getMessage());
    }

    // ── Factory: simple factories ────────────────────────────────────────

    public function test_unauthorized_message(): void
    {
        $response = $this->plainErrorResponse(401, '');
        $e = HttpClientException::unauthorized($response);

        $this->assertSame(401, $e->getCode());
        $this->assertStringContainsString('credentials', $e->getMessage());
    }

    public function test_request_failed_message(): void
    {
        $response = $this->plainErrorResponse(402, '');
        $e = HttpClientException::requestFailed($response);

        $this->assertSame(402, $e->getCode());
        $this->assertStringContainsString('valid', $e->getMessage());
    }

    public function test_not_found_message(): void
    {
        $response = $this->plainErrorResponse(404, '');
        $e = HttpClientException::notFound($response);

        $this->assertSame(404, $e->getCode());
        $this->assertStringContainsString('does not exist', $e->getMessage());
    }

    public function test_conflict_message(): void
    {
        $response = $this->plainErrorResponse(409, '');
        $e = HttpClientException::conflict($response);

        $this->assertSame(409, $e->getCode());
        $this->assertStringContainsString('conflict', strtolower($e->getMessage()));
    }

    public function test_payload_too_large_message(): void
    {
        $response = $this->plainErrorResponse(413, '');
        $e = HttpClientException::payloadTooLarge($response);

        $this->assertSame(413, $e->getCode());
        $this->assertStringContainsString('too large', strtolower($e->getMessage()));
    }

    // ── Factory: forbidden ───────────────────────────────────────────────

    public function test_forbidden_extracts_json_error_key(): void
    {
        $response = $this->jsonErrorResponse(403, ['Error' => 'Access denied']);
        $e = HttpClientException::forbidden($response);

        $this->assertSame(403, $e->getCode());
        $this->assertStringContainsString('Access denied', $e->getMessage());
    }

    public function test_forbidden_uses_raw_body_when_non_json(): void
    {
        $response = $this->plainErrorResponse(403, 'Forbidden response');
        $e = HttpClientException::forbidden($response);

        $this->assertStringContainsString('Forbidden response', $e->getMessage());
    }

    // ── Factory: tooManyRequests ─────────────────────────────────────────

    public function test_too_many_requests_with_retry_after_header(): void
    {
        $response = $this->mockResponse(429, '', ['Retry-After' => '30']);
        $e = HttpClientException::tooManyRequests($response);

        $this->assertSame(429, $e->getCode());
        $this->assertStringContainsString('30', $e->getMessage());
    }

    public function test_too_many_requests_without_retry_after(): void
    {
        $response = $this->plainErrorResponse(429, '');
        $e = HttpClientException::tooManyRequests($response);

        $this->assertSame(429, $e->getCode());
        $this->assertSame('Too many requests.', $e->getMessage());
    }

    // ── Factory: unprocessableEntity ────────────────────────────────────

    public function test_unprocessable_entity_extracts_error_messages(): void
    {
        $response = $this->jsonErrorResponse(422, ['errorMessages' => ['Field A is required', 'Field B is invalid']]);
        $e = HttpClientException::unprocessableEntity($response);

        $this->assertSame(422, $e->getCode());
        $this->assertStringContainsString('Field A is required', $e->getMessage());
        $this->assertStringContainsString('Field B is invalid', $e->getMessage());
    }

    public function test_unprocessable_entity_non_json_fallback(): void
    {
        $response = $this->plainErrorResponse(422, 'Validation failed');
        $e = HttpClientException::unprocessableEntity($response);

        $this->assertSame(422, $e->getCode());
        $this->assertStringContainsString('Validation failed', $e->getMessage());
    }

    // ── Factory: serverError ─────────────────────────────────────────────

    public function test_server_error_includes_status_code(): void
    {
        foreach ([500, 502, 503] as $status) {
            $response = $this->plainErrorResponse($status, '');
            $e = HttpClientException::serverError($response);

            $this->assertSame($status, $e->getCode());
            $this->assertStringContainsString((string) $status, $e->getMessage());
        }
    }

    // ── Factory: unknown ─────────────────────────────────────────────────

    public function test_unknown_error(): void
    {
        $response = $this->plainErrorResponse(418, '');
        $e = HttpClientException::unknown($response);

        $this->assertSame(418, $e->getCode());
        $this->assertSame('Unknown Error', $e->getMessage());
    }
}
