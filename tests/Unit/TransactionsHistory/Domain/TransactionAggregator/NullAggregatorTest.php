<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\TransactionAggregator\NullAggregator;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use PHPUnit\Framework\TestCase;

final class NullAggregatorTest extends TestCase
{
    private NullAggregator $aggregator;

    public function setUp(): void
    {
        $this->aggregator = new NullAggregator();
    }

    public function test_manage_transactions(): void
    {
        $transactions = [
            (new Transaction())->setCurrency('BCH')->setAmount(1)->setNativeAmount(10),
            (new Transaction())->setCurrency('DOT')->setAmount(2)->setNativeAmount(20.2),
            (new Transaction())->setCurrency('BCH')->setAmount(3)->setNativeAmount(30.33),
        ];

        self::assertSame([], $this->aggregator->aggregate(...$transactions));
    }
}
