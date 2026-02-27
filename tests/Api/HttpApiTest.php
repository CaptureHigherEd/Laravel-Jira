<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\HttpApi;
use CaptureHigherEd\LaravelJira\Exception\HttpClientException;
use CaptureHigherEd\LaravelJira\Models\Search;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class HttpApiTest extends TestCase
{
    use MocksHttpResponses;

    /** @return HttpApi&object{callHydrateResponse: callable, callHandleErrors: callable, callHttpGet: callable, callHttpPost: callable, callHttpPut: callable, callHttpDelete: callable, callHttpPostWithAttachments: callable} */
    private function makeApiWithResponse(ResponseInterface $response): object
    {
        return $this->makeApi($this->mockClient($response));
    }

    /** @return HttpApi&object{callHydrateResponse: callable, callHandleErrors: callable, callHttpGet: callable, callHttpPost: callable, callHttpPut: callable, callHttpDelete: callable, callHttpPostWithAttachments: callable} */
    private function makeApi(ClientInterface $client): object
    {
        return new class($client) extends HttpApi
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
        };
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

    public function test_hydrate_invalid_json_returns_empty_array(): void
    {
        $response = $this->mockResponse(200, 'not-valid-json', ['Content-Type' => 'application/json']);
        $api = $this->makeApiWithResponse($response);

        $result = $api->callHydrateResponse($response);

        $this->assertSame([], $result, 'hydrateResponse() should return an empty array when the response body contains invalid JSON');
    }

    // ── handleErrors ─────────────────────────────────────────────────────

    /** @dataProvider errorStatusProvider */
    public function test_handle_errors_throws_for_status(int $status): void
    {
        $response = $this->plainErrorResponse($status, 'error');
        $api = $this->makeApiWithResponse($response);

        $this->expectException(HttpClientException::class);

        $api->callHandleErrors($response);
    }

    /** @return array<string, array{int}> */
    public static function errorStatusProvider(): array
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
            '500' => [500],
            '502' => [502],
            '503' => [503],
            '418 unknown' => [418],
        ];
    }

    // ── HTTP verb delegation ──────────────────────────────────────────────

    public function test_http_get_passes_query(): void
    {
        $response = $this->jsonResponse([]);
        $client = $this->mockClientExpecting('GET', 'search', ['query' => ['jql' => 'project=CBE4']], $response);
        $api = $this->makeApi($client);

        $api->callHttpGet('search', ['jql' => 'project=CBE4']);
    }

    public function test_http_post_passes_json(): void
    {
        $response = $this->jsonResponse(['id' => '1', 'key' => 'KEY-1', 'fields' => []]);
        $client = $this->mockClientExpecting('POST', 'issue', ['json' => ['fields' => ['summary' => 'Test']]], $response);
        $api = $this->makeApi($client);

        $api->callHttpPost('issue', ['fields' => ['summary' => 'Test']]);
    }

    public function test_http_put_passes_json(): void
    {
        $response = $this->jsonResponse(['id' => '1', 'key' => 'KEY-1', 'fields' => []]);
        $client = $this->mockClientExpecting('PUT', 'issue/KEY-1', ['json' => ['fields' => ['summary' => 'Updated']]], $response);
        $api = $this->makeApi($client);

        $api->callHttpPut('issue/KEY-1', ['fields' => ['summary' => 'Updated']]);
    }

    public function test_http_delete_passes_path(): void
    {
        $response = $this->noContentResponse();
        $client = $this->mockClientExpecting('DELETE', 'issue/KEY-1', ['query' => []], $response);
        $api = $this->makeApi($client);

        $api->callHttpDelete('issue/KEY-1');
    }

    public function test_http_delete_passes_query_params(): void
    {
        $response = $this->noContentResponse();
        $client = $this->mockClientExpecting('DELETE', 'issue/KEY-1/watchers', ['query' => ['accountId' => 'u1']], $response);
        $api = $this->makeApi($client);

        $api->callHttpDelete('issue/KEY-1/watchers', ['accountId' => 'u1']);
    }

    public function test_http_post_with_attachments_passes_multipart_and_headers(): void
    {
        $response = $this->jsonResponse([]);
        $multipart = [['name' => 'file', 'contents' => 'file-data', 'filename' => 'test.txt']];
        $client = $this->mockClientExpecting('POST', 'issue/KEY-1/attachments', [
            'multipart' => $multipart,
            'headers' => [
                'Accept' => 'application/json',
                'X-Atlassian-Token' => 'no-check',
            ],
        ], $response);
        $api = $this->makeApi($client);

        $api->callHttpPostWithAttachments('issue/KEY-1/attachments', $multipart);
    }
}
