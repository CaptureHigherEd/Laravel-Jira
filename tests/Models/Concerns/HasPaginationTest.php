<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models\Concerns;

use CaptureHigherEd\LaravelJira\Models\Concerns\HasPagination;
use CaptureHigherEd\LaravelJira\Models\Paginated;
use PHPUnit\Framework\TestCase;

class HasPaginationTest extends TestCase
{
    private function makeModel(): Paginated
    {
        return new class implements Paginated
        {
            use HasPagination;
        };
    }

    public function test_defaults_to_zero(): void
    {
        $model = $this->makeModel();

        $this->assertSame(0, $model->getTotal(), 'total should default to 0');
        $this->assertSame(0, $model->getMaxResults(), 'maxResults should default to 0');
        $this->assertSame(0, $model->getStartAt(), 'startAt should default to 0');
    }

    public function test_setters_return_static(): void
    {
        $model = $this->makeModel();

        $this->assertSame($model, $model->setTotal(10), 'setTotal() should return static');
        $this->assertSame($model, $model->setMaxResults(50), 'setMaxResults() should return static');
        $this->assertSame($model, $model->setStartAt(0), 'setStartAt() should return static');
    }

    public function test_setters_update_values(): void
    {
        $model = $this->makeModel();
        $model->setTotal(100)->setMaxResults(25)->setStartAt(50);

        $this->assertSame(100, $model->getTotal(), 'setTotal() should update total');
        $this->assertSame(25, $model->getMaxResults(), 'setMaxResults() should update maxResults');
        $this->assertSame(50, $model->getStartAt(), 'setStartAt() should update startAt');
    }

    public function test_has_more_returns_true_when_more_pages(): void
    {
        $model = $this->makeModel();
        $model->setTotal(100)->setMaxResults(25)->setStartAt(0);

        $this->assertTrue($model->hasMore(), 'hasMore() should return true when startAt + maxResults < total');
    }

    public function test_has_more_returns_false_on_last_page(): void
    {
        $model = $this->makeModel();
        $model->setTotal(100)->setMaxResults(25)->setStartAt(75);

        $this->assertFalse($model->hasMore(), 'hasMore() should return false when startAt + maxResults >= total');
    }

    public function test_has_more_returns_false_when_total_is_zero(): void
    {
        $model = $this->makeModel();
        $model->setTotal(0)->setMaxResults(50)->setStartAt(0);

        $this->assertFalse($model->hasMore(), 'hasMore() should return false when total is 0');
    }

    public function test_get_next_start_at(): void
    {
        $model = $this->makeModel();
        $model->setStartAt(25)->setMaxResults(25);

        $this->assertSame(50, $model->getNextStartAt(), 'getNextStartAt() should return startAt + maxResults');
    }

    public function test_hydrate_pagination(): void
    {
        $model = new class implements Paginated
        {
            use HasPagination;

            public function callHydratePagination(array $data): void
            {
                $this->hydratePagination($data);
            }
        };

        $model->callHydratePagination(['total' => 42, 'maxResults' => 10, 'startAt' => 20]);

        $this->assertSame(42, $model->getTotal(), 'hydratePagination() should set total');
        $this->assertSame(10, $model->getMaxResults(), 'hydratePagination() should set maxResults');
        $this->assertSame(20, $model->getStartAt(), 'hydratePagination() should set startAt');
    }

    public function test_pagination_to_array(): void
    {
        $model = new class implements Paginated
        {
            use HasPagination;

            /** @return array<string, int> */
            public function callPaginationToArray(): array
            {
                return $this->paginationToArray();
            }
        };

        $model->setTotal(99)->setMaxResults(20)->setStartAt(40);

        $this->assertSame(
            ['total' => 99, 'maxResults' => 20, 'startAt' => 40],
            $model->callPaginationToArray(),
            'paginationToArray() should return the correct pagination keys'
        );
    }
}
