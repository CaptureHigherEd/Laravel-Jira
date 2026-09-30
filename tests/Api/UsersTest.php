<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\Users;
use CaptureHigherEd\LaravelJira\Models\Users as ModelsUsers;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    use MocksHttpResponses;

    public function test_index_with_default_max_results(): void
    {
        $response = $this->jsonResponse([
            ['accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => 'alice@example.com', 'active' => true],
        ]);
        $client = $this->mockClientExpecting('GET', 'users', ['query' => ['maxResults' => 1000]], $response);
        $api = new Users($client);

        $result = $api->index();

        $this->assertInstanceOf(ModelsUsers::class, $result, 'Users::index() should return a Users model instance');
        $this->assertCount(1, $result->getUsers(), 'Users::index() should return exactly 1 user from the response');
    }

    public function test_index_with_custom_params(): void
    {
        $response = $this->jsonResponse([]);
        $client = $this->mockClientExpecting('GET', 'users', ['query' => ['maxResults' => 50, 'startAt' => 100]], $response);
        $api = new Users($client);

        $result = $api->index(['maxResults' => 50, 'startAt' => 100]);

        $this->assertInstanceOf(ModelsUsers::class, $result, 'Users::index() should return a Users model instance when called with custom query parameters');
    }
}
