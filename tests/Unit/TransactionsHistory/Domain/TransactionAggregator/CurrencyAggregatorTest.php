<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use PHPUnit\Framework\TestCase;

final class CurrencyAggregatorTest extends TestCase
{
    public function test_aggregate_amoount_total(): void
    {
        $transactions = [
            (new Transaction())->setCurrency('BCH')->setAmount(1.25),
            (new Transaction())->setCurrency('BCH')->setAmount(1.25),
            (new Transaction())->setCurrency('DOT')->setAmount(1.00),
        ];

        $aggregator = new CurrencyAggregator(2, 2);
        $actual = $aggregator->aggregate(...$transactions);

        self::assertSame(['BCH', 'DOT'], array_keys($actual));
        self::assertSame('2.5', $actual['BCH']['total']);
        self::assertSame('1', $actual['DOT']['total']);
    }

    public function test_aggregate_to_amount_total(): void
    {
        $transactions = [
            (new Transaction())->setToCurrency('BCH')->setToAmount(1.25),
            (new Transaction())->setToCurrency('BCH')->setToAmount(1.25),
            (new Transaction())->setToCurrency('DOT')->setToAmount(1.00),
        ];

        $aggregator = new CurrencyAggregator(2, 2);
        $actual = $aggregator->aggregate(...$transactions);

        self::assertSame(['BCH', 'DOT'], array_keys($actual));
        self::assertSame('2.5', $actual['BCH']['total']);
        self::assertSame('1', $actual['DOT']['total']);
    }

    public function test_aggregate_custom_native_currency_amount(): void
    {
        $transactions = [
            (new Transaction())->setCurrency('BCH')->setNativeAmount(2),
            (new Transaction())->setCurrency('BCH')->setNativeAmount(2),
            (new Transaction())->setCurrency('DOT')->setNativeAmount(2),
        ];

        $aggregator = new CurrencyAggregator(2, 2, 'EUR');
        $actual = $aggregator->aggregate(...$transactions);

        self::assertSame(['BCH', 'DOT'], array_keys($actual));
        self::assertSame('4.00', $actual['BCH']['EUR']);
        self::assertSame('2.00', $actual['DOT']['EUR']);
    }

    public function test_aggregate_transaction_native_currency_amount(): void
    {
        $transactions = [
            (new Transaction())->setCurrency('BCH')->setNativeAmount(2)->setNativeCurrency('NATIVE'),
            (new Transaction())->setCurrency('BCH')->setNativeAmount(2)->setNativeCurrency('NATIVE'),
            (new Transaction())->setCurrency('DOT')->setNativeAmount(2)->setNativeCurrency('NATIVE'),
        ];

        $aggregator = new CurrencyAggregator(2, 2);
        $actual = $aggregator->aggregate(...$transactions);

        self::assertSame(['BCH', 'DOT'], array_keys($actual));
        self::assertSame('4.00', $actual['BCH']['NATIVE']);
        self::assertSame('2.00', $actual['DOT']['NATIVE']);
    }

    public function test_aggregate_description(): void
    {
        $transactions = [
            (new Transaction())->setCurrency('BCH')->setTransactionDescription('desc_1'),
            (new Transaction())->setCurrency('BCH')->setTransactionDescription('desc_2'),
            (new Transaction())->setCurrency('DOT')->setTransactionDescription('desc_3'),
        ];

        $aggregator = new CurrencyAggregator(2, 2);
        $actual = $aggregator->aggregate(...$transactions);

        self::assertSame(['BCH', 'DOT'], array_keys($actual));
        self::assertSame('desc_2', $actual['BCH']['description']);
        self::assertSame('desc_3', $actual['DOT']['description']);
    }

    public function test_aggregate_last_datetime(): void
    {
        $transactions = [
            (new Transaction())->setCurrency('BCH')->setTimestampUtc('2022-01-01 00:00:03'),
            (new Transaction())->setCurrency('BCH')->setTimestampUtc('2022-01-01 00:00:02'),
            (new Transaction())->setCurrency('DOT')->setTimestampUtc('2022-01-01 00:00:01'),
        ];

        $aggregator = new CurrencyAggregator(2, 2);
        $actual = $aggregator->aggregate(...$transactions);

        self::assertSame(['BCH', 'DOT'], array_keys($actual));
        self::assertSame('2022-01-01 00:00:03', $actual['BCH']['last_datetime']);
        self::assertSame('2022-01-01 00:00:01', $actual['DOT']['last_datetime']);
    }
}
