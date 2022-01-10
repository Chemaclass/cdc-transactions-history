<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\Service;

use App\TransactionsHistory\Domain\Service\TransactionsFilter;
use PHPUnit\Framework\TestCase;

final class TransactionsFilterTest extends TestCase
{
    private TransactionsFilter $filter;

    protected function setUp(): void
    {
        $this->filter = new TransactionsFilter();
    }

    public function test_no_filter(): void
    {
        $transactionsGroupedByType = [
            'type-1' => [
                'currency-1' => 'value-1',
                'currency-2' => 'value-2',
            ],
            'type-2' => [
                'currency-1' => 'value-1',
                'currency-2' => 'value-2',
            ],
        ];

        $actual = $this->filter->filterForGroupedByType($transactionsGroupedByType);

        self::assertEquals($transactionsGroupedByType, $actual);
    }

    public function test_filter_by_type(): void
    {
        $transactionsGroupedByType = [
            'type-1' => [
                'currency-1' => 'value-1',
                'currency-2' => 'value-2',
            ],
            'type-2' => [
                'currency-1' => 'value-1',
                'currency-2' => 'value-2',
            ],
        ];

        self::assertEquals([
            'type-2' => [
                'currency-1' => 'value-1',
                'currency-2' => 'value-2',
            ],
        ], $this->filter->filterForGroupedByType($transactionsGroupedByType, 'type-2'));
    }

    public function test_filter_by_currency(): void
    {
        $transactionsGroupedByType = [
            'type-1' => [
                'currency-1' => 'value-1',
                'currency-2' => 'value-2',
            ],
            'type-2' => [
                'currency-1' => 'value-1',
                'currency-2' => 'value-2',
            ],
        ];

        self::assertEquals([
            'type-1' => [
                'currency-2' => 'value-2',
            ],
            'type-2' => [
                'currency-2' => 'value-2',
            ],
        ], $this->filter->filterForGroupedByType($transactionsGroupedByType, null, 'currency-2'));
    }

    public function test_filter_by_type_and_currency(): void
    {
        $transactionsGroupedByType = [
            'type-1' => [
                'currency-1' => 'value-1',
                'currency-2' => 'value-2',
            ],
            'type-2' => [
                'currency-1' => 'value-1',
                'currency-2' => 'value-2',
            ],
        ];

        self::assertEquals([
            'type-2' => [
                'currency-2' => 'value-2',
            ],
        ], $this->filter->filterForGroupedByType($transactionsGroupedByType, 'type-2', 'currency-2'));
    }
}
