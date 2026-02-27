<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\HttpApi;
use CaptureHigherEd\LaravelJira\Exception\HttpClientException;
use CaptureHigherEd\LaravelJira\Exception\HttpServerException;
use CaptureHigherEd\LaravelJira\Exception\HydrationException;
use CaptureHigherEd\LaravelJira\Http\HttpClientConfig;
use CaptureHigherEd\LaravelJira\Models\ApiResponse;
use CaptureHigherEd\LaravelJira\Models\Paginated;
use CaptureHigherEd\LaravelJira\Models\Search;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class HttpApiTest extends TestCase
{
    use MocksHttpResponses;

    /** @return HttpApi&object{callHydrateResponse: callable, callHandleErrors: callable, callHttpGet: callable, callHttpPost: callable, callHttpPut: callable, callHttpDelete: callable, callHttpPostWithAttachments: callable, callHttpPostRaw: callable} */
    private function makeApiWithResponse(ResponseInterface $response): object
    {
        return $this->makeApi($this->makeConfig($response));
    }

    /** @return HttpApi&object{callHydrateResponse: callable, callHandleErrors: callable, callHttpGet: callable, callHttpPost: callable, callHttpPut: callable, callHttpDelete: callable, callHttpPostWithAttachments: callable, callHttpPostRaw: callable} */
    private function makeApi(HttpClientConfig $config): object
    {
        return new class($config) extends HttpApi
        {
            public function callHydrateResponse(ResponseInterface $response, ?string $class = null): mixed
            {
                return $this->hydrateResponse($response, $class);
            }

            public function callHandleErrors(ResponseInterface $response): void
            {
                $this->handleErrors($response);
            }

            public function callHttpGet(string $path, array $parameters = []): ResponseInterface
            {
                return $this->httpGet($path, $parameters);
            }

            public function callHttpPost(string $path, array $parameters = []): ResponseInterface
            {
                return $this->httpPost($path, $parameters);
            }

            public function callHttpPut(string $path, array $parameters = []): ResponseInterface
            {
                return $this->httpPut($path, $parameters);
            }

            public function callHttpDelete(string $path, array $parameters = []): ResponseInterface
            {
                return $this->httpDelete($path, $parameters);
            }

            public function callHttpPostWithAttachments(string $path, array $multipart = []): ResponseInterface
            {
                return $this->httpPostWithAttachments($path, $multipart);
            }

            public function callHttpPostRaw(string $path, string $body, string $contentType = 'application/json'): ResponseInterface
            {
                return $this->httpPostRaw($path, $body, $contentType);
            }

            /**
             * @template T of Paginated&ApiResponse
             *
             * @param  array<string, mixed>  $parameters
             * @param  class-string<T>  $class
             * @return \Generator<int, T, mixed, void>
             */
            public function callPaginateGet(string $path, array $parameters, string $class): \Generator
            {
                return $this->paginateGet($path, $parameters, $class);
            }
        };
    }

    // ── getLastResponse ───────────────────────────────────────────────────

    public function test_get_last_response_returns_null_initially(): void
    {
        $response = $this->jsonResponse([]);
        $api = $this->makeApiWithResponse($response);

        $this->assertNull($api->getLastResponse(), 'getLastResponse() should return null before any HTTP call is made');
    }

    public function test_get_last_response_stores_most_recent(): void
    {
        $response = $this->jsonResponse(['key' => 'value']);
        $api = $this->makeApiWithResponse($response);

        $api->callHttpGet('search');

        $this->assertSame($response, $api->getLastResponse(), 'getLastResponse() should return the last ResponseInterface after an HTTP call');
    }

    // ── hydrateResponse ──────────────────────────────────────────────────

    public function test_hydrate_200_with_class(): void
    {
        $response = $this->jsonResponse(['issues' => [['id' => '1', 'key' => 'KEY-1', 'fields' => []]]]);
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHydrateResponse($response, Search::class);

        $this->assertInstanceOf(Search::class, $result, 'hydrateResponse() should return a Search instance when given a 200 response and the Search class');
        $this->assertCount(1, $result->getIssues(), 'Hydrated Search result should contain exactly 1 issue from the response body');
    }

    public function test_hydrate_201_with_class(): void
    {
        $response = $this->mockResponse(201, ['id' => '10', 'key' => 'KEY-10', 'fields' => []]);
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHydrateResponse($response, Search::class);

        $this->assertInstanceOf(Search::class, $result, 'hydrateResponse() should return a Search instance when given a 201 Created response');
    }

    public function test_hydrate_204_with_class(): void
    {
        $response = $this->noContentResponse();
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHydrateResponse($response, Search::class);

        $this->assertInstanceOf(Search::class, $result, 'hydrateResponse() should return an empty hydrated instance when given a 204 No Content response with a class');
        $this->assertSame([], $result->getIssues(), 'Hydrated Search from a 204 response should have an empty issues array');
    }

    public function test_hydrate_204_without_class(): void
    {
        $response = $this->noContentResponse();
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHydrateResponse($response);

        $this->assertSame([], $result, 'hydrateResponse() should return an empty array when given a 204 No Content response without a class');
    }

    public function test_hydrate_empty_body_with_class(): void
    {
        $response = $this->mockResponse(200, '');
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHydrateResponse($response, Search::class);

        $this->assertInstanceOf(Search::class, $result, 'hydrateResponse() should return an empty hydrated instance when the response body is empty and a class is provided');
    }

    public function test_hydrate_empty_body_without_class(): void
    {
        $response = $this->mockResponse(200, '');
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHydrateResponse($response);

        $this->assertSame([], $result, 'hydrateResponse() should return an empty array when the response body is empty and no class is provided');
    }

    public function test_hydrate_200_without_class(): void
    {
        $data = ['key' => 'value', 'count' => 3];
        $response = $this->jsonResponse($data);
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHydrateResponse($response);

        $this->assertSame($data, $result, 'hydrateResponse() should return the raw decoded array when no class is provided');
    }

    public function test_hydrate_invalid_json_throws_hydration_exception(): void
    {
        $response = $this->mockResponse(200, 'not-valid-json', ['Content-Type' => 'application/json']);
        $api = $this->makeApiWithResponse($response);

        $this->expectException(HydrationException::class);

        $api->callHydrateResponse($response);
    }

    // ── handleErrors ─────────────────────────────────────────────────────

    /** @dataProvider clientErrorStatusProvider */
    public function test_handle_errors_throws_client_exception_for_4xx(int $status): void
    {
        $response = $this->plainErrorResponse($status, 'error');
        $api = $this->makeApiWithResponse($response);

        $this->expectException(HttpClientException::class);

        $api->callHandleErrors($response);
    }

    /** @return array<string, array{int}> */
    public static function clientErrorStatusProvider(): array
    {
        return [
            '400' => [400],
            '401' => [401],
            '402' => [402],
            '403' => [403],
            '404' => [404],
            '409' => [409],
            '413' => [413],
            '422' => [422],
            '429' => [429],
            '418 unknown' => [418],
        ];
    }

    /** @dataProvider serverErrorStatusProvider */
    public function test_handle_errors_throws_server_exception_for_5xx(int $status): void
    {
        $response = $this->plainErrorResponse($status, 'error');
        $api = $this->makeApiWithResponse($response);

        $this->expectException(HttpServerException::class);

        $api->callHandleErrors($response);
    }

    /** @return array<string, array{int}> */
    public static function serverErrorStatusProvider(): array
    {
        return [
            '500' => [500],
            '502' => [502],
            '503' => [503],
            '504 unknown server' => [504],
        ];
    }

    // ── HTTP verb delegation ──────────────────────────────────────────────

    public function test_http_get_returns_response(): void
    {
        $response = $this->jsonResponse([]);
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHttpGet('search', ['jql' => 'project=CBE4']);

        $this->assertSame($response, $result, 'httpGet() should return the PSR-7 response from sendRequest()');
    }

    public function test_http_post_returns_response(): void
    {
        $response = $this->jsonResponse(['id' => '1', 'key' => 'KEY-1', 'fields' => []]);
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHttpPost('issue', ['fields' => ['summary' => 'Test']]);

        $this->assertSame($response, $result, 'httpPost() should return the PSR-7 response from sendRequest()');
    }

    public function test_http_put_returns_response(): void
    {
        $response = $this->jsonResponse(['id' => '1', 'key' => 'KEY-1', 'fields' => []]);
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHttpPut('issue/KEY-1', ['fields' => ['summary' => 'Updated']]);

        $this->assertSame($response, $result, 'httpPut() should return the PSR-7 response from sendRequest()');
    }

    public function test_http_delete_returns_response(): void
    {
        $response = $this->noContentResponse();
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHttpDelete('issue/KEY-1');

        $this->assertSame($response, $result, 'httpDelete() should return the PSR-7 response from sendRequest()');
    }

    public function test_http_post_with_attachments_returns_response(): void
    {
        $response = $this->jsonResponse([]);
        $api = $this->makeApiWithResponse($response);
        $multipart = [['name' => 'file', 'contents' => 'file-data', 'filename' => 'test.txt']];

        $result = $api->callHttpPostWithAttachments('issue/KEY-1/attachments', $multipart);

        $this->assertSame($response, $result, 'httpPostWithAttachments() should return the PSR-7 response from sendRequest()');
    }

    public function test_http_post_raw_returns_response(): void
    {
        $response = $this->noContentResponse();
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHttpPostRaw('issue/KEY-1/watchers', '"u1"');

        $this->assertSame($response, $result, 'httpPostRaw() should return the PSR-7 response from sendRequest()');
    }

    // ── paginateGet ───────────────────────────────────────────────────────

    public function test_paginate_get_single_page(): void
    {
        $response = $this->jsonResponse(['issues' => [], 'total' => 3, 'maxResults' => 50, 'startAt' => 0]);
        $config = $this->makeConfigWithResponses([$response]);
        $api = $this->makeApi($config);

        $pages = iterator_to_array($api->callPaginateGet('search/jql', [], Search::class));

        $this->assertCount(1, $pages, 'Single page should yield exactly 1 result');
        $this->assertInstanceOf(Search::class, $pages[0], 'Yielded value should be a Search instance');
        $this->assertSame(3, $pages[0]->getTotal(), 'Total should be hydrated from response');
    }

    public function test_paginate_get_multiple_pages(): void
    {
        $page1 = $this->jsonResponse(['issues' => [], 'total' => 3, 'maxResults' => 1, 'startAt' => 0]);
        $page2 = $this->jsonResponse(['issues' => [], 'total' => 3, 'maxResults' => 1, 'startAt' => 1]);
        $page3 = $this->jsonResponse(['issues' => [], 'total' => 3, 'maxResults' => 1, 'startAt' => 2]);
        $config = $this->makeConfigWithResponses([$page1, $page2, $page3]);
        $api = $this->makeApi($config);

        $pages = iterator_to_array($api->callPaginateGet('search/jql', [], Search::class));

        $this->assertCount(3, $pages, 'Three pages should yield exactly 3 results');
        $this->assertSame(0, $pages[0]->getStartAt(), 'First page startAt should be 0');
        $this->assertSame(1, $pages[1]->getStartAt(), 'Second page startAt should be 1');
        $this->assertSame(2, $pages[2]->getStartAt(), 'Third page startAt should be 2');
    }

    public function test_paginate_get_empty_results(): void
    {
        $response = $this->jsonResponse(['issues' => [], 'total' => 0, 'maxResults' => 50, 'startAt' => 0]);
        $config = $this->makeConfigWithResponses([$response]);
        $api = $this->makeApi($config);

        $pages = iterator_to_array($api->callPaginateGet('search/jql', [], Search::class));

        $this->assertCount(1, $pages, 'Empty results should still yield 1 (empty) page');
        $this->assertSame(0, $pages[0]->getTotal(), 'Total should be 0 for empty results');
    }

    public function test_paginate_get_respects_initial_start_at(): void
    {
        $response = $this->jsonResponse(['issues' => [], 'total' => 10, 'maxResults' => 10, 'startAt' => 5]);
        $config = $this->makeConfigWithResponses([$response]);
        $api = $this->makeApi($config);

        $pages = iterator_to_array($api->callPaginateGet('search/jql', ['startAt' => 5], Search::class));

        $this->assertCount(1, $pages, 'Should yield 1 page when startAt + maxResults >= total');
        $this->assertSame(5, $pages[0]->getStartAt(), 'startAt should reflect initial offset');
    }
}
