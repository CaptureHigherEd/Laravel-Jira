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

        $this->assertSame(['message' => 'Bad input', 'status' => 400], $e->getResponseBody(), 'Constructor should parse and store JSON response body as an array');
    }

    public function test_constructor_stores_non_json_body_as_message(): void
    {
        $response = $this->plainErrorResponse(400, 'Plain text error');
        $e = new HttpClientException('Test', 400, $response);

        $this->assertSame(['message' => 'Plain text error'], $e->getResponseBody(), 'Constructor should wrap plain-text response body in a message array');
    }

    public function test_constructor_handles_empty_json_body(): void
    {
        $response = $this->jsonResponse([], 200);
        $e = new HttpClientException('Test', 200, $response);

        $this->assertSame([], $e->getResponseBody(), 'Constructor should store an empty array when the JSON response body is an empty object');
    }

    public function test_constructor_stores_response_code(): void
    {
        $response = $this->jsonResponse([], 404);
        $e = new HttpClientException('Test', 404, $response);

        $this->assertSame(404, $e->getResponseCode(), 'Constructor should store the HTTP status code for retrieval via getResponseCode()');
    }

    public function test_constructor_stores_response(): void
    {
        $response = $this->jsonResponse([], 200);
        $e = new HttpClientException('Test', 200, $response);

        $this->assertSame($response, $e->getResponse(), 'Constructor should store the original PSR response object for retrieval via getResponse()');
    }

    // ── Factory: badRequest ──────────────────────────────────────────────

    public function test_bad_request_extracts_json_message(): void
    {
        $response = $this->jsonErrorResponse(400, ['message' => 'Invalid field value']);
        $e = HttpClientException::badRequest($response);

        $this->assertSame(400, $e->getCode(), 'badRequest() factory should set the exception code to 400');
        $this->assertStringContainsString('Invalid field value', $e->getMessage(), 'badRequest() factory should include the JSON message field in the exception message');
    }

    public function test_bad_request_uses_raw_body_when_non_json(): void
    {
        $response = $this->plainErrorResponse(400, 'raw error body');
        $e = HttpClientException::badRequest($response);

        $this->assertStringContainsString('raw error body', $e->getMessage(), 'badRequest() factory should include the raw body text when the response is not JSON');
    }

    public function test_bad_request_falls_back_when_no_message_key(): void
    {
        $response = $this->jsonErrorResponse(400, ['errorMessages' => ['Something failed']]);
        $e = HttpClientException::badRequest($response);

        // Falls back to raw JSON string since no 'message' key
        $this->assertSame(400, $e->getCode(), 'badRequest() factory should set the exception code to 400 even when the message key is absent');
        $this->assertStringContainsString('parameters passed to the API', $e->getMessage(), 'badRequest() factory should fall back to the generic bad request message when no message key exists in the JSON body');
    }

    // ── Factory: simple factories ────────────────────────────────────────

    public function test_unauthorized_message(): void
    {
        $response = $this->plainErrorResponse(401, '');
        $e = HttpClientException::unauthorized($response);

        $this->assertSame(401, $e->getCode(), 'unauthorized() factory should set the exception code to 401');
        $this->assertStringContainsString('credentials', $e->getMessage(), 'unauthorized() factory should mention credentials in the exception message');
    }

    public function test_request_failed_message(): void
    {
        $response = $this->plainErrorResponse(402, '');
        $e = HttpClientException::requestFailed($response);

        $this->assertSame(402, $e->getCode(), 'requestFailed() factory should set the exception code to 402');
        $this->assertStringContainsString('valid', $e->getMessage(), 'requestFailed() factory should mention valid in the exception message');
    }

    public function test_not_found_message(): void
    {
        $response = $this->plainErrorResponse(404, '');
        $e = HttpClientException::notFound($response);

        $this->assertSame(404, $e->getCode(), 'notFound() factory should set the exception code to 404');
        $this->assertStringContainsString('does not exist', $e->getMessage(), 'notFound() factory should indicate the resource does not exist in the exception message');
    }

    public function test_conflict_message(): void
    {
        $response = $this->plainErrorResponse(409, '');
        $e = HttpClientException::conflict($response);

        $this->assertSame(409, $e->getCode(), 'conflict() factory should set the exception code to 409');
        $this->assertStringContainsString('conflict', strtolower($e->getMessage()), 'conflict() factory should mention conflict in the exception message');
    }

    public function test_payload_too_large_message(): void
    {
        $response = $this->plainErrorResponse(413, '');
        $e = HttpClientException::payloadTooLarge($response);

        $this->assertSame(413, $e->getCode(), 'payloadTooLarge() factory should set the exception code to 413');
        $this->assertStringContainsString('too large', strtolower($e->getMessage()), 'payloadTooLarge() factory should indicate the payload is too large in the exception message');
    }

    // ── Factory: forbidden ───────────────────────────────────────────────

    public function test_forbidden_extracts_json_error_key(): void
    {
        $response = $this->jsonErrorResponse(403, ['Error' => 'Access denied']);
        $e = HttpClientException::forbidden($response);

        $this->assertSame(403, $e->getCode(), 'forbidden() factory should set the exception code to 403');
        $this->assertStringContainsString('Access denied', $e->getMessage(), 'forbidden() factory should extract and include the Error key from the JSON body');
    }

    public function test_forbidden_uses_raw_body_when_non_json(): void
    {
        $response = $this->plainErrorResponse(403, 'Forbidden response');
        $e = HttpClientException::forbidden($response);

        $this->assertStringContainsString('Forbidden response', $e->getMessage(), 'forbidden() factory should include the raw body text when the response is not JSON');
    }

    // ── Factory: tooManyRequests ─────────────────────────────────────────

    public function test_too_many_requests_with_retry_after_header(): void
    {
        $response = $this->mockResponse(429, '', ['Retry-After' => '30']);
        $e = HttpClientException::tooManyRequests($response);

        $this->assertSame(429, $e->getCode(), 'tooManyRequests() factory should set the exception code to 429');
        $this->assertStringContainsString('30', $e->getMessage(), 'tooManyRequests() factory should include the Retry-After value in the exception message');
    }

    public function test_too_many_requests_without_retry_after(): void
    {
        $response = $this->plainErrorResponse(429, '');
        $e = HttpClientException::tooManyRequests($response);

        $this->assertSame(429, $e->getCode(), 'tooManyRequests() factory should set the exception code to 429 when no Retry-After header is present');
        $this->assertSame('Too many requests.', $e->getMessage(), 'tooManyRequests() factory should use the generic message when no Retry-After header is present');
    }

    // ── Factory: unprocessableEntity ────────────────────────────────────

    public function test_unprocessable_entity_extracts_error_messages(): void
    {
        $response = $this->jsonErrorResponse(422, ['errorMessages' => ['Field A is required', 'Field B is invalid']]);
        $e = HttpClientException::unprocessableEntity($response);

        $this->assertSame(422, $e->getCode(), 'unprocessableEntity() factory should set the exception code to 422');
        $this->assertStringContainsString('Field A is required', $e->getMessage(), 'unprocessableEntity() factory should include the first errorMessages entry in the exception message');
        $this->assertStringContainsString('Field B is invalid', $e->getMessage(), 'unprocessableEntity() factory should include all errorMessages entries in the exception message');
    }

    public function test_unprocessable_entity_non_json_fallback(): void
    {
        $response = $this->plainErrorResponse(422, 'Validation failed');
        $e = HttpClientException::unprocessableEntity($response);

        $this->assertSame(422, $e->getCode(), 'unprocessableEntity() factory should set the exception code to 422 for non-JSON responses');
        $this->assertStringContainsString('Validation failed', $e->getMessage(), 'unprocessableEntity() factory should include the raw body text when the response is not JSON');
    }

    // ── Factory: serverError ─────────────────────────────────────────────

    public function test_server_error_includes_status_code(): void
    {
        foreach ([500, 502, 503] as $status) {
            $response = $this->plainErrorResponse($status, '');
            $e = HttpClientException::serverError($response);

            $this->assertSame($status, $e->getCode(), "serverError() factory should set the exception code to $status");
            $this->assertStringContainsString((string) $status, $e->getMessage(), "serverError() factory should include the HTTP status code $status in the exception message");
        }
    }

    // ── Factory: unknown ─────────────────────────────────────────────────

    public function test_unknown_error(): void
    {
        $response = $this->plainErrorResponse(418, '');
        $e = HttpClientException::unknown($response);

        $this->assertSame(418, $e->getCode(), 'unknown() factory should preserve the HTTP status code from the response');
        $this->assertSame('Unknown Error', $e->getMessage(), 'unknown() factory should use the generic "Unknown Error" message');
    }
}
