<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use PHPUnit\Framework\TestCase;

final class CurrencyAggregatorTest extends TestCase
{
    public function test_aggregate(): void
    {
        $transactions = [
            (new Transaction())
                ->setCurrency('BCH')
                ->setAmount(1.25)
                ->setNativeAmount(2)
                ->setNativeAmountInUSD(3),
            (new Transaction())
                ->setCurrency('BCH')
                ->setAmount(1.25)
                ->setNativeAmount(2)
                ->setNativeAmountInUSD(3),
            (new Transaction())
                ->setCurrency('DOT')
                ->setAmount(1.00)
                ->setNativeAmount(2)
                ->setNativeAmountInUSD(3),
        ];

        $aggregator = new CurrencyAggregator(2, 'EUR', 2);

        self::assertSame([
            'BCH' => [
                'total' => '2.50',
                'EUR' => '4.00',
                'USD' => '6.00',
            ],
            'DOT' => [
                'total' => '1.00',
                'EUR' => '2.00',
                'USD' => '3.00',
            ],
        ], $aggregator->aggregate(...$transactions));
    }
}
