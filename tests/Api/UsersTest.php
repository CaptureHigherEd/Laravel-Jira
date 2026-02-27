<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\Users;
use CaptureHigherEd\LaravelJira\Exception\InvalidArgumentException;
use CaptureHigherEd\LaravelJira\Models\User;
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
        $api = new Users($this->makeConfig($response));

        $result = $api->index();

        $this->assertInstanceOf(ModelsUsers::class, $result, 'Users::index() should return a Users model instance');
        $this->assertCount(1, $result->getUsers(), 'Users::index() should return exactly 1 user from the response');
    }

    public function test_index_with_custom_params(): void
    {
        $response = $this->jsonResponse([]);
        $api = new Users($this->makeConfig($response));

        $result = $api->index(['maxResults' => 50, 'startAt' => 100]);

        $this->assertInstanceOf(ModelsUsers::class, $result, 'Users::index() should return a Users model instance when called with custom query parameters');
    }

    public function test_show(): void
    {
        $response = $this->jsonResponse(['accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => 'alice@example.com', 'active' => true]);
        $api = new Users($this->makeConfig($response));

        $result = $api->show('u1');

        $this->assertInstanceOf(User::class, $result, 'Users::show() should return a User model instance');
        $this->assertSame('u1', $result->getKey(), 'Users::show() should return the user with the correct accountId');
    }

    public function test_search(): void
    {
        $response = $this->jsonResponse([
            ['accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => 'alice@example.com', 'active' => true],
        ]);
        $api = new Users($this->makeConfig($response));

        $result = $api->search(['query' => 'alice']);

        $this->assertInstanceOf(ModelsUsers::class, $result, 'Users::search() should return a Users model instance');
        $this->assertCount(1, $result->getUsers(), 'Users::search() should return exactly 1 user from the response');
    }

    public function test_myself(): void
    {
        $response = $this->jsonResponse(['accountId' => 'me', 'displayName' => 'Current User', 'emailAddress' => 'me@example.com', 'active' => true]);
        $api = new Users($this->makeConfig($response));

        $result = $api->myself();

        $this->assertInstanceOf(User::class, $result, 'Users::myself() should return a User model instance');
        $this->assertSame('me', $result->getKey(), 'Users::myself() should return the current user with the correct accountId');
    }

    // ── Validation ────────────────────────────────────────────────────────

    public function test_show_throws_on_empty_account_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $api = new Users($this->makeConfig($this->jsonResponse([])));
        $api->show('');
    }
}
