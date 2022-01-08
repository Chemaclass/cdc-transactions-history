<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use PHPUnit\Framework\TestCase;

final class CurrencyAggregatorTest extends TestCase
{
    private CurrencyAggregator $aggregator;

    public function setUp(): void
    {
        $this->aggregator = new CurrencyAggregator();
    }

    public function test_manage_transactions(): void
    {
        $transactions = [
            (new Transaction())->setCurrency('BCH')->setAmount(1)->setNativeAmount(10),
            (new Transaction())->setCurrency('DOT')->setAmount(2)->setNativeAmount(20.2),
            (new Transaction())->setCurrency('BCH')->setAmount(3)->setNativeAmount(30.33),
        ];

        self::assertSame([
            'BCH' => [
                'total' => '4.00000000',
                'totalInEuros' => '40.33',
            ],
            'DOT' => [
                'total' => '2.00000000',
                'totalInEuros' => '20.20',
            ],
        ], $this->aggregator->aggregate(...$transactions));
    }
}
