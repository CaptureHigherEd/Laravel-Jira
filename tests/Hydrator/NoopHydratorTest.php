<?php

namespace CaptureHigherEd\LaravelJira\Tests\Hydrator;

use CaptureHigherEd\LaravelJira\Hydrator\NoopHydrator;
use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class NoopHydratorTest extends TestCase
{
    use MocksHttpResponses;

    private NoopHydrator $hydrator;

    protected function setUp(): void
    {
        $this->hydrator = new NoopHydrator;
    }

    public function test_hydrate_returns_response_unchanged(): void
    {
        $response = $this->jsonResponse(['id' => '1']);

        $result = $this->hydrator->hydrate($response, null);

        $this->assertSame($response, $result, 'NoopHydrator should return the original ResponseInterface instance unchanged');
    }

    public function test_hydrate_ignores_class_parameter(): void
    {
        $response = $this->jsonResponse(['id' => '1', 'key' => 'KEY-1', 'fields' => []]);

        $result = $this->hydrator->hydrate($response, Issue::class);

        $this->assertInstanceOf(ResponseInterface::class, $result, 'NoopHydrator should return a ResponseInterface even when a class is provided');
        $this->assertSame($response, $result, 'NoopHydrator should return the same response object, not a model');
    }

    public function test_hydrate_204_returns_response(): void
    {
        $response = $this->noContentResponse();

        $result = $this->hydrator->hydrate($response, null);

        $this->assertSame($response, $result, 'NoopHydrator should return the raw 204 response without modification');
        $this->assertSame(204, $result->getStatusCode(), 'Status code should be preserved');
    }
}
