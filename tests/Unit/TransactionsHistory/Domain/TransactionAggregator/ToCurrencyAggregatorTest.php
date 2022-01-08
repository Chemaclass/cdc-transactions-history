<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use PHPUnit\Framework\TestCase;

final class ToCurrencyAggregatorTest extends TestCase
{
    private ToCurrencyAggregator $aggregator;

    public function setUp(): void
    {
        $this->aggregator = new ToCurrencyAggregator();
    }

    public function test_manage_transactions(): void
    {
        $transactions = [
            (new Transaction())->setToCurrency('BCH')->setToAmount(1)->setNativeAmount(10),
            (new Transaction())->setToCurrency('DOT')->setToAmount(2)->setNativeAmount(20.2),
            (new Transaction())->setToCurrency('BCH')->setToAmount(3)->setNativeAmount(30.33),
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
