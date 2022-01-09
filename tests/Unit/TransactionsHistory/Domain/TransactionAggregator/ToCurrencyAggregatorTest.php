<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use PHPUnit\Framework\TestCase;

final class ToCurrencyAggregatorTest extends TestCase
{
    public function test_aggregate(): void
    {
        $transactions = [
            (new Transaction())
                ->setToCurrency('BCH')
                ->setToAmount(1.25)
                ->setNativeAmount(2)
                ->setNativeAmountInUSD(3),
            (new Transaction())
                ->setToCurrency('BCH')
                ->setToAmount(1.25)
                ->setNativeAmount(2)
                ->setNativeAmountInUSD(3),
            (new Transaction())
                ->setToCurrency('DOT')
                ->setToAmount(1.00)
                ->setNativeAmount(2)
                ->setNativeAmountInUSD(3),
        ];

        $aggregator = new ToCurrencyAggregator(2, 'EUR', 2);

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
