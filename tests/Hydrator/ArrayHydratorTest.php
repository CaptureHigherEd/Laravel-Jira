<?php

namespace CaptureHigherEd\LaravelJira\Tests\Hydrator;

use CaptureHigherEd\LaravelJira\Exception\HydrationException;
use CaptureHigherEd\LaravelJira\Hydrator\ArrayHydrator;
use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class ArrayHydratorTest extends TestCase
{
    use MocksHttpResponses;

    private ArrayHydrator $hydrator;

    protected function setUp(): void
    {
        $this->hydrator = new ArrayHydrator;
    }

    public function test_hydrate_200_returns_array(): void
    {
        $data = ['id' => '1', 'key' => 'KEY-1'];
        $response = $this->jsonResponse($data);

        $result = $this->hydrator->hydrate($response, null);

        $this->assertSame($data, $result, 'ArrayHydrator should return the raw decoded array from a 200 response');
    }

    public function test_hydrate_ignores_class_and_returns_array(): void
    {
        $data = ['id' => '1', 'key' => 'KEY-1', 'fields' => []];
        $response = $this->jsonResponse($data);

        $result = $this->hydrator->hydrate($response, Issue::class);

        $this->assertSame($data, $result, 'ArrayHydrator should always return an array, never a model instance');
    }

    public function test_hydrate_204_returns_empty_array(): void
    {
        $response = $this->noContentResponse();

        $result = $this->hydrator->hydrate($response, null);

        $this->assertSame([], $result, 'ArrayHydrator on a 204 response should return an empty array');
    }

    public function test_hydrate_empty_body_returns_empty_array(): void
    {
        $response = $this->mockResponse(200, '');

        $result = $this->hydrator->hydrate($response, null);

        $this->assertSame([], $result, 'ArrayHydrator on an empty body should return an empty array');
    }

    public function test_hydrate_invalid_json_throws_hydration_exception(): void
    {
        $response = $this->mockResponse(200, 'not-json');

        $this->expectException(HydrationException::class);
        $this->hydrator->hydrate($response, null);
    }
}
