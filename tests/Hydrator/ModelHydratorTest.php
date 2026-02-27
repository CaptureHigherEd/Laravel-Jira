<?php

namespace CaptureHigherEd\LaravelJira\Tests\Hydrator;

use CaptureHigherEd\LaravelJira\Exception\HydrationException;
use CaptureHigherEd\LaravelJira\Hydrator\ModelHydrator;
use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class ModelHydratorTest extends TestCase
{
    use MocksHttpResponses;

    private ModelHydrator $hydrator;

    protected function setUp(): void
    {
        $this->hydrator = new ModelHydrator;
    }

    public function test_hydrate_200_with_class_returns_model(): void
    {
        $response = $this->jsonResponse(['id' => '1', 'key' => 'KEY-1', 'fields' => []]);

        $result = $this->hydrator->hydrate($response, Issue::class);

        $this->assertInstanceOf(Issue::class, $result, 'ModelHydrator should hydrate a 200 response into the given model class');
        $this->assertSame('KEY-1', $result->getKey(), 'ModelHydrator should pass decoded data to the model factory');
    }

    public function test_hydrate_200_without_class_returns_array(): void
    {
        $data = ['foo' => 'bar'];
        $response = $this->jsonResponse($data);

        $result = $this->hydrator->hydrate($response, null);

        $this->assertSame($data, $result, 'ModelHydrator without a class should return the raw decoded array');
    }

    public function test_hydrate_204_without_class_returns_empty_array(): void
    {
        $response = $this->noContentResponse();

        $result = $this->hydrator->hydrate($response, null);

        $this->assertSame([], $result, 'ModelHydrator on a 204 response without class should return an empty array');
    }

    public function test_hydrate_204_with_class_returns_empty_model(): void
    {
        $response = $this->noContentResponse();

        $result = $this->hydrator->hydrate($response, Issue::class);

        $this->assertInstanceOf(Issue::class, $result, 'ModelHydrator on a 204 response with class should return an empty model instance');
    }

    public function test_hydrate_empty_body_returns_empty_array(): void
    {
        $response = $this->mockResponse(200, '');

        $result = $this->hydrator->hydrate($response, null);

        $this->assertSame([], $result, 'ModelHydrator on an empty body should return an empty array');
    }

    public function test_hydrate_invalid_json_throws_hydration_exception(): void
    {
        $response = $this->mockResponse(200, 'not-json');

        $this->expectException(HydrationException::class);
        $this->hydrator->hydrate($response, null);
    }
}
