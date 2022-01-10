<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use PHPUnit\Framework\TestCase;

final class ToCurrencyAggregatorTest extends TestCase
{
    public function test_aggregate_total(): void
    {
        $transactions = [
            (new Transaction())->setToCurrency('BCH')->setToAmount(1.25),
            (new Transaction())->setToCurrency('BCH')->setToAmount(1.25),
            (new Transaction())->setToCurrency('DOT')->setToAmount(1.00),
        ];

        $aggregator = new ToCurrencyAggregator(2, 2);
        $actual = $aggregator->aggregate(...$transactions);

        self::assertSame(['BCH', 'DOT'], array_keys($actual));
        self::assertSame('2.50', $actual['BCH']['total']);
        self::assertSame('1.00', $actual['DOT']['total']);
    }

    public function test_aggregate_custom_native_currency_amount(): void
    {
        $transactions = [
            (new Transaction())->setToCurrency('BCH')->setNativeAmount(2),
            (new Transaction())->setToCurrency('BCH')->setNativeAmount(2),
            (new Transaction())->setToCurrency('DOT')->setNativeAmount(2),
        ];

        $aggregator = new ToCurrencyAggregator(2, 2, 'EUR');
        $actual = $aggregator->aggregate(...$transactions);

        self::assertSame(['BCH', 'DOT'], array_keys($actual));
        self::assertSame('4.00', $actual['BCH']['EUR']);
        self::assertSame('2.00', $actual['DOT']['EUR']);
    }

    public function test_aggregate_transaction_native_currency_amount(): void
    {
        $transactions = [
            (new Transaction())->setToCurrency('BCH')->setNativeAmount(2)->setNativeCurrency('NATIVE'),
            (new Transaction())->setToCurrency('BCH')->setNativeAmount(2)->setNativeCurrency('NATIVE'),
            (new Transaction())->setToCurrency('DOT')->setNativeAmount(2)->setNativeCurrency('NATIVE'),
        ];

        $aggregator = new ToCurrencyAggregator(2, 2);
        $actual = $aggregator->aggregate(...$transactions);

        self::assertSame(['BCH', 'DOT'], array_keys($actual));
        self::assertSame('4.00', $actual['BCH']['NATIVE']);
        self::assertSame('2.00', $actual['DOT']['NATIVE']);
    }

    public function test_aggregate_description(): void
    {
        $transactions = [
            (new Transaction())->setToCurrency('BCH')->setTransactionDescription('desc_1'),
            (new Transaction())->setToCurrency('BCH')->setTransactionDescription('desc_2'),
            (new Transaction())->setToCurrency('DOT')->setTransactionDescription('desc_3'),
        ];

        $aggregator = new ToCurrencyAggregator(2, 2);
        $actual = $aggregator->aggregate(...$transactions);

        self::assertSame(['BCH', 'DOT'], array_keys($actual));
        self::assertSame('desc_2', $actual['BCH']['description']);
        self::assertSame('desc_3', $actual['DOT']['description']);
    }

    public function test_aggregate_last_datetime(): void
    {
        $transactions = [
            (new Transaction())->setToCurrency('BCH')->setTimestampUtc('2022-01-01 00:00:03'),
            (new Transaction())->setToCurrency('BCH')->setTimestampUtc('2022-01-01 00:00:02'),
            (new Transaction())->setToCurrency('DOT')->setTimestampUtc('2022-01-01 00:00:01'),
        ];

        $aggregator = new ToCurrencyAggregator(2, 2);
        $actual = $aggregator->aggregate(...$transactions);

        self::assertSame(['BCH', 'DOT'], array_keys($actual));
        self::assertSame('2022-01-01 00:00:03', $actual['BCH']['last_datetime']);
        self::assertSame('2022-01-01 00:00:01', $actual['DOT']['last_datetime']);
    }
}
